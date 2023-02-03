<?php
/**
 * Template Name: Black Friday 2022
 */

// deal time
$datetime_start = strtotime(carbon_get_the_post_meta('datetime_start'));
$datetime_start += 5 * 60 * 60; // GMT-5
$now = time();
$deal_active_period = 4 * 60 * 60;
$next_deal_time = $datetime_start;
// promote_video
$unlocked_video_url = carbon_get_the_post_meta('unlocked_video');
$unlocked_text = carbon_get_the_post_meta('unlocked_text');
$items = carbon_get_the_post_meta('checkout_product');
function get_youtube_iframe_url($video_url) {
    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $video_url, $matches);
    $youtube_url = 'https://www.youtube.com/embed/' . $matches[0];
    $youtube_url = add_query_arg(array(
        'autoplay' => 1,
        'loop' => 1,
        'mute' => 1,
        // 'showinfo' => 0,
        // 'controls' => 0,
        'rel' => 0,
        'autohide' => 1,
        'modestbranding' => 1,
    ), $youtube_url);
    return $youtube_url;
}

$unlocked_video_iframe_url = get_youtube_iframe_url($unlocked_video_url);

// deals
$deals = carbon_get_the_post_meta('new_deals');

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
    <style>
       
    </style>
    <div class="bf2019-content bf2022-content">
        <div class="bf2019-bg js-bf2019-bg" data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/bg_2022.jpg"></div>
        <div class="bf2019-header">
            <div class="bf2019-header__body-wrapper bf2022-header__body-wrapper">
                <div class="bf2019-content-container text-center">
                    <img class="js-bf2019-img bf2022-header__dice"
                         data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/banner_2022.png"
                         src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                    <div class="bf2022-header__body">
                        <img class="js-bf2019-img bf2019-header__main-image"
                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/black-lightning-deals_2022.png"
                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                        <h2 class="bf2022-header__heading">ALL DEALS UNLOCKED!</h2>
                        <h3 class="bf2022-header__sub-heading">- ALL PRODUCTS IN THE SHOP ARE AT BASE PRICE - <br>NO REWARDS MONEY NEEDED!<br></h3>
    					<h3 class="bf2022-header__bottom-heading" style="margin-top: 15px; font-size: 22px;">QUICKLY FIND BEST DEALS IN THE SHOP. CHECK OUT THE <br><a href="https://audioplugin.deals/apd-shop-cheat-sheet-for-black-friday-2022/" style="text-decoration:underline!important;">APD SHOP CHEAT SHEET - UP TO 97% OFF!</a></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="deal-container">
            <div class="bf2022_deal-section">
                <div class="bf2022-cards-wrapper">
                    <div class="bf2022-cards">
                        <?php $deal_index = 0;
                        $page_ids = array(1626655,1504088,1269648,1637083,1405207,1637450,1637850,1638167,1439560,1638440,1639198,1639208,1639229,1639245,1639266,1390525,1639296,1639323);
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
                            <div class="bf2019-cards__item bf2022-cards__item">
                                <?php if ($is_active): ?>
                                    <a class="bf2019-cards__item__image-wrapper bf2022-card_content" href="<?php echo $page_link; ?>">
                                        <img class="js-bf2019-img bf2019-cards__item__image"
                                             data-src="<?php echo wp_get_attachment_image_url($deal['image'], 'full'); ?>"
                                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                                    </a>
                                    
                                    <div class="bf2019-cards__item__details">
                                        <div class="bf2019-cards__item__deal">Deal <?php echo $deal_index; ?></div>
                                        <h3 class="js-bf2019-img bf2019-cards__item__title">
                                            <span class="bf2019-cards__item__off-text"><?php echo $deal['off_text']; ?></span>
                                            <?php echo $deal['title']; ?>
                                            <span class="bf2019-cards__item__subtitle"><?php echo $deal['subtitle']; ?></span>
                                        </h3>
                                        <div class="bf2019-cards__item__text"><?php echo wpautop($deal['text']); ?></div>
                                        <div class="bf2019-cards__item__price"><?php echo $product ? $product->get_price_html() : ''; ?></div>
                                        <div class="bf2019-cards__item__buttons">
                                            <a class="bf2019-cards__item__btn bf2019-cards__item__btn-more-info" href="<?php echo $page_link; ?>">MORE INFO</a>
                                            <a class="bf2019-cards__item__btn bf2019-cards__item__btn-buy-now" href="<?php echo do_shortcode(sprintf('[add_to_cart_url id="%s"]', $product_id)); ?>">BUY NOW</a>
                                        </div>
                                    </div>
                                    <div class="discount_img">
<img class="js-bf2019-img bf2019-cards__item__image"
                                             data-src="<?php echo wp_get_attachment_image_url($deal['d_image'], 'full'); ?>"
                                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
</div>
                                <?php else: ?>
                                    <div class="bf2022-card_content">
                                    <img class="js-bf2019-img bf2019-cards__item__image bf2022-empty_img"
                                         title="<?php echo htmlentities($unlocked_text); ?>"
                                         data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/card_blank_2022.png"
                                         src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="bf2019-live-deal-countdown">
                    <div class="bf2019-content-container bf2022-content-container">
                        <h2 class="bf2020-live-deal-countdown__headline">PROMO ENDS IN...</h2>
                        <div class="bf2019-live-deal-countdown__body">
                            <div class="bf2019-live-deal-countdown__body-inner">
                                <img class="js-bf2019-img"
                                     data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/hourglass.png"
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
                                        
                                <div class="bf2019-live-deal-countdown__time-wrapper">
                                    <div class="bf2019-live-deal-countdown__time bf2020-live-deal-countdown__time" data-next_deal="<?php echo $expired_time;  ?>"></div>
                                    <div class="bf2019-live-deal-countdown__text"><span>Hours</span><span>Minutes</span><span>Seconds</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-bg">
                        <img class="js-bf2019-img"
                                     data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/count_bg.png"
                                     src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                    </div>
                </div>
            </div>
        </div>
        <div class="bf2019-live-deal">
            <div class="bf2019-live-deal__headline-wrapper">
                <div class="bf2019-content-container">
                    <h2 class="bf2019-live-deal__headline bf2022-live-deal__headline">LIVE DEAL COVERAGE</h2>
                </div>
            </div>
            <div class="bf2019-live-deal__slider js-bf2019-bg" data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/live-deals/slider-bg.jpg">
                <div class="bf2019-content-container">
                    <div class="bf2019-live-deal__slider__video">
                        <?php $deal_index = 0;
                        $deal_index++;
                        $is_active = $now >= $datetime_start + $deal_active_period * ($deal_index - 1);
                        
                        ?>
                        <?php if($is_active){ ?>
                        <iframe width="100%"
                                height="350"
                                src="<?php echo get_youtube_iframe_url($deals[0]['promote_video']); ?>"
                                frameborder="0"
                                data-unlocked_video_url="<?php echo htmlentities($unlocked_video_iframe_url); ?>"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        ></iframe>
                        <?php }else{?>
                        <iframe width="100%"
                                height="350"
                                src="<?php echo ($unlocked_video_iframe_url); ?>"
                                frameborder="0"
                                data-unlocked_video_url="<?php echo htmlentities($unlocked_video_iframe_url); ?>"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        ></iframe>
                        <?php }?>
                    </div>
                </div>
                <div class="bf2019-live-deal__slider__container" data-active_slide="<?php echo ($active_slide - 1); ?>">
                    <?php $deal_index = 0;
                    foreach ($deals as $deal):
                        $deal_index++;
                        $product_id = $deal['product_id'][0]['id'];
                        $product = wc_get_product($product_id);
                        $is_active = $now >= $datetime_start + $deal_active_period * ($deal_index - 1);
                        $video_url = $unlocked_video_iframe_url;
                        if ($is_active) {
                            $video_url = $deal['promote_video'] ? get_youtube_iframe_url($deal['promote_video']) : $unlocked_video_iframe_url;
                        }
                        else{
                            $video_url = $unlocked_video_iframe_url;
                        }
                        ?>
                        <div class="bf2019-live-deal__item-wrapper">
                            <div class="bf2019-content-container">
                                <div class="bf2019-live-deal__item" data-video_url="<?php echo $video_url; ?>">
                                    <?php if ($is_active): ?>
                                        <h3 class="bf2019-live-deal__item__title"><?php echo $deal['title']; ?></h3>
                                        <div class="bf2022-live-deal__item__banner">Deal <?php echo $deal_index; ?></div>
                                        <div class="bf2019-live-deal__item__image-details-wrapper">
                                            <div class="bf2019-live-deal__item__image-wrapper">
                                                <img class="js-bf2019-img bf2019-live-deal__item__image"
                                                     data-src="<?php echo wp_get_attachment_image_url($deal['image_slider'], 'full'); ?>"
                                                     src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                                            </div>
                                            <div class="bf2019-live-deal__item__details">
                                                <div class="bf2019-live-deal__item__details__inner">
                                                    
                                                    <div>
                                                        <div class="bf2019-live-deal__item__price"><?php echo $product ? $product->get_price_html() : ''; ?></div>
                                                        <a class="bf2019-live-deal__item__btn bf2019-live-deal__item__btn-buy-now"
                                                           href="<?php echo do_shortcode(sprintf('[add_to_cart_url id="%s"]', $product_id)); ?>">BUY NOW</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>

                                        <div class="bf2019-live-deal__item__details bf2019-live-deal__item__details--locked">
                                            <div>
                                                <img class="js-bf2019-img bf2019-live-deal__item__padlock"
                                                     style="display: block;margin-left: auto;margin-right: auto;margin-bottom: 25px;max-width: 100px;"
                                                     data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/locked.png"
                                                     src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                                                <?php echo $unlocked_text; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="bf2022-product-deal">
            <div class="product-deal-title">
                <h2 class="bf2019-live-deal-countdown__headline">- DOOR BUSTER DEALS! -</h2>
                <h2 class="bf2020-product_title">Check out these deals ending soon!</h2>
            </div>
            
            <div class="product-deal-section">

                <?php $item_index = 0;
                $page_ids = array(1614834, 1619727, 1619822, 1619910, 1620263, 1620129);
                // $product_id = $deal['product_id'][0]['id'];
                foreach ($items as $item) {
                    $item_index++;
                    $product_id = $item['product_id'][0]['id'];

                    // $page_link = get_permalink($product_id);
                    $page_link = get_permalink($page_ids[$item_index - 1]);
                ?>
                <div class="product_item">
                    <a href="<?php echo $page_link ; ?>">
                    <img class="bf2020-product-img"
                     data-src="<?php echo wp_get_attachment_image_url($item['image'], 'full'); ?>"
                     src="<?php echo wp_get_attachment_image_url($item['image'], 'full'); ?>">
                    </a>
                </div>
                 <?php }?>

            </div>
            <div class="bottom-deal-section">
                <?php $bottom_img = carbon_get_the_post_meta('bottom_img'); 
                      $bottom_id = 1616922; 
                      $bottom_url = get_permalink( $bottom_id );
                ?>
                <a href="<?php echo $bottom_url; ?>">
                    <img class="bf2020-product-img"
                     data-src="<?php echo wp_get_attachment_image_url($bottom_img, 'full'); ?>"
                     src="<?php echo wp_get_attachment_image_url($bottom_img, 'full'); ?>">
                </a>
            </div>
        </div>
	<h3 class="bf2022-header__bottom-heading">- ALL PRODUCTS IN THE SHOP ARE AT BASE PRICE - <br>NO REWARDS MONEY NEEDED!</h3>
    <h3 class="bf2022-header__bottom-heading">QUICKLY FIND BEST DEALS IN THE SHOP. CHECK OUT THE <br><a href="https://audioplugin.deals/apd-shop-cheat-sheet-for-black-friday-2022/" style="text-decoration:underline!important;">APD SHOP CHEAT SHEET - UP TO 97% OFF!</a></h3>
        <div class="bf2022-shopping-info">
            <div class="info_item brand_info">
                <img src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/shop_brands.png">
                <div class="info_item_des">
                    <div class="info_item_des">
                        <div class="left">
                            <?php echo apply_filters( 'the_content', carbon_get_the_post_meta('shop_brand_left') ); ?>
                        </div>
                        <div class="right">
                            <?php echo apply_filters( 'the_content', carbon_get_the_post_meta('shop_brand_right') ); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="info_item trending_info">
                <img src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/trending_now.png">
                <div class="info_item_des">
                    <div class="left">
                        <?php echo apply_filters( 'the_content', carbon_get_the_post_meta('trending_now_left') ); ?>
                    </div>
                    <div class="right">
                        <?php echo apply_filters( 'the_content', carbon_get_the_post_meta('trending_now_right') ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php get_footer();