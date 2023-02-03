<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
    <td class="product-name">
		<?php
		$is_visible        = $product && $product->is_visible();
		$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

		echo $item['name'];
		echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item );

		?>
    </td>
    <td class="product-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); ?>
    </td>
</tr>
<tr>
    <td colspan="2">
    <?php 
       if ( ! isset( $_GET['key'] ) ) {
       
        do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );
		if ( get_post_meta( $item['product_id'], '_product_type_single', true ) != 'yes' && get_post_meta( $item['product_id'], '_product_big_deal', true ) != 'yes' ) {
			$email_template_content = show_woocommerce_order_success_email_template( $order->get_id() );
			$woo                    = get_option( 'woocommerce_wc_apd_order_completed_settings' );
			$mail_text              = $woo['main_text'];
			if ( $email_template_content ) {
				echo '<div class="order_success_email_template">' . $email_template_content . '</div>';
			} else {
				$item_meta   = $item->get_meta_data();
				$item_result = array();

				foreach ( $item_meta as $meta_item ) {
					if ( ( $meta_item->key == 'Coupon Code(s)' ) or ( $meta_item->key == 'License Code(s)' ) ) {
						$codes = $meta_item->value;

						if ( is_array( $codes ) ) {
							$item_result['coupon_code'] = implode( ', ', $codes );
						} else {
							$item_result['coupon_code'] = $codes;
						}
					}
				}
				$html = "<div>[email_main_text]</div>";
				$html = str_replace( '[email_main_text]', $mail_text, $html );
				$html = str_replace( '{customer_name}', $order->billing_first_name, $html );
				$html = str_replace( '{coupon_code}', strip_tags( $item_result['coupon_code'] ), $html );
				$html = str_replace( '{gift_code}', $item['gift_code'], $html );
				$html = str_replace( '{company_name}', get_post_meta( $item['product_id'], 'company_name', true ), $html );
				$html = str_replace( '{redeem_link}', get_post_meta( $item['product_id'], 'url', true ), $html );
				if($order->get_status()=='completed'){
					echo '<div class="order_success_email_template">' . $html . '</div>';
                }
			}

		} elseif(get_post_meta( $item['product_id'], '_product_big_deal', true ) == 'yes') {
            $email_template_content = get_post_meta($order->get_id(), 'email_template_content_bigdeal', true);
            $woo                    = get_option( 'woocommerce_wc_apd_bigdeal_order_completed_settings' );
            $mail_text              = $woo['main_text'];
            if ( $email_template_content ) {
                echo '<div class="order_success_email_template">' . $email_template_content . '</div>';
            } else {
                $item_meta   = $item->get_meta_data();
                $item_result = array();

                foreach ( $item_meta as $meta_item ) {
                    if ( ( $meta_item->key == 'Coupon Code(s)' ) or ( $meta_item->key == 'License Code(s)' ) ) {
                        $codes = $meta_item->value;

                        if ( is_array( $codes ) ) {
                            $item_result['coupon_code'] = implode( ', ', $codes );
                        } else {
                            $item_result['coupon_code'] = $codes;
                        }
                    }
                }
                $html = "<div>[email_main_text]</div>";
                $html = str_replace( '[email_main_text]', $mail_text, $html );
                $html = str_replace( '{customer_name}', $order->billing_first_name, $html );
                $html = str_replace( '{coupon_code}', strip_tags( $item_result['coupon_code'] ), $html );
                $html = str_replace( '{gift_code}', $item['gift_code'], $html );
                $html = str_replace( '{company_name}', get_post_meta( $item['product_id'], 'company_name', true ), $html );
                $html = str_replace( '{redeem_link}', get_post_meta( $item['product_id'], 'url', true ), $html );
                if($order->get_status()=='completed'){
                    echo '<div class="order_success_email_template">' . $html . '</div>';
                }
            }
        } else {
			$email_template_content = apd_shop_order_success_email_template( $order->get_id(), $item['product_id'] );
			if ( $email_template_content ) {
				echo '<div class="order_success_email_template">' . $email_template_content . '</div>';
			} else {
				$item_meta   = $item->get_meta_data();
				$item_result = array();
				foreach ( $item_meta as $meta_item ) {
					if ( ( $meta_item->key == 'Coupon Code(s)' ) or ( $meta_item->key == 'License Code(s)' ) ) {
						$codes = $meta_item->value;

						if ( is_array( $codes ) ) {
							$item_result['coupon_code'] = implode( ', ', $codes );
						} else {
							$item_result['coupon_code'] = $codes;
						}
					}
				}
				$email_heading  = get_post_meta( $item['product_id'], 'apd_product_email_heading', true );
				$email_template = get_post_meta( $item['product_id'], 'apd_product_email_template', true );
				$single_email   = str_replace( '{product_name}', $item['name'], $email_template );
				$single_email   = str_replace( '{customer_name}', $order->get_billing_first_name(), $single_email );
				$single_email   = str_replace( '{coupon_code}', strip_tags( $item_result['coupon_code'] ), $single_email );
				$single_email   = str_replace( '{gift_code}', $item['gift_code'], $single_email );
				$single_email   = str_replace( '{developer_name}', $item['developer_name'], $single_email );
				$single_email   = str_replace( '{developer_email}', $item['developer_email'], $single_email );
				$single_email   = str_replace( '{url}', get_post_meta( $item['product_id'], '_redeem_link', true ), $single_email );
				if($order->get_status()=='completed'){
					echo '<div class="order_success_email_template">' . $email_heading . '</div>';
					echo '<div class="order_success_email_template">' . $single_email . '</div>';
				}
			}
		}

		$red_link   = get_field( 'url', $item['product_id'] );
		$product_ID = $item['product_id'];
		if ( $product_ID == 2194 ) {
			_e( '<br/><strong>The files have been compressed into .RAR format. Once downloaded, you will need an unrar applicaition to decompress the downloded files. For Mac we suggest UnrarX, for PC we suggest WinRar</strong>' );
		}
		if ( $red_link ) {
			?>
            <strong><br/>The message above has also been forwarded to you by email.</strong>
			<?php
		}
		$order->display_item_downloads( $item );

		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
  		}
        else {
		?>
        <p>Thank you for completing your order!</p> 
        Please check your email address for download instructions. 
        Also check your spam folder just in case the email containing those instructions end up there.
        <p>In case you need assistance, please send an email to <a href="mailto:support@audioplugin.deals">support@audioplugin.deals</a></p>
    </td>
    <?php     
    }
    ?>
</tr>
<?php if ( $show_purchase_note && $purchase_note ) : ?>
    <tr class="product-purchase-note">
        <td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
    </tr>
<?php endif; ?>