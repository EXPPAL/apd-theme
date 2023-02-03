<?php

class APD_Airdrops {

    public static $version = '0.1';
    private $notices = array();
    
    function __construct() {
        
        add_action( 'woocommerce_cart_contents', array( $this, 'show_airdrop_offers') );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts') );
        
        add_action( 'wp_ajax_claim_airdrop', array( $this, 'ajax_put_airdrop_in_cart') );
        add_action( 'wp_ajax_nopriv_claim_airdrop', array( $this, 'ajax_put_airdrop_in_cart') );
        
        add_action( 'woocommerce_order_status_processing', array( $this, 'remove_invalid_airdrops') );
        add_action( 'woocommerce_order_status_completed', array( $this, 'mark_airdrops_claimed') );
        add_action( 'wp_login', array( $this, 'remove_invalid_airdrops_from_cart'), 10, 2 );
        add_action( 'woocommerce_cart_item_removed', array( $this, 'check_to_remove_all_airdrops_from_cart'), 10, 2 );
        
        add_filter( 'woocommerce_apd_cart_price', array( $this, 'display_airdrop_price'), 10, 2 );
        add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'display_airdrop_price_on_checkout'), 100, 3 );
        /*
        add_action( 'woocommerce_before_checkout_form', array( $this, 'display_notices') );
        add_action( 'woocommerce_before_cart', array( $this, 'display_notices') );
*/
        $airdrops_data = array(
            'ajax_url'        => admin_url( 'admin-ajax.php' ),
            'all_airdrops'    => self::get_all_airdrop_product_ids()
        );

        
        wp_register_script( 'airdrops-frontend', get_template_directory_uri() . '/js/airdrops-frontend.js', array( 'jquery' ), self::$version, true );    
        wp_localize_script( 'airdrops-frontend', 'airdrops_data', $airdrops_data );
        
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script( 'airdrops-frontend' );
    }
    /*
    public function display_notices() {
        foreach ( $this->notices as $notice ) {
            wc_print_notice( $notice, 'notice' );
        }
    }
    
    public function add_notice( $notice ) {
        $this->notices[] = $notice;
    }
     * 
     */
    
    static function get_all_airdrop_product_ids() {
        global $wpdb;
        $wp = $wpdb->prefix;

        $airdrop_product_ids = array();

        $query_sql = "SELECT p.ID from {$wp}posts AS p "
                . " LEFT JOIN `{$wp}postmeta` AS pm on p.`ID` = pm.`post_id`"
                . " LEFT JOIN `{$wp}postmeta` AS pm_stock on p.`ID` = pm_stock.`post_id`"
                . " WHERE pm.meta_key = '_product_air_drop' AND pm.meta_value = 'yes' "		// select only airdrop products
                . " AND pm_stock.meta_key = '_stock_status' AND pm_stock.meta_value = 'instock' "	// select only products in stock 
                . " AND p.post_type = 'product' AND p.post_status = 'publish' ";

        $rows = $wpdb->get_results( $query_sql, ARRAY_A );

        foreach ( $rows as $row ) {
            $airdrop_product_ids[] = $row['ID'];
        }

        return $airdrop_product_ids;
    }

    static function get_all_airdrop_products() {
        $products = array();
        $ids = self::get_all_airdrop_product_ids();
        foreach ( $ids as $id ) {
            $products[$id] = get_product($id); 
        }

        return $products;
    }

    public function display_airdrop_price( $price, $product_id ) {
        if ( $product_id && self::check_if_airdrop( $product_id) ) {
            return 'Free';
        }
        return '$' . $price;
    }

    public function display_airdrop_price_on_checkout( $price, $cart_item, $cart_item_key ) {
        $product_id = $cart_item['product_id'];
        if ( $product_id && self::check_if_airdrop( $product_id) ) {
            return 'Free';
        }
        return $price;
    }
     
    public function show_airdrop_offers() {
        $user_id = get_current_user_id();

        $available_airdrops = self::get_available_airdrop_products( $user_id );
                
        $eligible_for_airdrops = self::check_if_deal_product_in_cart() && count($available_airdrops) > 0;

        if ( $eligible_for_airdrops ) {

            $colspan = is_user_logged_in() ? 6 : 5;
            ?>
            <tr>
                <td colspan="<?php echo $colspan; ?>" class="airdrops-header">
                    <?php if ( count($available_airdrops) == 1 ) : ?>
                        <span>You can also claim this Free Gift of the Month &darr;&darr;&darr;</span>
                    <?php else: ?>
                        <span>You can also claim any of these Free Gifts of the Month &darr;&darr;&darr;</span>  
                    <?php endif; ?>
                </td>
            </tr>

            <?php

            foreach( $available_airdrops as $airdrop_product ) {
                $product_link = $airdrop_product->get_permalink();
                ?>
                <tr>
                    <td class="product-remove">
                    </td>
                    <td class="product-thumbnail airdrop-product-thumbnail">
                        <?php
                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $airdrop_product->get_image(), false, false);
                        printf('<a href="%s">%s</a>', esc_url($product_link), $thumbnail);

                        ?>
                    </td>
                    <td class="product-name airdrop-product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                        <?php
                        if ( ! $product_link ) {
                            echo apply_filters('woocommerce_cart_item_name', $airdrop_product->get_title(), false, false) . '&nbsp;';
                        } else {
                            echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_link), $airdrop_product->get_title()), false, false);
                        }
                        ?>
                    </td>
                    <td class="product-price product-price-airdrop" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                        Free
                    </td>
                    <?php if (is_user_logged_in()): ?>
                        <td class="product-airdrop" data-title="">
                        </td>
                        <td class="product-airdrop" data-title="" style="text-align: center;">
                            <span class="add-airdrop" data-airdrop-id="<?php echo $airdrop_product->get_id(); ?>">Claim</span>
                            <img class="loading-cart" src="/wp-content/themes/apd/images/loading-deal-3.gif" alt="..." style="display:none">
                        </td>
                    <?php else: ?>
                        <td class="product-airdrop" data-title="" style="text-align: center;">
                            <span class="add-airdrop" data-airdrop-id="<?php echo $airdrop_product->get_id(); ?>">Claim</span>
                            <img class="loading-cart" src="/wp-content/themes/apd/images/loading-deal-3.gif" alt="..." style="display:none">
                        </td>
                    <?php endif; ?>

                </tr>
                <?php
            }
        }


    }


    public function ajax_put_airdrop_in_cart() {
        if (isset($_POST['airdrop_id']) && absint($_POST['airdrop_id']) > 0 ) {

            $product_id = absint($_POST['airdrop_id']);
            if ( self::check_if_airdrop($product_id) && ! self::check_if_airdrop_in_cart($product_id) ) { // must be an airdrop. and not yet in cart
                $user_id = get_current_user_id();

                if ( $user_id ) {
                    $available_airdrops = self::get_available_airdrop_products( $user_id );
                    if ( array_key_exists( $product_id, $available_airdrops ) ) { // check if this airdrop has been already claimed 
                        WC()->cart->add_to_cart($product_id, 1);
                        wp_send_json_success('OK');
                    }
                    else wp_send_json_error('404');
                }
                else {
                    $available_airdrops = self::get_all_airdrop_product_ids();
                    if ( in_array( $product_id, $available_airdrops ) ) {
                        WC()->cart->add_to_cart($product_id, 1);
                        wp_send_json_success('OK');
                    }
                    else wp_send_json_error('404');
                }
            }
            else wp_send_json_error('404');
        }
        else wp_send_json_error('403');
    }

    static function check_if_airdrop( $product_id ) {
        global $wpdb;
        $wp = $wpdb->prefix;

        $query_sql = $wpdb->prepare("SELECT p.ID from {$wp}posts AS p "
                . " LEFT JOIN `{$wp}postmeta` AS pm on p.`ID` = pm.`post_id`"
                . " LEFT JOIN `{$wp}postmeta` AS pm_stock on p.`ID` = pm_stock.`post_id`"
                . " WHERE pm.meta_key = '_product_air_drop' AND pm.meta_value = 'yes' "		// select only airdrop products
                . " AND pm_stock.meta_key = '_stock_status' AND pm_stock.meta_value = 'instock' "	// select only products in stock 
                . " AND p.post_type = 'product' AND p.post_status = 'publish' AND p.ID = %d ", $product_id);

        $result = $wpdb->get_row( $query_sql, ARRAY_A );

       
        return $result;
    }
    
    // fires on order being completed. 
    public function mark_airdrops_claimed( $order_id ) {
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        $order_items = $order->get_items();
    
        foreach ( $order_items as $item ) {
            $product_id = $item['product_id'];
            if ( self::check_if_airdrop( $product_id ) ) {
                self::claim_airdrop( $user_id, $product_id );
            }
        }
    }
    
    // fires when a product is removed from cart 
    public function check_to_remove_all_airdrops_from_cart($cart_item_key, $cart ) {
                
        if ( ! self::check_if_deal_product_in_cart( $cart ) ) {

            $items = $cart->get_cart();    
            
            foreach( $items as $cart_item_key => $cart_item ) {
                $product_id = $cart_item['product_id'];

                if ( self::check_if_airdrop( $product_id ) ) {
                    WC()->cart->remove_cart_item( $cart_item_key );
                    wc_add_notice('Free Gift of the Month has been removed from your cart since you don\'t have deal item in cart');
                }
            }
        }

    }
    
    // fires on order being processed. 
    public function remove_invalid_airdrops( $order_id ) {
        $user_id = get_current_user_id();
        
        if ( $user_id ) {

            $order = wc_get_order($order_id);

            if ( $order ) {
                $order_items = $order->get_items();

                foreach ( $order_items as $item_id => $item ) {
                    $product_id = $item['product_id'];
                    if ( self::check_if_airdrop( $product_id ) ) {
                        if ( ! self::user_eligible_for_airdrop( $user_id, $product_id ) ) {
                            wc_delete_order_item($item_id);
                            wc_add_notice('Free Gift of the Month has been removed from your order since you have already claimed it');
                        }
                    }
                }
            }
        }
    }
    
    // fires on user login. 
    public function remove_invalid_airdrops_from_cart( $user_login, $user ) {
        
        $user_id = $user->ID;
        
        if ( $user_id ) {

            $items = WC()->cart->get_cart();

            foreach( $items as $cart_item_key => $cart_item ) {
                $product_id = $cart_item['product_id'];
                
                if ( self::check_if_airdrop( $product_id ) ) {
                    if ( ! self::user_eligible_for_airdrop( $user_id, $product_id ) ) {
                        WC()->cart->remove_cart_item( $cart_item_key );
                        wc_add_notice('Free Gift of the Month has been removed from your cart since you have already claimed it');
                    }
                }
            }
        }
    }
    
    static function user_eligible_for_airdrop( $user_id, $airdrop_id ) {
        $user_aidrops = self::get_claimed_aidrop_ids( $user_id );
        
        $is_claimed = isset($user_aidrops[$airdrop_id]);
        $is_unclaimed = (isset($user_aidrops[$airdrop_id]) && $user_aidrops[$airdrop_id] == 0 );
        if ( ! $is_claimed || $is_unclaimed ) {
            return true;
        }
        return false;
    }
    
    // todo: should be done after successful checkout
    static function claim_airdrop( $user_id, $product_id ) {
        $airdrop_list = (array) get_user_meta($user_id, 'airdrops_list', true);
        $airdrop_list[$product_id] = 1;
        update_user_meta($user_id, 'airdrops_list', $airdrop_list);
    }

    static function unclaim_airdrop( $user_id, $product_id ) {
        $airdrop_list = (array) get_user_meta($user_id, 'airdrops_list', true);
        $airdrop_list[$product_id] = 0;
        update_user_meta($user_id, 'airdrops_list', $airdrop_list);
    }
    
    static function check_if_airdrop_in_cart( $product_id ) {
        $items = WC()->cart->get_cart();
        
        foreach( $items as $cart_item ) {
            if ( $cart_item['product_id'] == $product_id ) {
                return true;
            }
        }

        return false;
    }
    
    static function check_if_deal_product_in_cart( $cart = false ) {
        if ( ! is_a($cart, 'WC_Cart') ) {
            $cart = WC()->cart;
        }
        $items = $cart->get_cart();
        
        foreach( $items as $cart_item ) {
            if (get_post_meta($cart_item['product_id'], '_product_big_deal', true) == 'yes') {
                return true;
            }
        }
        
        return false;
    }

    static function get_claimed_aidrop_ids( $user_id ) {
        $airdrop_list = (array) get_user_meta($user_id, 'airdrops_list', true);
        return $airdrop_list;
    }

    static function get_available_airdrop_products( $user_id = 0 ) {
        $all_airdrops = self::get_all_airdrop_product_ids();
        
        if ( $user_id ) {
            $user_aidrops = self::get_claimed_aidrop_ids( $user_id );
        }
        else {
            $user_aidrops = array();
        }
        
        $available_airdrops = array();

        foreach ( $all_airdrops as $airdrop_id ) {
            if ( ! self::check_if_airdrop_in_cart($airdrop_id) ) { // must be not in cart
                
                $is_claimed = isset($user_aidrops[$airdrop_id]);
                $is_unclaimed = (isset($user_aidrops[$airdrop_id]) && $user_aidrops[$airdrop_id] == 0 );
                
                if ( ! $is_claimed || $is_unclaimed ) { 
                    $available_airdrops[$airdrop_id] = get_product($airdrop_id);
                }
            }
        }

        return $available_airdrops;
    }

   
}

$airdrop_feature = new APD_Airdrops();