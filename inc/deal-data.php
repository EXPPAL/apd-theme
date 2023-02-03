<?php
echo '<script src="' . get_template_directory_uri() . '/js/header_dropdowm.js" type="text/javascript" async></script>';

$page = get_page_by_path('new-front-page');
$BIG_DEAL = array(
    'new_price' => get_field('bd_product_new_price', $page->ID),
    'day_end' => get_field('bd_product_day_end', $page->ID),
    'percent' => get_field('bd_percent', $page->ID),
    'product' => get_field('big_deal_product', $page->ID)
);
$DEAL = array(
    'new_price' => get_field('d_product_new_price', $page->ID),
    'day_end' => get_field('d_product_day_end', $page->ID),
    'percent' => get_field('d_percent', $page->ID),
    'product' => get_field('d_product', $page->ID)
);
$big_deal_product_id = $BIG_DEAL['product']->ID;
$bd_thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
$bd_post_thumbnail_id = get_post_thumbnail_id($big_deal_product_id);
$bd_full_size_image = wp_get_attachment_image_src($bd_post_thumbnail_id, $bd_thumbnail_size);
$bd_company_name = get_field('lto_company_name', $big_deal_product_id);
$bd_big_deal_end_day = get_field('bd_product_day_end', $page->ID);
$bd_big_deal_end_time = get_field('bd_product_time_end', $page->ID);

$deal_product_id = $DEAL['product']->ID;
$d_thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
$d_post_thumbnail_id = get_post_thumbnail_id($deal_product_id);
$d_full_size_image = wp_get_attachment_image_src($d_post_thumbnail_id, $d_thumbnail_size);
$d_company_name = get_field('lto_company_name', $deal_product_id);
$d_big_deal_end_day = get_field('d_product_day_end', $page->ID);
$d_big_deal_end_time = get_field('d_product_time_end', $page->ID);

echo '<script>

            var BIG_DEAL = {
                "_colorBackground": "",
                "boxTitle"       : "The Big Deal",
                "percent"        : "' . $BIG_DEAL["percent"] . '",
                "productIMG"     : "' . $bd_full_size_image[0] . '", 
                "price"          : "' . $BIG_DEAL["new_price"] . '",
                "comany"         : "' . $bd_company_name . '",
                "product"        : "' . $BIG_DEAL['product']->post_title . '",
                "dat_end"        : "' . $bd_big_deal_end_day . '"
            }

            var DEAL = {
                "_colorBackground": "",
                "boxTitle"       : "The Deal",
                "percent"        : "' . $DEAL["percent"] . '",
                "productIMG"     : "' . $d_full_size_image[0] . '", 
                "price"          : "' . $DEAL["new_price"] . '",
                "comany"         : "' . $d_company_name . '",
                "product"        : "' . $DEAL['product']->post_title . '",
                "dat_end"        : "' . $d_big_deal_end_day . '"
            }';
echo '</script>';

echo '<div id="big_deal__caounter" style="display: none;">';
echo do_shortcode('[jcountdown timetext="' . $bd_big_deal_end_day . ' ' . $bd_big_deal_end_time . '" 
                                    timezone="-6" 
                                    style="slide" 
                                    color="black" 
                                    width="100" 
                                    textgroupspace="4" 
                                    textspace="0" 
                                    reflection="false" 
                                    reflectionopacity="10" 
                                    reflectionblur="0" 
                                    daytextnumber="2" 
                                    displayday="true" 
                                    displayhour="true" 
                                    displayminute="true" 
                                    displaysecond="true" 
                                    displaylabel="false" 
                                    onfinishredirecturl=""]' . $bd_big_deal_end_day . ' ' . $bd_big_deal_end_time . '[/jcountdown]');
echo '</div>';