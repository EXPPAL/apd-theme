<?php
/**
 * Template Name: Black Friday 2021
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
$deals = carbon_get_the_post_meta('deals');

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
    <style type="text/css">
        .bf2019-live-deal-countdown {
            padding-top: 0;
        }
        h3.bf2019-header__sub-heading {
            font-size: 30px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .bf2020-live-deal-countdown__headline {
            font-weight: 400;
            font-size: 30px;
            color: #9d0226;
            text-shadow: -4px 1px 6.72px #030303;
            margin-bottom: 15px!important;
        }

        .bf2020-container {
            width: 100%;
            max-width: 1400px;
            padding: 0 20px;
            padding-bottom: 70px;
        }
        .bf2020-container .bf2019-cards .bf2020-cards__item{
            width: 100%;
            max-width: 16.6667%;
            padding: 0 15px;
        }
        .bf2020-product-deal {
            margin-top: 150px;
        }
        .product-deal-title {
            text-align: center;
            margin-bottom: 15px;
        }
        .product-deal-title h2.bf2019-live-deal-countdown__headline {
            text-shadow: -7px 5px 6.72px #030303;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .bf2020-product_title {
            font-weight: 500;
            font-size: 32px;
            line-height: 1;
            color: #c0c0c0;
        }
        .product-deal-section {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 50px;
            flex-wrap:  wrap;
            justify-content: center;
            padding-bottom: 150px;
        }
        .product_item {
            width: 33.333%;
            padding: 0 15px;
            text-align: center;
            margin-top:  50px;
        }
        .product_item:nth-child(7) {
            width: 100%;
        }
        .bf2019-live-deal-countdown__time.bf2020-live-deal-countdown__time {
            font-size: 50px;
        }
        .bf2020-shopping-info {
            display: flex;
            max-width: 1300px;
            padding: 0 15px;
            margin: 0 auto;
            padding-bottom: 250px;
        }
        .info_item img {
            margin-bottom: 30px;
        }
        .trending_info {
            padding-left: 30px;
            width: 50%;
        }
        .brand_info {
            padding-right: 30px;
            width: 50%;
        }
        .info_item_des {
            font-family: 'Raleway-Medium';
            color: #fff;
            padding: 0 40px;
            display: flex;
            font-size: 16px;
        }
        .info_item_des a{
            font-size: 16px;
            font-family: 'Raleway-Medium';
            color: #fff;
        }
        .info_item_des .left{
            padding-right: 15px;
        }
        .info_item_des .right{
            padding-left: 15px;
        }
        .bf2019-live-deal__item__image-details-wrapper {
			align-items: center;
		}
        .bf2019-live-deal__item__details {
        	height: 100%;
            display: flex;
            align-items: end;
            margin-bottom: 70px;
            justify-content: center;
        }
        @media(max-width: 1200px){
            .bf2020-container .bf2019-cards .bf2020-cards__item{
                max-width: 25%;
            }
            .product-deal-section {
                padding-bottom: 150px;
            }
        }
        @media(max-width: 1023px){
            .product-deal-section {
                padding-bottom: 50px;
            }
        }
        @media(max-width: 992px){
            .bf2020-container .bf2019-cards .bf2020-cards__item{
                max-width: 33.3333%;
            }    
        }
        @media(max-width: 767px){
            .bf2020-container .bf2019-cards .bf2020-cards__item{
                max-width: 50%;
            }
            .product-deal-section {
                display: block;
            }
            .product_item {
                width: 100%;
                padding-top: 15px;
            }
            .bf2020-shopping-info {
                display: block;
            }
            .bf2020-shopping-info .info_item {
                width: 100%;
                padding: 0 30px;
            }
            .brand_info {
                margin-bottom: 30px;
            }
        }
        @media(max-width: 560px){
            .bf2020-container .bf2019-cards .bf2020-cards__item{
                max-width: 100%;
            }
        }
        @media(max-width: 480px){
			.bf2019-live-deal__item__image-details-wrapper {
				flex-wrap: wrap;
			}
            .bf2019-live-deal__item__details {
				height: auto;
                margin-top: 50px;
			}
		}
    </style>
    <div class="bf2019-content-container bf2019-header__dice-wrapper">
        <img class="js-bf2019-img bf2019-header__dice"
             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/dice.png"
             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
    </div>
    <div class="bf2019-content">
        <div class="bf2019-bg js-bf2019-bg" data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/bg2.jpg"></div>
        <div class="bf2019-header">
            <div class="bf2019-header__body-wrapper">
                <div class="bf2019-content-container">
                    <img class="js-bf2019-img bf2019-header__banner"
                         data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/banner_2021.png"
                         src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                    <div class="bf2019-header__body">
                        <img class="js-bf2019-img bf2019-header__chips"
                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/chips.png"
                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                        <img class="js-bf2019-img bf2019-header__main-image"
                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/black-lightning-deals.png"
                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                        <h2 class="bf2019-header__heading">ALL DEALS ARE UNLOCKED!<br><strong>LESS THAN 24 HOURS LEFT!</strong></h2>
                        <h3 class="bf2019-header__sub-heading">- ALL PRODUCTS IN THE SHOP ARE AT BASE PRICE - <br>NO REWARDS MONEY NEEDED!</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="bf2019-live-deal-countdown">
            <div class="bf2019-content-container">
                <h2 class="bf2020-live-deal-countdown__headline">ALL DEALS END IN</h2>
                <div class="bf2019-live-deal-countdown__body">
                    <div class="bf2019-live-deal-countdown__body-inner">
                        <img class="js-bf2019-img"
                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/hourglass.png"
                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">

                            <?php 
                                $deal_index = 0;
                                $expired_time = (new DateTime('2021-11-30-04'))->getTimestamp() + 13 * 60 * 60;
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
        </div>
        <div class="bf2019-content-container bf2019-cards-wrapper bf2020-container">
            <div class="bf2019-cards">
                <?php $deal_index = 0;
                $page_ids = array(1436164,1269750,1269648,1439522,1439541,1441778,1439560,1269668,1439628,1439648,1439677,1439689,1294470,1440332,1441840,1440339,1440348,1442150);
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
                    <div class="bf2019-cards__item bf2020-cards__item">
                        <?php if ($is_active): ?>
                            <a class="bf2019-cards__item__image-wrapper" href="<?php echo $page_link; ?>">
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
                        <?php else: ?>
                            <img class="js-bf2019-img bf2019-cards__item__image"
                                 title="<?php echo htmlentities($unlocked_text); ?>"
                                 data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/card-back.jpg"
                                 src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="bf2019-live-deal">
            <div class="bf2019-live-deal__headline-wrapper">
                <div class="bf2019-content-container">
                    <h2 class="bf2019-live-deal__headline">LIVE DEAL COVERAGE</h2>
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
                                        <img class="js-bf2019-img bf2019-live-deal__item__banner <?php printf('deal-%s', $deal_index); ?>"
                                             data-src="<?php printf('%s/bf2019-assets/images/live-deals/deal%s.png', get_template_directory_uri(), $deal_index); ?>"
                                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
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
        <div class="bf2020-product-deal">
            <div class="product-deal-title">
                <h2 class="bf2019-live-deal-countdown__headline">- DOOR BUSTER DEALS! -</h2>
                <h2 class="bf2020-product_title">Check out these deals ending soon!</h2>
            </div>
            
            <div class="buster-deal">
                <a href="/audio-design-desk-next-generation-daw"><img src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/buster_deal.png"></a>
            </div>
            <div class="product-deal-section">

                <?php $item_index = 0;
                $page_ids = array(1441064, 1441288, 1440860, 1440609, 1438800, 1437559);
                // $product_id = $deal['product_id'][0]['id'];
                foreach ($items as $item) {
                    $item_index++;
                    $product_id = $item['product_id'][0]['id'];
                    // $page_link = get_permalink($product_id);
                    $page_link = get_permalink($page_ids[$item_index - 1]);
                ?>
                <div class="product_item">
                    <a href="<?php echo $page_link; ?>">
                    <img class="bf2020-product-img"
                     data-src="<?php echo wp_get_attachment_image_url($item['image'], 'full'); ?>"
                     src="<?php echo wp_get_attachment_image_url($item['image'], 'full'); ?>">
                    </a>
                </div>
                 <?php }?>

            </div>
        </div>
        <h3 class="bf2019-header__sub-heading" style="text-align: center; margin-bottom: 70px;">- ALL PRODUCTS IN THE SHOP ARE AT BASE PRICE - <br>NO REWARDS MONEY NEEDED!</h3>
        <div class="bf2020-shopping-info">
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
        <img class="js-bf2019-img bf2019-live-deal-countdown__pcm"
                 data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/poker-cards-mockup.png"
                 src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
    </div>
    </div>
<?php get_footer();