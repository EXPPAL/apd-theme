<?php
/**
 * Template Name: Black Friday 2019
 */

// deal time
$datetime_start = strtotime(carbon_get_the_post_meta('datetime_start'));
$datetime_start += 5 * 60 * 60; // GMT-5
$now = time();
$deal_active_period = 8 * 60 * 60;
$next_deal_time = $datetime_start;

// promote_video
$unlocked_video_url = carbon_get_the_post_meta('unlocked_video');
$unlocked_text = carbon_get_the_post_meta('unlocked_text');

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
    }
}

//
get_header(); ?>
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
                         data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/banner.png"
                         src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                    <div class="bf2019-header__body">
                        <img class="js-bf2019-img bf2019-header__wallet"
                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/wallet.png"
                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                        <img class="js-bf2019-img bf2019-header__chips"
                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/chips.png"
                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                        <img class="js-bf2019-img bf2019-header__main-image"
                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/header/black-lightning-deals.png"
                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                        <h2 class="bf2019-header__heading">ALL DEALS ARE UNLOCKED!</h2>
                        <h3 class="bf2019-header__sub-heading">EVERY DOLLAR SPENT IS MATCHED IN YOUR APD REWARDS WALLET!</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="bf2019-content-container bf2019-cards-wrapper">
            <div class="bf2019-cards">
                <?php $deal_index = 0;
                $page_ids = array(472509, 471682, 249806, 473248, 68318, 164761, 65145, 163676, 56162, 56324);
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
                    <div class="bf2019-cards__item">
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
                        <iframe width="100%"
                                height="350"
                                src="<?php echo get_youtube_iframe_url($deals[0]['promote_video']); ?>"
                                frameborder="0"
                                data-unlocked_video_url="<?php echo htmlentities($unlocked_video_iframe_url); ?>"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        ></iframe>
                    </div>
                </div>
                <div class="bf2019-live-deal__slider__container" data-active_slide="<?php echo $active_slide; ?>">
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
                                                        <img class="js-bf2019-img bf2019-live-deal__item__logo"
                                                             data-src="<?php echo wp_get_attachment_image_url($deal['logo'], 'full'); ?>"
                                                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                                                    </div>
                                                    <div>
                                                        <div class="bf2019-live-deal__item__price"><?php echo $product ? $product->get_price_html() : ''; ?></div>
                                                        <a class="bf2019-live-deal__item__btn bf2019-live-deal__item__btn-buy-now"
                                                           href="<?php echo do_shortcode(sprintf('[add_to_cart_url id="%s"]', $product_id)); ?>">BUY NOW</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <img class="js-bf2019-img bf2019-live-deal__item__banner <?php printf('deal-%s', $deal_index); ?>"
                                             data-src="<?php printf('%s/bf2019-assets/images/live-deals/deal%s.png', get_template_directory_uri(), $deal_index); ?>"
                                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
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
        <div class="bf2019-live-deal-countdown">
            <div class="bf2019-content-container">
                <h2 class="bf2019-live-deal-countdown__headline">ALL DEALS EXPIRE IN</h2>
                <div class="bf2019-live-deal-countdown__body">
                    <div class="bf2019-live-deal-countdown__body-inner">
                        <img class="js-bf2019-img"
                             data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/hourglass.png"
                             src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
                        <?php $expired_time = (new DateTime('2019-12-03'))->getTimestamp() + 5 * 60 * 60; ?>
                        <div class="bf2019-live-deal-countdown__time-wrapper">
                            <div class="bf2019-live-deal-countdown__time" data-next_deal="<?php echo $expired_time; ?>"></div>
                            <div class="bf2019-live-deal-countdown__text"><span>Hours</span><span>Minutes</span><span>Seconds</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <img class="js-bf2019-img bf2019-live-deal-countdown__pcm"
                 data-src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/poker-cards-mockup.png"
                 src="<?php echo get_template_directory_uri(); ?>/bf2019-assets/images/holder.png">
        </div>
    </div>
    </div>
<?php get_footer();