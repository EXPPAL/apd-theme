<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php post_class(); ?>>
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * woocommerce_before_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * woocommerce_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	do_action( 'woocommerce_after_shop_loop_item' );

	?>
    <form class="cart" method="post" enctype='multipart/form-data'>
        <p>You have: <?php echo get_user_meta( wp_get_current_user()->ID, '_reward_points', true ); ?> reward points</p>
        <p>You can use
            max: <?php echo round( $product->get_sale_price() - get_post_meta( $product->id, '_minumum_price', true ) ) ?>
            reward points</p>
        <p>Apply reward points:
            <input type="number" id="use_rewards" name="use_rewards" min="0" value="0"
                   max="<?php echo round( $product->get_sale_price() - get_post_meta( $product->id, '_minumum_price', true ) ) ?>">
        </p>
        <input type="hidden" name="quantity" value="1"/>
        <input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>"/>
        <button type="submit" class="single_add_to_cart_button button alt"><img
                    src="http://apd.priv/wp-content/themes/apd/images/bucket_03.png" alt=""></button>
    </form>
</li>