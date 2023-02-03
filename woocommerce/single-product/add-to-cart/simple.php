<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.4.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * @var \WC_Product $product
 */
global $product;

if (!$product->is_purchasable()) {
    return;
}

$disable_rewards_and_offer_base_price = is_disable_rewards_and_offer_base_price($product->get_id());

// Availability
$availability = $product->get_availability();
$availability_html = empty($availability['availability']) ? '' : '<p class="stock ' . esc_attr($availability['class']) . '">' . esc_html($availability['availability']) . '</p>';

echo apply_filters('woocommerce_stock_html', $availability_html, $availability['availability'], $product);
?>

<?php if ($product->is_in_stock()) : ?>
    <?php if (get_post_meta(get_the_ID(), '_product_type_single', true) != 'yes' && !$product->is_type('variable')): ?>
        <?php do_action('woocommerce_before_add_to_cart_form'); ?>
        <a name="add_to_cart_back"></a>
        <div class="pricing">
            <div class="BuyNow_Button">
                <div>
                    <form class="cart" method="post" enctype='multipart/form-data'>
                        <p>
                            <button type="submit" class="single_add_to_cart_button button alt">
                                <span class="Buy">Buy Now</span>
                                <span class="Ins">Download Instantly</span>
                            </button>
                        </p>
                        <div class="BuyImg">

                            <?php do_action('woocommerce_before_add_to_cart_button'); ?>

                            <?php
                            if (!$product->is_sold_individually()) {
                                woocommerce_quantity_input(array(
                                    'min_value' => apply_filters('woocommerce_quantity_input_min', 1, $product),
                                    'max_value' => apply_filters('woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product),
                                    'input_value' => (isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : 1)
                                ));
                            }
                            ?>

                            <input type="hidden" name="add-to-cart"
                                   value="<?php echo esc_attr($product->get_id()); ?>"/>

                            <button type="submit" class="single_add_to_cart_button button alt"><img
                                        src="<?php echo get_template_directory_uri(); ?>/images/bucket_03.png" alt="">
                            </button>

                            <?php do_action('woocommerce_after_add_to_cart_button'); ?>


                        </div>
                        <p><span><?php echo $product->get_price_html(); ?></span></p></form>

                </div>

                <div class="Apx" style="visibility: hidden;">Approximate Conversion Rates:
                    <span>112 USD</span> || <span>85 GBP</span></div>

            </div><!--BuyNow_Button-->
            <?php if ($product->get_sale_price() != '') { ?>
                <div class="persent_Off">
                    <p>Value : <span>$<?php echo $product->get_regular_price(); ?></span></p>
                    <p>You save :
                        <span>$<?php echo($product->get_regular_price() - $product->get_sale_price()); ?></span>
                    </p>
                </div><!--persent_Off-->
            <?php } ?>
            <div class="clr"></div>
        </div><!--pricing-->


        <?php do_action('woocommerce_after_add_to_cart_form'); ?>
    <?php elseif (get_post_meta(get_the_ID(), '_product_type_single', true) == 'yes' && !$product->is_type('variable')): ?>
        <a name="add_to_cart_back"></a>
        <?php $sale_price = $product->get_sale_price();
        $is_one_price = !$disable_rewards_and_offer_base_price && !$sale_price; ?>
        <div class="item-details <?php echo $is_one_price ? 'one-price' : '' ?>">
            <div class="retail-price">
                <?php if (!$is_one_price): ?>
                    <span class="label">retail price</span>
                    <span class="price"><?php echo $product->get_regular_price(); ?></span>
                <?php endif; ?>
            </div><!-- end .retail-price -->

            <?php if (!$disable_rewards_and_offer_base_price): ?>
                <div class="rewards-holder"
                     style="<?php if ($product->get_id() == 508955 || $product->get_id() == 817271 || $product->get_id() == 817615): ?> display:none; <?php endif; ?>">
                    <span class="label">Rewards</span>
                    <?php if (is_user_logged_in()): ?>
                        <div class="info">
                            <span class="amount">$<?php echo (get_user_meta(get_current_user_id(), '_reward_points', true)) ? get_user_meta(get_current_user_id(), '_reward_points', true) : number_format(0, 2, '.', ''); ?></span>
                            <span class="label">reward</span>
                            <span class="icon">?</span>
                        </div>
                        <button>use rewards</button>
                        <div class="popup-reward-input">
                        <span>You have <mark
                                    class="rewardbox"><?php echo (get_user_meta(get_current_user_id(), '_reward_points', true)) ? get_user_meta(get_current_user_id(), '_reward_points', true) : '0.00'; ?></mark> reward</span>
                            <span>How much of your rewards would you like to use?</span>
                            <?php
                            $reward_points = get_user_meta(get_current_user_id(), '_reward_points', true);
                            $base_price = get_post_meta(get_the_ID(), '_minumum_price', true);
                            $current_price = ($product->get_sale_price()) ? $product->get_sale_price() : $product->get_regular_price();
                            if ($reward_points >= $current_price - $base_price) {
                                $can_use = $current_price - $base_price;
                            } elseif ($current_price - $base_price > $reward_points) {
                                $can_use = $reward_points;
                            } elseif ($reward_points == 0) {
                                $can_use = '0.00';
                            }
                            ?>
                            <input type="text" name="use_reward" class="use_reward" value="<?php echo $can_use; ?>">
                            <button class="set-reward">use</button>
                        </div>
                        <?php
                        $args = array(
                            'post_type' => 'page',
                            'meta_query' => array(
                                array(
                                    'key' => 'meta_product_id',
                                    'value' => $product->get_id(),
                                    'compare' => '=',
                                )
                            )
                        );
                        $query = new WP_Query($args);
                        $link = get_permalink($query->posts[0]->ID);
                        global $wp;
                        if (is_product() && home_url($wp->request) . '/' != $link) {
                            echo '<script> window.location="' . $link . '"; </script> ';
                        }
                        ?>
                    <?php else: ?>
                        <div class="info no-login">


                        <span class="label"><a
                                    href="<?php echo get_permalink(get_page_by_path('my-account')); ?>">Login</a></span>
                            <span class="sub-label">to see your rewards</span>
                            <span class="icon">?</span>
                        </div>
                    <?php endif; ?>
                    <!-- Popup with help text -->
                    <div class="popup-help-info">
                        <p>For every dollar you spend with APD, past, present or future, we will give you back at least
                            10%
                            in store rewards credit that will be saved in your rewards wallet.</p>
                    </div>
                </div><!-- end .rewards-holder -->
            <?php endif; ?>

            <div class="action-holder"
                 style="<?php if ($product->get_id() == 404196 || $product->get_id() == 508955 || $product->get_id() == 817271 || $product->get_id() == 817615): ?> width:100%!important; <?php endif; ?>">
                <span class="label">current price</span>
                <span class="price-value price-box"><?php echo wc_price(apd_get_product_price($product)); ?></span>

                <?php $style = '';
                if ($disable_rewards_and_offer_base_price || $product->get_id() == 508955 || $product->get_id() == 817271 || $product->get_id() == 817615) {
                    $style = 'display:none;';
                } ?>
                <span class="sublabel" style="<?php echo $style; ?>">base price
                    <mark class="base_price"><?php echo wc_price(get_post_meta(get_the_ID(), '_minumum_price', true)); ?></mark>
                </span>
                <form class="cart" method="post" enctype='multipart/form-data'>
                    <input type="hidden" name="quantity" value="1"/>
                    <input type="hidden" name="add-to-cart" value="<?php echo $product->get_id(); ?>"/>
                    <input type="hidden" name="use_rewards" class="use_rewards" value="0">
                    <input type="hidden" name="original_price" class="original_price"
                           value="<?php echo apd_get_product_price($product); ?>">
                    <input type="hidden" name="original_rewards" class="original_rewards"
                           value="<?php echo (is_user_logged_in()) ? get_user_meta(get_current_user_id(), '_reward_points', true) : 0; ?>">
                    <button class="single_add_to_cart_button"></button>
					<img class="loading-cart" src="/wp-content/themes/apd/images/loading-cart.gif" style="display:none;">
                </form>
                <style>
                    .price-value::before, .custom-product-page .item-details .action-holder .sublabel mark::before {
                        display: none;
                    }
                </style>
                <script>
                  jQuery('.set-reward').on('click', function () {

                    jQuery('p.alt-new').css('display', 'none');
                  });
                </script>
                <?php $isaac_currency = new Isaac_Custom_Currency();
                $isaac_currency->show_custom_currencies();
                ?>
            </div>
        </div>
    <?php else: ?>
        <a name="add_to_cart_back"></a>

        <?php $sale_price = $product->get_sale_price();
        $is_one_price = !$disable_rewards_and_offer_base_price && !$sale_price; ?>
        <div class="item-details <?php echo $is_one_price ? 'one-price' : '' ?>">
            <div class="retail-price">
                <?php if (!$is_one_price): ?>
                    <span class="label">retail price</span>
                    <span class="price"><?php echo $product->get_regular_price(); ?></span>
                <?php endif; ?>
            </div><!-- end .retail-price -->
            <div class="rewards-holder">
                <span class="label">Rewards</span>
            </div><!-- end .rewards-holder -->
            <div class="action-holder">
                <span class="label">current price</span>
                <span class="price-value price-box"><?php echo wc_price(apd_get_product_price($product)); ?></span>
                <?php $style = '';
                if ($disable_rewards_and_offer_base_price) {
                    $style = 'display:none;';
                } ?>
                <span class="sublabel" style="<?php echo $style; ?>">base price
                    <mark class="base_price"><?php echo get_post_meta(get_the_ID(), '_minumum_price', true); ?></mark>
                </span>
                <form class="cart" method="post" enctype='multipart/form-data'>
                    <p>
                        <button type="submit" class="single_add_to_cart_button button alt">
                            <span class="Buy">Buy Now</span>
                            <span class="Ins">Download Instantly</span>
                        </button>
                    </p>
                    <div class="BuyImg">

                        <?php do_action('woocommerce_before_add_to_cart_button'); ?>

                        <?php if (!$product->is_sold_individually()) {
                            woocommerce_quantity_input(array(
                                'min_value' => apply_filters('woocommerce_quantity_input_min', 1, $product),
                                'max_value' => apply_filters('woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product),
                                'input_value' => (isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : 1)
                            ));
                        } ?>

                        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"/>

                        <button type="submit" class="single_add_to_cart_button button alt">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/bucket_03.png" alt="">
                        </button>
						<img class="loading-cart" src="/wp-content/themes/apd/images/loading-cart.gif" style="display:none;">

                        <?php do_action('woocommerce_after_add_to_cart_button'); ?>

                    </div>
                    <p><span><?php echo $product->get_price_html(); ?></span></p></form>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>