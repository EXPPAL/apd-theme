<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
 * @version 3.5.1
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product;
$args = array(
    'post_type' => 'page',
    'meta_query' => array(
        array(
            'key' => 'meta_product_id',
            'value' => $post->ID,
            'compare' => '=',
        )
    )
);
$query = new WP_Query($args);


$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');


if ($query->have_posts()):
    $post_id = $query->posts[0]->ID;
else:
    $post_id = $post->ID;
endif;
$post_thumbnail_id = get_post_thumbnail_id($post_id);
$full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
$placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes = apply_filters('woocommerce_single_product_image_gallery_classes', array(
    'woocommerce-product-gallery',
    'woocommerce-product-gallery--' . $placeholder,
    'woocommerce-product-gallery--columns-' . absint($columns),
    'images',
));
if (!$post_thumbnail_id) {
    $post_id = $post->ID;
}
?>
<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>"
     data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <figure class="woocommerce-product-gallery__wrapper">
        <?php
        $attributes = array(
            'title' => get_post_field('post_title', $post_thumbnail_id),
            'data-caption' => get_post_field('post_excerpt', $post_thumbnail_id),
            'data-src' => $full_size_image[0],
            'data-large_image' => $full_size_image[0],
            'data-large_image_width' => $full_size_image[1],
            'data-large_image_height' => $full_size_image[2],
            'itemprop' => 'image',
        );

        if (has_post_thumbnail()) {
            $html = '<div data-thumb="' . get_the_post_thumbnail_url($post_id, 'shop_thumbnail') . '" class="woocommerce-product-gallery__image"><a href="' . esc_url($full_size_image[0]) . '">';
            $html .= get_the_post_thumbnail($post_id, 'shop_single', $attributes);
            $html .= '</a></div>';
        } else {
            $html = '<div class="woocommerce-product-gallery__image--placeholder">';
            $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce'));
            $html .= '</div>';
        }

        echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id($post_id));

        do_action('woocommerce_product_thumbnails');
        ?>
    </figure>
</div>