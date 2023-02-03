<?php
/**
 * Variable product add to cart
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.5
 *
 * Modified to use radio buttons instead of dropdowns
 * @author 8manos
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

$attribute_keys = array_keys($attributes);

do_action('woocommerce_before_add_to_cart_form'); ?>
<form class="variations_form cart" method="post" enctype='multipart/form-data'
      data-product_id="<?php echo absint($product->get_id()); ?>"
      data-product_variations="<?php echo htmlspecialchars(wp_json_encode($available_variations)) ?>">
    <?php do_action('woocommerce_before_variations_form'); ?>
    <a name="add_to_cart_back"></a>
    <div class="item-details one-price">
        <?php if (empty($available_variations) && false !== $available_variations) : ?>
            <p class="stock out-of-stock"><?php _e('This product is currently out of stock and unavailable.', 'woocommerce'); ?></p>
        <?php else : ?>
            <div class="retail-price"></div><!-- end .retail-price -->
            <div class="rewards-holder">
                <span class="label">Donate</span>
                <table class="variations" cellspacing="0">
                    <tbody>
                    <?php foreach ($attributes as $name => $options) : ?>
                        <tr class="attribute-<?php echo sanitize_title($name); ?>">
                            <td class="label"><label
                                        for="<?php echo sanitize_title($name); ?>"><?php echo wc_attribute_label($name); ?></label>
                            </td>
                            <?php
                            $sanitized_name = sanitize_title($name);
                            if (isset($_REQUEST['attribute_' . $sanitized_name])) {
                                $checked_value = $_REQUEST['attribute_' . $sanitized_name];
                            } elseif (isset($selected_attributes[$sanitized_name])) {
                                $checked_value = $selected_attributes[$sanitized_name];
                            } else {
                                $checked_value = '';
                            }
                            ?>
                            <td class="value">
                            	<?php
								if (sanitize_title($name) == 'select-donation') { 
								$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) ) : $product->get_variation_default_attribute( $name );
								wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $name, 'product' => $product, 'selected' => $selected ) );
								//echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) : '';
								} else {
                                if (!empty($options)) {
                                    if (taxonomy_exists($name)) {
                                        // Get terms if this is a taxonomy - ordered. We need the names too.
                                        $terms = wc_get_product_terms($product->get_id(), $name, array('fields' => 'all'));

                                        foreach ($terms as $term) {
                                            if (!in_array($term->slug, $options)) {
                                                continue;
                                            }
                                            print_attribute_radio($checked_value, $term->slug, $term->name, $sanitized_name);
                                        }
                                    } else {
                                        foreach ($options as $option) {
                                            print_attribute_radio($checked_value, $option, $option, $sanitized_name);
                                        }
                                    }
                                }
								}
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div><!-- end .rewards-holder -->
            <div class="action-holder">
                <?php do_action('woocommerce_before_add_to_cart_button'); ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($){
                        $("input:radio[name^='attribute']").on("click",function(){
                            $("span.msg").css('display','none');
                        });
                    });
					jQuery(document).ready(function($){
                        $("select[name^='attribute']").on("change",function(){
                            $("span.msg").css('display','none');
                        });
                    });
                </script>
                <span class="label">selected donation</span>
                <span class="msg">None</span>
                <script type="text/template" id="tmpl-variation-template">
                <span class="price-value price-box">{{{ data.variation.display_regular_price }}}</span>
                </script>


                <div class="single_variation_wrap">
                    <?php
                    do_action('woocommerce_before_single_variation');
                    do_action('woocommerce_single_variation');
                    do_action('woocommerce_after_single_variation');
                    ?>
                </div>

                <?php do_action('woocommerce_after_add_to_cart_button'); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php do_action('woocommerce_after_variations_form'); ?>
</form>
<?php do_action('woocommerce_after_add_to_cart_form'); ?>