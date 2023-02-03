<?php
/**
 * Template Name: Christmas
 */

// deal time
$datetime_start = strtotime(carbon_get_the_post_meta('datetime_start'));
$datetime_start += 5 * 60 * 60; // GMT-5
$now = time();
$deal_active_period = 24 * 60 * 60;
$next_deal_time = $datetime_start;
// promote_video
$unlocked_video_url = carbon_get_the_post_meta('unlocked_video');
$unlocked_text = carbon_get_the_post_meta('unlocked_text');
$check_items = carbon_get_the_post_meta('checkout_products');
function get_youtube_iframe_url($video_url) {
    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $video_url, $matches);
    $youtube_url = 'https://www.youtube.com/embed/' . $matches[0];
    $youtube_url = add_query_arg(array(
        'autoplay' => 0,
        'loop' => 0,
        'mute' => 1,
        // 'showinfo' => 0,
        // 'controls' => 0,
        'rel' => 0,
        'autohide' => 1,
        'modestbranding' => 1,
    ), $youtube_url);
    return $youtube_url;
}

// deals
$deals = carbon_get_the_post_meta('new_deal');

$active_slide = 0;
foreach ($deals as $deal_index => $deal) {

    $is_active = $now >= $datetime_start + $deal_active_period * $deal_index;
    if ($is_active) {
        $active_slide = $deal_index;
        $active_slide ++;
    }
}

// checkout_product


//
get_header(); ?>
    <div class="snowflakes" aria-hidden="true">
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
      <div class="snowflake">
      
      </div>
    </div>
    <div class="bf2019-content christmas-content">
        <div class="christmas-bg js-bf2019-bg bf2019-bg" data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/chris_bg.jpg"></div>
        <div class="christmas-top-content">
            <div class="christmas-logo text-center">
                <?php $logo_img = carbon_get_the_post_meta('logo_img'); ?>
                <img 
                     data-src="<?php echo wp_get_attachment_image_url($logo_img, 'full'); ?>"
                     src="<?php echo wp_get_attachment_image_url($logo_img, 'full'); ?>">
            </div>
            <div class="christmas-deal_section">
                <div class="christmas-deal_title text-center">
                    A New Deal Reveals Every Day!
                </div>
                <div class="christmas-deal_main">
                    <div class="christmas-cards-wrapper">
                        <div class="christmas-live-deal-countdown">
                            <div class="christmas-content-container">
                                <h2 class="christmas-live-deal-countdown__headline">Next Deal Reveals In</h2>
                                <div class="christmas-live-deal-countdown__body">
                                    <div class="christmas-live-deal-countdown__body-inner">
                                        <img class="js-bf2019-img"
                                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/y_hourglass.png"
                                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">

                                            <?php 
                                                $deal_index = 0;
                                                $expired_time = (new DateTime('2022-11-28-21'))->getTimestamp() + 13 * 60 * 60;
                                                foreach ($deals as $deal):
                                                    $deal_index++;
                                                    $product_id = $deal['product_id'][0]['id'];
                                                    $product = wc_get_product($product_id);
                                                    $is_active = $now >= $datetime_start + $deal_active_period * ($deal_index - 1);
                                                    if ($datetime_start + $deal_active_period * ($deal_index - 1) < $now && $now <= $datetime_start + $deal_active_period * $deal_index) {
                                                        $next_deal_time = $datetime_start + $deal_active_period * $deal_index;
                                                    }
                                                    $page_link = get_permalink($next_deal_time);
                                                    ?>
                                                <?php endforeach; ?>
                                                
                                        <div class="christmas-live-deal-countdown__time-wrapper">
                                            <div class="bf2019-live-deal-countdown__time" data-next_deal="<?php echo $next_deal_time;  ?>"></div>
                                            <div class="christmas-live-deal-countdown__text"><span>Hours</span><span>Minutes</span><span>Seconds</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="christmas-cards">
                            <?php $deal_index = 0;
                            $page_ids = array(1662417,1557706,1662445,1662466,1468900,1514773,1294470,1438233,1662552,1559447,1662578,1662583,1662624);
                            foreach ($deals as $deal):
                                $deal_index++;
                                $product_id = $deal['product_id'][0]['id'];
                                $product = wc_get_product($product_id);
                                $is_active = $now >= $datetime_start + $deal_active_period * ($deal_index - 1);
                                if ($datetime_start + $deal_active_period * ($deal_index - 1) < $now && $now <= $datetime_start + $deal_active_period * $deal_index) {
                                    $next_deal_time = $datetime_start + $deal_active_period * $deal_index;
                                }
                                $page_link = get_permalink($page_ids[$deal_index - 1]);
                                ?>
                                <div class="christmas-cards__item card_item<?php echo $deal_index; ?>">
                                    <?php if ($is_active): ?>
                                        <a class="christmas-cards__item__image-wrapper" href="<?php echo $page_link; ?>">
                                            <img class="js-christmas-img"
                                                 data-src="<?php echo wp_get_attachment_image_url($deal['image'], 'full'); ?>"
                                                 src="<?php echo wp_get_attachment_image_url($deal['image'], 'full'); ?>">
                                        </a>
                                        
                                        <div class="christmas-cards__item__details">
                                            <img class="js-christmas-img"
                                                 data-src="<?php echo wp_get_attachment_image_url($deal['h_image'], 'full'); ?>"
                                                 src="<?php echo wp_get_attachment_image_url($deal['h_image'], 'full'); ?>">
                                            <div class="christmas-cards__item__price"><?php echo $product ? $product->get_price_html() : ''; ?></div>
                                            <div class="christmas-cards__item__buttons">
                                                <a class="christmas-cards__item__btn-more-info" href="<?php echo $page_link; ?>">LEARN MORE</a>
                                                <a class="christmas-cards__item__btn-buy-now" href="<?php echo do_shortcode(sprintf('[add_to_cart_url id="%s"]', $product_id)); ?>">BUY NOW</a>
                                            </div>
                                        </div>
                                        <div class="discount_img">
                                            <img class="chris-discount"
                                                 data-src="<?php echo wp_get_attachment_image_url($deal['d_image'], 'full'); ?>"
                                                 src="<?php echo wp_get_attachment_image_url($deal['d_image'], 'full'); ?>">
                                        </div>
                                    <?php else: ?>
                                        <div class="christmas-card_content">
                                            <div class="empty_img">
                                            <img class="chris-empty"
                                                 data-src="<?php echo wp_get_attachment_image_url($deal['e_image'], 'full'); ?>"
                                                 src="<?php echo wp_get_attachment_image_url($deal['e_image'], 'full'); ?>">
                                            </div>
                                            
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            <div class="christmas-cards__item sub_logo">
                                <?php $sub_img = carbon_get_the_post_meta('sub_logo'); ?>
                                <img class="christmas-sub_logo"
                                            data-src="<?php echo wp_get_attachment_image_url($sub_img, 'full'); ?>"
                                            src="<?php echo wp_get_attachment_image_url($sub_img, 'full'); ?>">
                            </div>
                        </div>
                        <div class="christmas-sock">
                            <?php $sock_img = carbon_get_the_post_meta('sock_img'); ?>
                            <img class="christmas-sock_img"
                                data-src="<?php echo wp_get_attachment_image_url($sock_img, 'full'); ?>"
                                src="<?php echo wp_get_attachment_image_url($sock_img, 'full'); ?>">
                        </div>
                        <div class="christmas-bell">
                            <?php $bell_img = carbon_get_the_post_meta('bell_img'); ?>
                            <img class="christmas-bell_img"
                                data-src="<?php echo wp_get_attachment_image_url($bell_img, 'full'); ?>"
                                src="<?php echo wp_get_attachment_image_url($bell_img, 'full'); ?>">
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="christmas-vip">
            <div class="vip-bg js-bf2019-bg bf2019-bg" data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/club_bg.png"></div>
            <div class="vip-content">
                <div class="vip-logo">
                    <?php $vip_logo = carbon_get_the_post_meta('vip_logo'); ?>
                    <img class="christmas-vip_logo"
                     data-src="<?php echo wp_get_attachment_image_url($vip_logo, 'full'); ?>"
                     src="<?php echo wp_get_attachment_image_url($vip_logo, 'full'); ?>">
                </div>
                <div class="vip-title_url">
                    <?php 
                        $vip_title = carbon_get_the_post_meta('vip_title'); 
                        $product_url = carbon_get_the_post_meta('vip_product');
                    ?>
                    <div class="vip-title">
                        <img class="christmas-vip_title"
                        data-src="<?php echo wp_get_attachment_image_url($vip_title, 'full'); ?>"
                        src="<?php echo wp_get_attachment_image_url($vip_title, 'full'); ?>">
                    </div>
                    <div class="vip-url">
                        <a href="<?php echo $product_url; ?>" class="vip_btn">Join Now</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="christmas-checkout_product text-center">
            <div class="christmas-product-deal">
                <div class="product-deal-title">
                    <h2 class="christmas-live-deal-countdown__headline">- Top Picks -</h2>
                    <h2 class="christmas-product_title">Check out these additional Stuffing stocker deals ending soon!</h2>
                </div>
                
                <div class="product-deal-section">

                    <?php $item_index = 0;
                    $page_ids = array(1639675, 1661331, 1661374, 1662098, 152326, 1470419);
                    // $product_id = $deal['product_id'][0]['id'];
                    foreach ($check_items as $item) {
                        $item_index++;
                        $page_link = $item['product_id'];

                    ?>
                    <div class="product_item">
                        <img class="christmas-product_d_img"
                                data-src="<?php echo wp_get_attachment_image_url($item['d_image'], 'full'); ?>"
                                src="<?php echo wp_get_attachment_image_url($item['d_image'], 'full'); ?>">
                        <a href="<?php echo $page_link ; ?>">
                            <img class="christmas-product_img"
                                data-src="<?php echo wp_get_attachment_image_url($item['image'], 'full'); ?>"
                                src="<?php echo wp_get_attachment_image_url($item['image'], 'full'); ?>">
                            
                        </a>
                    </div>
                     <?php }?>

                </div>
                <div class="video-section">
                    <?php $video = carbon_get_the_post_meta('video');
                    ?>
                    <iframe width="100%"
                                height="350"
                                src="<?php echo get_youtube_iframe_url($video); ?>"
                                frameborder="0"
                                allow="accelerometer; autoplay; loop; encrypted-media; gyroscope; picture-in-picture"
                        ></iframe>
                </div>
            </div>
        </div>
    </div>
<?php get_footer();