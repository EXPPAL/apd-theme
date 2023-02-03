<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<div class="footer">
<?php if ( ! is_front_page() ) : ?>

<?php 
    
      if (date('U') > 1 && date('U') < 1672718447 ) {
        	   echo ('<div style="text-align: center; padding-top: 50px; padding-bottom: 50px;" ><a href="https://audioplugin.deals/"><img src="https://audioplugin.deals/wp-content/themes/apd/deals_december_2022.jpg"></a></div>');  
      }
      else {
      		echo '<div><h1 style="font-weight: bold;text-align: center;padding-bottom: 20px;">HOT DEALS ENDING SOON</h1></div><div>';
          echo do_shortcode('[bigdeal_products_banner count="10"]'); 
          echo('</div>');
      }
      ?>
<?php endif; ?>
    <div class="new_letter" style="margin-top:-2px;">
        <div class="news_main_heading" style="background-color: #121212;padding-top:25px; font-family: 'Montserrat', sans-serif;>
            <div class="container">
                <p style="display:none;">newsletter</p>
                <p class="sec_line" style="display:none;"></p>
     <div class="news_main_heading" style="text-align:center;">
    <span>CUSTOMER FEEDBACK</span>
       <div class="footer-testimonials-list" style="text-align:center;color:white;font-weight:bold;width:70%;margin:auto;">
    <?php echo do_shortcode('[testimonial_rotator id="108089"]'); ?></div>
       </div>

            </div><!--container-->
        </div><!--section_heading-->

        <div class="news_inner">
            <div class="container">
                <div class="news_main_heading">
                    <span>WHAT ARE YOU WAITING FOR?</span>
                    <span>Hear about the latest deeply discounted deals in music software.</span>
                </div><!--news_main_heading-->

                <div class="news_input">
                    <!--<form method="POST" name="subscription" action="<?php echo site_url(); ?>/index.php?wp_nlm=subscription">
            <table>
              <tr>
                <td><img src="<?php echo get_template_directory_uri(); ?>/images/email_icon_14.png" alt=""></td>
                <td><input type="text" class="form-control n_input" name="xyz_em_email" placeholder="Enter your email address…"></td>
                <td><input name="htmlSubmit"  id="submit_em" type="submit" class="join_btn hvr-bounce-to-right" value="Subscribe Free" onclick="javascript: if(!xyz_em_verify_fields()) return false; "></td>
              </tr>
            </table>-->
                    <!-- div class="ctct-embed-signup">
                    <span id="success_message" style="display:none;">
                       <div style="text-align:center;">Thanks for signing up!</div>
                   </span>
                    <form data-id="embedded_signup:form" id="mc4wp-form-1" class="mc4wp-form mc4wp-form-139" name="embedded_signup" method="POST" action="https://visitor2.constantcontact.com/api/signup" data-name="Footer Newsletter" >
             <!-- The following code must be included to ensure your sign-up form works properly. -->
                    <input data-id="ca:input" type="hidden" name="ca" value="d94f11d8-cc46-4e10-911d-280ffa9a3382">
                    <input data-id="list:input" type="hidden" name="list" value="1210211202">
                    <input data-id="source:input" type="hidden" name="source" value="EFD">
                    <input data-id="required:input" type="hidden" name="required" value="list,email">
                    <input data-id="url:input" type="hidden" name="url"
                           value="https://audioplugin.deals/email-subscribed/">
                    <!-- table>
                        <tr>
                            <td><img src="https://audioplugin.deals/wp-content/themes/apd/images/email_icon_14.png" alt=""></td>
                            <td><p data-id="Email Address:p" ><label data-id="Email Address:label" data-name="email" class="ctct-form-required" style="display:none;">Email Address</label> <input data-id="Email Address:input" type="text" name="email" value="" class="form-control n_input" placeholder="Enter your email address…"></p></td>
                            <td><button type="submit" class="join_btn hvr-bounce-to-right" data-enabled="enabled">Join Newsletter</button></td>
                        </tr>
                    </table>
                    </form>
                    </div -->
                    <!-- script type='text/javascript'>
                       var localizedErrMap = {};
                       localizedErrMap['required'] =    'This field is required.';
                       localizedErrMap['ca'] =      'An unexpected error occurred while attempting to send email.';
                       localizedErrMap['email'] =       'Please enter your email address in name@email.com format.';
                       localizedErrMap['birthday'] =    'Please enter birthday in MM/DD format.';
                       localizedErrMap['anniversary'] =   'Please enter anniversary in MM/DD/YYYY format.';
                       localizedErrMap['custom_date'] =   'Please enter this date in MM/DD/YYYY format.';
                       localizedErrMap['list'] =      'Please select at least one email list.';
                       localizedErrMap['generic'] =     'This field is invalid.';
                       localizedErrMap['shared'] =    'Sorry, we could not complete your sign-up. Please contact us to resolve this.';
                       localizedErrMap['state_mismatch'] = 'Mismatched State/Province and Country.';
                        localizedErrMap['state_province'] = 'Select a state/province';
                       localizedErrMap['selectcountry'] =   'Select a country';
                       var postURL = 'https://visitor2.constantcontact.com/api/signup';
                    </script -->
                    <!-- script type='text/javascript' src='https://static.ctctcdn.com/h/contacts-embedded-signup-assets/1.0.2/js/signup-form.js'></script -->
          <?php echo do_shortcode( '[mc4wp_form id="139"]' ); ?>
          <?php //echo do_shortcode('[ctct form="619"]'); ?>
                </div><!--news_input-->
            </div><!--container-->
        </div><!--news_inner-->
    </div><!--new_letter-->

    <div class="copyright">
        <div class="container">
            <div>
              <?php if(is_front_page()):?>
                    <p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img
                                src="<?php echo get_template_directory_uri(); ?>/images/logo2_03.png" alt=""></a></p>
              <?php else:?>
                    <p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img
                                src="<?php echo get_template_directory_uri(); ?>/images/logo2_03.png" alt=""></a></p>
              <?php endif; ?>
               <div>
 
                <?php /* wp_nav_menu( array(
                  'menu'       => 'Footer Menu',
                  'container'  => false,
                  'items_wrap' => '<ul style="display:inline-block!important;" class="nav1">%3$s</ul>'
                ) ); */?>

                </div>

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
   

                <?php if (   is_active_sidebar( 'first-footer-widget-area'  )) {?> 
                           <div class="first one-third left widget-area">
                           <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
                            </div><!-- .first .widget-area -->
                 <?php } ?>

                  <?php if (   is_active_sidebar( 'second-footer-widget-area'  )) {?> 
                            <div class="second one-third left widget-area">
                           <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
                            </div><!-- .second .widget-area -->
                 <?php } ?>

                  <?php if (   is_active_sidebar( 'third-footer-widget-area'  )) {?> 
                             <div class="third one-third left widget-area">
                                  <?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
                             </div><!-- .third .widget-area -->
                 <?php } ?>
                 </div>
                 <br/>
                <div style="margin-bottom:20px;">Partners: <a href="https://lunaticaudio.com" target="_blank">Lunatic Audio</a>  |  <a href="https://artistryaudio.com" target="_blank">Artistry Audio</a></div><span >Copyright &copy; <?php echo date( 'Y' ); ?> Audio Plugin Deals. All Rights Reserved. <a
                            href="<?php echo site_url(); ?>/terms-and-conditions">Terms and Conditions</a>  <!--|  <a href="https://audioplugin.deals/privacy-policy/">Privacy Policy</a>--></span>
            </div>

            <div class="top_arrow">
                <a href="#header" class="hvr-bounce-to-top"><img
                            src="<?php echo get_template_directory_uri(); ?>/images/toparow_03.png" alt="" class=""></a>
            </div><!--top_arrow-->
        </div><!--container-->
    </div><!--copyright-->
</div><!--footer-->
</div><!--mainwrapper-->

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            // Configure/customize these variables.
            var showChar = 400;  // How many characters are shown by default
            var ellipsestext = "...";
            var moretext = "Show more >";
            var lesstext = "Show less";


            $('.more').each(function () {
                var content = $(this).html();

                if (content.length > showChar) {

                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar, content.length - showChar);

                    var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(html);
                }

            });

            $(".morelink").click(function () {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });
        });
    })(jQuery);
</script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>
<!--end menu-->
<script src="<?php echo get_template_directory_uri(); ?>/js/plugin.js"></script>
<script>
    jQuery(document).ready(function () {
        jQuery('.slidewrap').carousel({
            slider: '.slider',
            slide: '.slide',
            slideHed: '.slidehed',
            nextSlide: '.next',
            prevSlide: '.prev',
            addPagination: false,
            addNav: false
        });
        jQuery('.home .woocommerce-message').delay(5000).fadeOut(400);
        jQuery('dt.variation-CouponCodes').text('Download Redemption Code:');

    });
</script>
<!-- -----------------end slider ----------------------- -->


<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-scrolltofixed.js"></script>
<script type="text/javascript">
    jQuery('.sticky-navigation').scrollToFixed({
        marginTop: 28,
    });
    jQuery('.sticky-navigation-top').scrollToFixed();
</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        (function ($) {


            jQuery('a[href*=#]:not([href=#])').click(function () {
                if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                    || location.hostname == this.hostname) {

                    var target = jQuery(this.hash),
                        headerHeight = jQuery(".header2").height() + 2; // Get fixed header height

                    target = target.length ? target : jQuery('[name=' + this.hash.slice(2) + ']');

                    if (target.length) {
                        jQuery('html,body').animate({
                            scrollTop: target.offset().top - headerHeight
                        }, 1000);
                        return false;
                    }
                }
            });
        })(jQuery);

        jQuery("#menu select").change(function () {
            var val = jQuery(this).val();
            var headerHeight = jQuery(".header2").height() + 2; // Get fixed header height
            var p = jQuery(val);
            //var off = p.offset();
            //alert(off.top);
            jQuery('html,body').animate({
                scrollTop: p.offset().top - headerHeight
            }, 1000);
            return false;

        });

    });
</script>


<script>
    function xyz_em_verify_fields() {
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var address = document.subscription.xyz_em_email.value;
        if (reg.test(address) == false) {
            alert('Please check whether the email is correct.');
            return false;
        } else {
//document.subscription.submit();
            return true;
        }
    }
</script>
<!-- PayPal BEGIN --> <script> ;(function(a,t,o,m,s){a[m]=a[m]||[];a[m].push({t:new Date().getTime(),event:'snippetRun'});var f=t.getElementsByTagName(o)[0],e=t.createElement(o),d=m!=='paypalDDL'?'&m='+m:'';e.async=!0;e.src='https://www.paypal.com/tagmanager/pptm.js?id='+s+d;f.parentNode.insertBefore(e,f);})(window,document,'script','paypalDDL','27f5cf03-aa79-4b12-b6ec-31857b348501'); </script> <!-- PayPal END -->
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
</style>
<?php wp_footer(); ?>
<!-- script type="text/javascript">
  (function() {
    window._pa = window._pa || {};
     
      
    if ( window.order_data_for_perfectaudience ) {
    	_pa.orderId = window.order_data_for_perfectaudience.order_id; // OPTIONAL: attach unique conversion identifier to conversions
    	_pa.revenue = window.order_data_for_perfectaudience.revenue; // OPTIONAL: attach dynamic purchase values to conversions
    	_pa.productId = window.order_data_for_perfectaudience.product_id; // OPTIONAL: Include product ID for use with dynamic ads
     	console.log(_pa);
    }
    
    var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;
    pa.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + "//tag.perfectaudience.com/serve/604a42124315116696000047.js";
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(pa, s);
  })();
</script -->
<script type="text/javascript">
    adroll_adv_id = "EZPIZRWUFNGFFKASJG7RWX";
    adroll_pix_id = "ROVX4LYVDNETZHL64TFO4L";

    (function () {
        var _onload = function(){
            if (document.readyState && !/loaded|complete/.test(document.readyState)){setTimeout(_onload, 10);return}
            if (!window.__adroll_loaded){__adroll_loaded=true;setTimeout(_onload, 50);return}
            var scr = document.createElement("script");
            var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
            scr.setAttribute('async', 'true');
            scr.type = "text/javascript";
            scr.src = host + "/j/roundtrip.js";
            ((document.getElementsByTagName('head') || [null])[0] ||
                document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
        };
        if (window.addEventListener) {window.addEventListener('load', _onload, false);}
        else {window.attachEvent('onload', _onload)}
    }());
</script>
</body>
</html>