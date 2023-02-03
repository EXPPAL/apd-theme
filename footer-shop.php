<?php
$page_name = get_query_var('pagename');
$is_shop_page = get_post_meta(get_the_ID(), 'is_shop_page', true);

if ($is_shop_page == 'yes') {
    $product_id = get_post_meta(get_the_ID(), 'meta_product_id', true);
    $_pf = new WC_Product_Factory();
    $product = $_pf->get_product($product_id);
    $disable_rewards_and_offer_base_price = is_disable_rewards_and_offer_base_price($product_id);
    ?>
    <div id="buynow" class="vc_row wpb_row vc_row-fluid dont_miss vc_custom_1478159727745 vc_row-has-fill">
        <div class="wpb_column vc_column_container vc_col-sm-6"
             style="<?php if ($product->get_id() == 119783): ?> display:none; <?php endif; ?>">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">
                    <div class="wpb_text_column wpb_content_element ">
                        <div class="wpb_wrapper">
                            <div class="deal">
                                <p style="<?php if ($product->get_id() == 404196 || $product->get_id() == 107885 || $product->get_id() == 106354 || $product->get_id() == 106363 || $product->get_id() == 37604 || $product->get_id() == 221646): ?> display:none; <?php endif; ?>">
                                    EARN AND REDEEM REWARDS MONEY NOW!</p>
                                <div style="text-align:center;">
                                    <section class="hero-submenu-footer">
                                        <div style="text-align:center;"></div>
                                        <button class="btn-open-video"
                                                style="background:transparent;border-color:transparent;<?php if ($product->get_id() == 404196 || $product->get_id() == 107885 || $product->get_id() == 106354 || $product->get_id() == 106363 || $product->get_id() == 37604 || $product->get_id() == 221646): ?> display:none; <?php endif; ?>">
                                            Learn How The Shop Works<br/>Click to Watch Video
                                        </button>
                                        <section class="video-popup">
                                            <div class="wrapper-video">
                                                <div class="action-holder">
                                                    <button data-action="open-short-video">Play short video (0:53)
                                                    </button>
                                                    <button data-action="open-long-video">Play long video (4:06)
                                                    </button>
                                                </div>
                                                <div class="viewport short-video">
                                                    <iframe class="video"
                                                            src="https://www.youtube.com/embed/TMeLrvmGiVQ"
                                                            frameborder="0" allowfullscreen></iframe>
                                                </div><!-- end .viewport -->
                                                <div class="viewport long-video">
                                                    <iframe class="video"
                                                            src="https://www.youtube.com/embed/AJPll1n-o_k"
                                                            frameborder="0" allowfullscreen></iframe>
                                                </div><!-- end .viewport -->
                                            </div>
                                        </section>
                                    </section><!-- end .hero-banner -->
                                </div>
                            </div>
                            <p><!--deal--></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-6"
             style="<?php if ($product->get_id() == 221646): ?> display:none; <?php endif; ?>">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">
                    <?php if (!$product->is_type('variable')): ?>
                        <?php $sale_price = $product->get_sale_price();
                        $is_one_price = !$disable_rewards_and_offer_base_price && !$sale_price; ?>
                        <div class="item-details <?php echo $is_one_price ? 'one-price' : '' ?>">
                            <div class="retail-price">
                                <?php if (!$is_one_price): ?>
                                    <span class="label">retail price</span>
                                    <span class="price"><?php echo $product->get_regular_price(); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if (!$disable_rewards_and_offer_base_price): ?>
                                <div class="rewards-holder"
                                     style="<?php if ($product->get_id() == 400853 || $product->get_id() == 508955 || $product->get_id() == 817271 || $product->get_id() == 817615): ?> display:none; <?php endif; ?>">
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
                                                class="rewardbox"><?php echo (get_user_meta(get_current_user_id(), '_reward_points', true)) ? get_user_meta(get_current_user_id(), '_reward_points', true) : number_format(0, 2, '.', ''); ?></mark> in rewards</span>
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
                                            <input type="text" name="use_reward" class="use_reward"
                                                   value="<?php echo $can_use; ?>">
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
                                        <p>For every dollar you spend with APD, past, present or future, we will give
                                            you
                                            back at least 10% in store rewards credit that will be saved in your rewards
                                            wallet.</p>
                                    </div>
                                </div><!-- end .rewards-holder -->
                            <?php endif; ?>
                            <div class="action-holder"
                                 style="<?php if ($product->get_id() == 400853 || $product->get_id() == 508955 || $product->get_id() == 817271 || $product->get_id() == 817615): ?> width:100%!important; <?php endif; ?>">
                                <span class="label">current price</span>
                                <span class="price-value price-box"><?php echo wc_price(apd_get_product_price($product)); ?></span>
                                <?php $style = '';
                                if ($disable_rewards_and_offer_base_price || $product->get_id() == 400853 || $product->get_id() == 508955 || $product->get_id() == 817271 || $product->get_id() == 817615) {
                                    $style = 'display:none;';
                                } ?>
                                <span class="sublabel" style="<?php echo $style; ?>">base price
                                    <mark class="base_price"><?php echo get_post_meta($product->get_id(), '_minumum_price', true); ?></mark>
                                </span>
                                <form class="cart" method="post" enctype='multipart/form-data'>
                                    <input type="hidden" name="quantity" value="1"/>
                                    <input type="hidden" name="add-to-cart" value="<?php echo $product->get_id(); ?>"/>
                                    <input type="hidden" name="use_rewards" class="use_rewards" value="0">
                                    <input type="hidden" name="original_price" class="original_price"
                                           value="<?php echo apd_get_product_price($product); ?>">
                                    <input type="hidden" name="original_rewards" class="original_rewards"
                                           value="<?php echo (is_user_logged_in()) ? get_user_meta(get_current_user_id(), '_reward_points', true) : 0; ?>">
                                    <button>add to cart</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="hot-deals-footer">
	
	<div><?php 
    
                           
          
      
      if (date('U') > 1 && date('U') < 1672718447 ) {
          echo ('<div style="text-align: center; padding-top: 50px;" ><a href="https://audioplugin.deals/"><img src="https://audioplugin.deals/wp-content/themes/apd/deals_december_2022.jpg"></a></div>');  	
      }
      else {
      echo '<div><h1 style="font-weight:bold;margin-top:20px;margin-bottom:20px;text-align:center;">HOT DEALS ENDING SOON</p></div><div>';
          echo do_shortcode('[bigdeal_products_banner count="10"]'); 
          echo('</div>');
      }
      ?>
        <?php
        // $id = 256167;
        // $post = get_post($id);
        // $content = apply_filters('the_content', $post->post_content);
        // echo $content;
        ?>

    </div>
    <style type="text/css">
        .hot-deals-footer {
            margin-bottom: 50px;
        }

        .hot-heading-footer {
            margin-top: 50px !important;
            margin-bottom: 30px !important;
        }

        .custom-product-page .wpb_content_element.padding-footer-btn {
            margin-top: 22px;

            margin-bottom: 22px !important;
        }
    </style>
    <?php


    // require_once get_template_directory() . '/inc/bottom-menu.php';
}
?>
</main>

<footer>
    <div class="new_letter">
        <div class="news_main_heading" style="background-color:black;padding-top:25px;">
            <div class="container">
                <div class="news_main_heading" style="text-align:center;">
                    <span>CUSTOMER FEEDBACK</span>
                    <div style="text-align:center;color:white;font-weight:bold;width:70%;margin:auto;"><?php echo do_shortcode('[testimonial_rotator id="108089"]'); ?></div>
                </div>
            </div><!--container-->
        </div><!--section_heading-->

        <?php if ($is_shop_page != 'yes') : ?>
            <div class="section_heading newletter">
                <div class="container">
                    <p>newsletter</p>
                    <p class="sec_line"></p>
                </div><!--container-->
            </div><!--section_heading-->
        <?php endif; ?>

        <div class="news_inner">
            <div class="container">
                <div class="news_main_heading">
                    <span>WHAT ARE YOU WAITING FOR?</span>
                    <span>Hear about the latest deeply discounted deals in music software.</span>
                </div><!--news_main_heading-->

                <div class="news_input">
                    <!-- The following code must be included to ensure your sign-up form works properly. -->
                    <input data-id="ca:input" type="hidden" name="ca" value="d94f11d8-cc46-4e10-911d-280ffa9a3382">
                    <input data-id="list:input" type="hidden" name="list" value="1210211202">
                    <input data-id="source:input" type="hidden" name="source" value="EFD">
                    <input data-id="required:input" type="hidden" name="required" value="list,email">
                    <input data-id="url:input" type="hidden" name="url"
                           value="https://audioplugin.deals/email-subscribed/">

                    <!-- MailChimp for WordPress v4.1.11 - https://wordpress.org/plugins/mailchimp-for-wp/ -->
                    <!--  <form id="mc4wp-form-1" class="mc4wp-form mc4wp-form-139" method="post" data-id="139"
                          data-name="Footer Newsletter">
                        <div class="mc4wp-form-fields">
                            <table>
                                <tr>
                                    <td>
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/shop/icon-email-blue.png"
                                             alt=""></td>
                                    <td><input type="email" class="form-control n_input" name="EMAIL"
                                               placeholder="Enter your email address…" required></td>
                                    <td><input name="submit" id="submit_em" type="submit"
                                               class="join_btn hvr-bounce-to-right" value="Join Newsletter"></td>
                                </tr>
                            </table>
                            <label style="display: none !important;">Leave this field empty if you're human: <input
                                        type="text" name="_mc4wp_honeypot" value="" tabindex="-1"
                                        autocomplete="off"/></label><input type="hidden" name="_mc4wp_timestamp"
                                                                           value="1510353742"/><input type="hidden"
                                                                                                      name="_mc4wp_form_id"
                                                                                                      value="139"/><input
                                    type="hidden" name="_mc4wp_form_element_id" value="mc4wp-form-1"/></div>
                        <div class="mc4wp-response"></div>
                    </form> -->
                    <form id="mc4wp-form-1" class="mc4wp-form mc4wp-form-139" method="post" data-id="139"
                          data-name="Footer Newsletter" _lpchecked="1">
                        <div class="mc4wp-form-fields">
                            <table>
                                <tbody>
                                <tr style="display: flex; align-items: center; justify-content: center; flex-direction: row-reverse;">
                                    <!--    <td><img src="https://audioplugin.deals/wp-content/themes/apd/images/email_icon_14.png" alt=""></td>-->
                                    <td><input name="submit" id="submit_em" type="submit"
                                               class="join_btn hvr-bounce-to-right" value="Join Newsletter"></td>
                                    <td><input type="email" class="form-control n_input" name="EMAIL"
                                               placeholder="Enter your email address…" required=""></td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <label style="display: none !important;">Leave this field empty if you're human: <input
                                    type="text" name="_mc4wp_honeypot" value="" tabindex="-1"
                                    autocomplete="off"></label><input type="hidden" name="_mc4wp_timestamp"
                                                                      value="1561546977"><input type="hidden"
                                                                                                name="_mc4wp_form_id"
                                                                                                value="139"><input
                                type="hidden" name="_mc4wp_form_element_id" value="mc4wp-form-1">
                        <div class="mc4wp-response"></div>
                    </form>
                    <!-- / MailChimp for WordPress Plugin -->
                </div><!--news_input-->
            </div><!--container-->
        </div><!--news_inner-->
    </div><!--new_letter-->
    <div class="footer-bottom">
        <div class="logo-holder" style="margin-top:20px;">
            <a href="<?php echo get_permalink(get_page_by_path('shop')); ?>"><img
                        src="<?php echo get_template_directory_uri(); ?>/images/shop/logo_horizontal.png"
                        alt="logo"></a>
        </div>
        <!--   <nav>
			<?php wp_nav_menu(array(
            'menu' => 'Footer Menu',
            'container' => false,
            'items_wrap' => '<ul class="nav1">%3$s</ul>'
        )); ?>
        </nav>  -->
<div class="footer-card-logos">
<span style="font-family: 'Arial', sans-serif; font-weight: 700;display:none;" >We Accept:</span>
<img src="/wp-content/themes/apd/card-logos/Visa.svg">
<img src="/wp-content/themes/apd/card-logos/MasterCard.svg">
<img src="/wp-content/themes/apd/card-logos/Amex.svg">
<img class="hi-vis" src="/wp-content/themes/apd/card-logos/Discovery.svg">
<img src="/wp-content/themes/apd/card-logos/PayPal.svg">
<img src="/wp-content/themes/apd/card-logos/UnionPay.svg">
<img src="/wp-content/themes/apd/card-logos/JCB.svg">
<img class="hi-vis" src="/wp-content/themes/apd/card-logos/ApplePay.svg">
<img src="/wp-content/themes/apd/card-logos/GooglePay.svg">
<img src="/wp-content/themes/apd/card-logos/Venmo.svg">
</div>
        <div class="fatfooter" role="complementary">


            <?php if (is_active_sidebar('first-footer-widget-area')) { ?>
                <div class="first one-third left widget-area">
                    <?php dynamic_sidebar('first-footer-widget-area'); ?>
                </div><!-- .first .widget-area -->
            <?php } ?>

            <?php if (is_active_sidebar('second-footer-widget-area')) { ?>
                <div class="second one-third left widget-area">
                    <?php dynamic_sidebar('second-footer-widget-area'); ?>
                </div><!-- .second .widget-area -->
            <?php } ?>

            <?php if (is_active_sidebar('third-footer-widget-area')) { ?>
                <div class="third one-third left widget-area">
                    <?php dynamic_sidebar('third-footer-widget-area'); ?>
                </div><!-- .third .widget-area -->
            <?php } ?>
        </div>

        <div class="copyright-holder">
		<div style="margin-bottom:20px;text-align:center;">Partners: <a href="https://lunaticaudio.com" target="_blank">Lunatic Audio</a></div>
            <p>Copyright &copy; <?php echo date('Y'); ?> Audio Plugin Deals. All Rights Reserved. <a
                        href="<?php echo site_url(); ?>/terms-and-conditions">Terms and Conditions</a></p>
        </div>
    </div>
</footer>
<div class="button-back-to-top"></div>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js" rel="preload"></script> -->
<?php wp_footer(); ?>
<script>
  var chimpPopupLoader = document.createElement("script");
  chimpPopupLoader.src = '//downloads.mailchimp.com/js/signup-forms/popup/embed.js';
  chimpPopupLoader.setAttribute('data-dojo-config', 'usePlainJson: true, isDebug: false');

  function showMailingPopUp() {
    require(["mojo/signup-forms/Loader"], function (L) {
      L.start({"baseUrl": "mc.us15.list-manage.com", "uuid": "63ba9268d7ed400d7bd933660", "lid": "3fb114fa3c"})
    })
    document.cookie = 'MCPopupClosed=;path=/;expires=Thu, 01 Jan 1970 00:00:00 UTC;';
    document.cookie = 'MCPopupSubscribed=;path=/;expires=Thu, 01 Jan 1970 00:00:00 UTC;';
  };


  jQuery(window).on("load", function () {
    document.body.appendChild(chimpPopupLoader);
    jQuery("#mc-form-popup").click(function () {
      showMailingPopUp()
    });
  });
</script>
<!-- PayPal BEGIN -->
<script> ;(function (a, t, o, m, s) {
    a[m] = a[m] || [];
    a[m].push({t: new Date().getTime(), event: 'snippetRun'});
    var f = t.getElementsByTagName(o)[0], e = t.createElement(o), d = m !== 'paypalDDL' ? '&m=' + m : '';
    e.async = !0;
    e.src = 'https://www.paypal.com/tagmanager/pptm.js?id=' + s + d;
    f.parentNode.insertBefore(e, f);
  })(window, document, 'script', 'paypalDDL', '27f5cf03-aa79-4b12-b6ec-31857b348501'); </script> <!-- PayPal END -->
<style type="text/css">

    .one-third,
    {
        float: left;
    }

    /* widths */
    .one-third {
        width: 31%;
    }


    /* margins  */
    .one-third {
        margin: 0 0.5%;
    }

    .one-third.left {
        margin: 0 1% 0 0;
        float: left;
    }

    .one-third.right {
        margin: 0 0 0 1%;
        float: right;
    }

    li.footer_url_custom {
        float: left;
        text-align: -webkit-left;
        width: 100%;
    }

    footer .footer-bottom {

        height: 640px;
    }
</style>
<!-- script type="text/javascript">
  (function() {
    window._pa = window._pa || {};
    // _pa.orderId = "myOrderId"; // OPTIONAL: attach unique conversion identifier to conversions
    // _pa.revenue = "19.99"; // OPTIONAL: attach dynamic purchase values to conversions
    // _pa.productId = "myProductId"; // OPTIONAL: Include product ID for use with dynamic ads
        
    var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;
    pa.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + "//tag.perfectaudience.com/serve/604a42124315116696000047.js";
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(pa, s);
  })();
</script -->
</body>
</html>