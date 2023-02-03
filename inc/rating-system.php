<?php

class APD_Rating_System {

    private static $dev = false;

    static $option = array(
        '0' => '0. Trash',
        '1' => '1. Horrible',
        '2' => '2. Bad',
        '3' => '3. So so',
        '4' => '4. Great',
        '5' => '5. Mind Blown!',
    );

    private static $instance;

    public static function get_instance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_apd_rate_product', array(self::class, 'apd_rate_product'));
        // rating json+ld for product
        add_filter('woocommerce_structured_data_product', array(self::class, 'woocommerce_structured_data_product'), 99, 2);
        add_filter('wp_footer', array(self::class, 'apd_product_ld_json'));

        // reset duplicated product's rating data
        add_action('woocommerce_product_duplicate', array(self::class, 'woocommerce_product_duplicate'), 99, 2);
    }

    /**
     * reset duplicated product's rating data
     * @param \WC_Product $duplicate
     * @param \WC_Product $product
     */
    public static function woocommerce_product_duplicate($duplicate, $product) {
        delete_post_meta($duplicate->get_id(), '_apd_rating_count');
        delete_post_meta($duplicate->get_id(), '_apd_rating_sum');
    }

    public static function apd_product_ld_json() {
        $page_id = get_the_ID();
        $is_shop_page = get_post_meta($page_id, 'is_shop_page', true) == 'yes';
        if ($is_shop_page) {
            $product_id = get_post_meta($page_id, 'meta_product_id', true);
            $product = wc_get_product($product_id);
            WC()->structured_data->generate_product_data($product);

            $types = array('product', 'review', 'breadcrumblist', 'order');
            $data = WC()->structured_data->get_structured_data($types);
            if ($data) {
                echo '<script type="application/ld+json">' . wc_esc_json(wp_json_encode($data), true) . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }
    }

    /**
     * @param int $value
     * @param WC_Product $product
     *
     * @return int
     */
    public static function woocommerce_structured_data_product($markup, $product) {
        $rating_data = self::get_product_rating_data($product->get_id());

        if (self::$dev) {
            $rating_data['rating_count'] = 93;
        }

        if ($rating_data['rating_count'] > 0) {
            $markup['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $rating_data['average_rating'],
                'reviewCount' => $rating_data['rating_count'],
            );
        }

        if (self::$dev) {
            var_dump($rating_data, $markup);
        }

        return $markup;
    }

    public static function get_product_rating_data($product_id) {
        $rating_count = get_post_meta($product_id, '_apd_rating_count', true);
        $rating_count = is_numeric($rating_count) ? intval($rating_count) : 0;
        $rating_sum = get_post_meta($product_id, '_apd_rating_sum', true);
        $rating_sum = is_numeric($rating_sum) ? doubleval($rating_sum) : 0;

        // average
        $average_rating = $rating_sum / $rating_count;
        $rounded_average_rating = round($average_rating, 0);

        return array(
            'average_rating' => number_format($average_rating, 1, '.', ''),
            'rating_count' => $rating_count,
            'rounded_average_rating' => $rounded_average_rating,
            'rounded_average_rating_text' => self::$option[$rounded_average_rating],
        );
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public static function get_rating_list($limit = 20) {
        $rating_list = get_option('_apd_rating_list');
        $rating_list = is_array($rating_list) ? $rating_list : array();
        // asort($rating_list);
        uasort($rating_list, function ($item1, $item2) {
            if ($item1['average_rating'] < $item2['average_rating']) {
                return 1;
            }

            if ($item1['average_rating'] > $item2['average_rating']) {
                return -1;
            }

            if ($item1['rating_count'] <= $item2['rating_count']) {
                return 1;
            }

            return -1;
        });
        // var_dump($rating_list);
        // $rating_list = array_reverse($rating_list, true);
        $rating_list = array_keys($rating_list);
        // $rating_list = array_slice($rating_list, 0, $limit);
        $shop_product_list = array();
        $shop_length = 0;
        foreach ($rating_list as $product_id) {
            if (self::is_shop_product($product_id)) {
                $shop_product_list[] = $product_id;
                $shop_length++;

                if ($shop_length > $limit) {
                    break;
                }
            }
        }
        return $shop_product_list;
    }

    private static function is_shop_product($product_id) {
        $product_type_single = get_post_meta($product_id, '_product_type_single', true);
        return $product_type_single == 'yes';
    }

    public static function product_rating_full_html($product_id) {
        $product_rating = self::get_product_rating_data($product_id);

        // show nothing if no rating
        if (!$product_rating['rating_count'] && !self::$dev) {
            return;
        }
        ?>
        <div class="rsc__average-rating">
            <div class="rsc__average-rating__rating"
                 data-rating="<?php echo $product_rating['rounded_average_rating']; ?>">
                <span><?php echo $product_rating['average_rating']; ?></span><span>/5.0</span>
            </div>
            <div class="rsc__average-rating__help-text">On average, users rated this product
                <span><?php echo $product_rating['rounded_average_rating_text']; ?></span>
            </div>
            <div class="rsc__text-apd-meter">APD METER</div>
            <div class="rsc__total-count">
                <?php printf('(Total review%2$s: <span>%1$s</span>)', $product_rating['rating_count'], $product_rating['rating_count'] > 1 ? 's' : ''); ?>
            </div>
        </div>
        <?php
    }

    public static function product_rating_one_line_html($product_id, $snippet = false) {
        $product_rating = self::get_product_rating_data($product_id);

        // show nothing if no rating
        if (!$product_rating['rating_count'] && !self::$dev) {
            return;
        }
        ?>
        <?php if ($snippet): ?>
            <div class="rsc__average-rating short">
                <div class="rsc__average-rating__rating"
                     data-rating="<?php echo $product_rating['rounded_average_rating']; ?>"
                     itemprop="aggregateRating"
                     itemscope
                     itemtype="https://schema.org/AggregateRating">
                    <span itemprop="ratingValue"><?php echo $product_rating['average_rating']; ?></span><span>/5.0</span>
                    <span><?php printf('(Total review%2$s: <span itemprop="ratingCount">%1$s</span>)', $product_rating['rating_count'], $product_rating['rating_count'] > 1 ? 's' : ''); ?></span>
                </div>
            </div>
        <?php else: ?>
            <div class="rsc__average-rating short">
                <div class="rsc__average-rating__rating" data-rating="<?php echo $product_rating['rounded_average_rating']; ?>">
                    <span><?php echo $product_rating['average_rating']; ?></span><span>/5.0</span>
                    <span><?php printf('(Total review%2$s: %1$s)', $product_rating['rating_count'], $product_rating['rating_count'] > 1 ? 's' : ''); ?></span>
                </div>
            </div>
        <?php endif; ?>
        <?php
    }

    public static function product_rating_short_html($product_id) {
        $product_rating = self::get_product_rating_data($product_id);

        // show nothing if no rating
        if (!$product_rating['rating_count'] && !self::$dev) {
            return;
        }
        ?>
        <div class="rsc__average-rating short">
            <div class="rsc__average-rating__rating"
                 data-rating="<?php echo $product_rating['rounded_average_rating']; ?>">
                <span><?php echo $product_rating['average_rating']; ?></span><span>/5.0</span>
            </div>
        </div>
        <?php
    }

    /**
     * @param int $product_id
     *
     * @return bool
     */
    public static function is_top_rated($product_id) {
        $rating_data = self::get_product_rating_data($product_id);

        if ($rating_data['rounded_average_rating'] == 5) {
            return true;
        }

        return false;
    }

    public static function apd_rate_product() {
        $product_id = isset($_POST['product_id']) && is_numeric($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $rating = isset($_POST['rating']) && is_numeric($_POST['rating']) ? intval($_POST['rating']) : -1;

        if (!array_key_exists($rating, self::$option)) {
            wp_send_json_error(1);
            return;
        }

        $post_type = get_post_type($product_id);
        if ($post_type != 'product') {
            wp_send_json_error(2);
            return;
        }

        if (!self::current_user_can_rate_product($product_id)) {
            wp_send_json_error(3);
            return;
        }

        $user_id = get_current_user_id();
        $rating_data = self::rate_product($user_id, $product_id, $rating);
        // $rating_data = null;

        if (!$rating_data) {
            wp_send_json_error(4);
            return;
        }

        wp_send_json_success($rating_data);
    }

    private static function rate_product($user_id, $product_id, $rating) {
        // self::log(array($user_id, $product_id));
        // add data to database
        $success = self::insert_rating_row($user_id, $product_id, $rating);

        if (!$success) {
            return null;
        }

        // update product average rating
        $rating_data = self::update_product_average_rating($product_id, $rating);

        // add $5 to the customer rewards
        self::add_rating_rewards($user_id);

        return $rating_data;
    }

    private static function add_rating_rewards($user_id) {
        $reward_points = get_user_meta($user_id, '_reward_points', true);
        $reward_points = $reward_points ? $reward_points : 0;
        $reward_points += 5;
        update_user_meta($user_id, '_reward_points', $reward_points);
    }

    /**
     * @param int $product_id
     * @param int $new_rating
     *
     * @return array|null
     */
    private static function update_product_average_rating($product_id, $new_rating) {
        $rating_count = get_post_meta($product_id, '_apd_rating_count', true);
        $rating_count = is_numeric($rating_count) ? intval($rating_count) : 0;
        $rating_sum = get_post_meta($product_id, '_apd_rating_sum', true);
        $rating_sum = is_numeric($rating_sum) ? doubleval($rating_sum) : 0;

        // new rating
        $new_rating_sum = $rating_sum + $new_rating;
        $new_count = $rating_count + 1;

        // update new average data
        update_post_meta($product_id, '_apd_rating_sum', $new_rating_sum);
        update_post_meta($product_id, '_apd_rating_count', $new_count);

        //
        $new_rating_data = self::get_product_rating_data($product_id);

        // update rating list
        $rating_list = get_option('_apd_rating_list');
        $rating_list = is_array($rating_list) ? $rating_list : array();
        // $rating_list[$product_id] = $new_rating_sum / $new_count;
        // self::log(array('before', $rating_list, $product_id));
        $rating_list[$product_id] = $new_rating_data;
        // self::log(array('after', $rating_list));
        update_option('_apd_rating_list', $rating_list);

        // maybe optimize this by calculating fields directly here?
        return $new_rating_data;
    }

    public static function recalculate_rating_list() {
        global $wpdb;
        $product_list = $wpdb->get_results("SELECT `post_id` FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` IN ('_apd_rating_sum', '_apd_rating_count');");
        $product_list = wp_list_pluck($product_list, 'post_id');
        $product_list = array_unique($product_list);

        $rating_list = array();
        foreach ($product_list as $product_id) {
            $rating_list[$product_id] = self::get_product_rating_data($product_id);
        }
        // var_dump($rating_list);
        update_option('_apd_rating_list', $rating_list);
    }

    private static function insert_rating_row($user_id, $product_id, $rating) {
        global $wpdb;
        $table = self::get_table_name();
        $success = $wpdb->insert($table, array(
            'user_id' => $user_id,
            'product_id' => $product_id,
            'rating' => $rating,
            'created_at' => time(),
        ), array(
            '%d',
            '%d',
            '%d',
            '%d',
        ));

        // self::log(array($wpdb->last_query, $wpdb->last_error));

        return !!$success;
    }

    public static function current_user_can_rate_product($product_id) {
        // the user is not logged in
        if (!is_user_logged_in()) {
            return false;
        }

        $user = _wp_get_current_user();

        $is_customer_bought_product = wc_customer_bought_product($user->user_email, $user->ID, $product_id);

        // the customer never bought this product
        if (!$is_customer_bought_product) {
            return false;
        }

        // the customer already rated this product
        if (self::customer_rated_product($user->ID, $product_id) && !self::$dev) {
            return false;
        }

        return true;
    }

    private static function customer_rated_product($user_id, $product_id) {
        global $wpdb;
        $table = self::get_table_name();
        $has_row = !!$wpdb->get_row($wpdb->prepare("SELECT * FROM `${table}` WHERE `user_id`=%d AND `product_id`=%d;", $user_id, $product_id));
        return $has_row;
    }

    /**
     * CREATE TABLE `wp_apd_rating` (
     * `ID` bigint(20) NOT NULL,
     * `user_id` bigint(20) NOT NULL,
     * `product_id` bigint(20) NOT NULL,
     * `rating` tinyint(4) NOT NULL,
     * `created_at` bigint(20) NOT NULL
     * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
     */
    private static function get_table_name() {
        global $wpdb;
        return "{$wpdb->prefix}apd_rating";
    }

    private static function log($content) {
        ob_start();
        var_dump($content);
        $log = ob_get_clean();
        file_put_contents(__DIR__ . '/debug.log', $log . PHP_EOL . PHP_EOL, FILE_APPEND);
    }
}

APD_Rating_System::get_instance();
