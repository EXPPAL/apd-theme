<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>
<?php
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/my-account/');
}
$current_user = wp_get_current_user();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'paged' => $paged,
    'post_type' => wc_get_order_types('view-orders'),
    'post_status' => 'wc-completed',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => '_customer_user',
            'value' => get_current_user_id(),
        ),
    ),
);
$customer_orders = new WP_Query($args);
?>
<?php get_header('shop'); ?>
<section class="hero-rewards">
    <div class="wrapper">
        <h1 class="title">rewards wallet</h1>
        <div class="row">
            <div class="account-info-holder">
                <span class="user-name"><?php echo $current_user->first_name . ' ' . $current_user->last_name ?></span>
                <span class="user-email"><?php echo $current_user->user_email; ?></span>
                <nav class="woocommerce-MyAccount-navigation">
                    <ul>
                        <?php foreach (wc_get_account_menu_items() as $endpoint => $label) :
                            if (in_array($endpoint, array('orders', 'customer-logout'))) {
                                continue;
                            } ?>
                            <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"
                                   class="btn-logout" style="margin-bottom: 10px;"><?php echo esc_html($label); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
                <div>
                    <a href="<?php echo wp_logout_url(); ?>" class="btn-logout">Logout</a>
                </div>
            </div><!-- end .account-info-holder -->

            <div class="wallet-info-holder">
                <div class="content">
                    <span>you have</span>
                    <span
                            class="value">$<?php echo (get_user_meta($current_user->ID, '_reward_points', true)) ? number_format(get_user_meta($current_user->ID, '_reward_points', true), 2, '.', '') : 0; ?></span>
                    <span>in rewards</span>
                </div>

                <a href="<?php echo get_permalink(get_page_by_path('shop')); ?>" class="btn-go-shop">go shop</a>
                <?php
                $cart = WC()->cart->get_cart();
                $rewards = 0;
                foreach ($cart as $item) {
                    if (isset($item['use_rewards'])) {
                        $rewards += $item['use_rewards'];
                    }
                }
                if ($rewards > 0 && count($cart) == 1) {
                    echo '<p>You have an item in the cart using: $' . $rewards . ' in rewards money. <a href="' . get_permalink(get_page_by_path('checkout')) . '">Checkout now</a></p>';
                } elseif ($rewards > 0 && count($cart) > 1) {
                    echo '<p>You have items in the cart using: $' . $rewards . ' in rewards money. <a href="' . get_permalink(get_page_by_path('checkout')) . '">Checkout now</a></p>';
                }
                ?>
            </div><!-- end .wallet-ifo-holder -->

            <div class="action-holder">
                <a style="cursor: pointer" class="btn-video btn-open-video"></a>
                <section class="video-popup">
                    <div class="wrapper-video">
                        <div class="action-holder">
                            <button data-action="open-short-video">Play short video (0:53)</button>
                            <button data-action="open-long-video">Play long video (4:06)</button>
                        </div>
                        <div class="viewport short-video">
                            <iframe class="video" src="https://www.youtube.com/embed/TMeLrvmGiVQ" frameborder="0"
                                    allowfullscreen></iframe>
                        </div><!-- end .viewport -->
                        <div class="viewport long-video">
                            <iframe class="video" src="https://www.youtube.com/embed/AJPll1n-o_k" frameborder="0"
                                    allowfullscreen></iframe>
                        </div><!-- end .viewport -->
                    </div>
                </section>
            </div><!-- end .action-holder -->
        </div><!-- end .row -->
    </div><!-- end .wrapper -->
</section><!-- end .hero-rewards -->

<div class="rsc-templates" style="display: none;">
    <div class="rating-system-content">
        <div class="rsc__product-image">__IMAGE__</div>
        <div class="rsc__product-title">__TITLE__</div>
        <div class="rsc__form">
            <div class="rsc__heading">On a scale of 0 - 5, rate your overall experience with this product.</div>
            <ul class="rsc__rating">
                <?php $rating_options = APD_Rating_System::$option;
                foreach ($rating_options as $value => $label): ?>
                    <li class="rsc__rating__option">
                        <input id="__FEATHER_ID__-rating-<?php echo $value; ?>" name="rating" type="radio"
                               value="<?php echo $value; ?>">
                        <label for="__FEATHER_ID__-rating-<?php echo $value; ?>"><span><?php echo $label; ?></span></label>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn btn-submit-rating" disabled>Submit</button>
            <div class="rsc__error"></div>
            <div class="rsc__hint">You will immediately receive $5 into your APD rewards wallet after rating this
                product!
            </div>
        </div>
        <div class="rsc__info-and-thank-you">
            <div class="rsc__thank-you-message">Thanks for rating this product! We've just added $5 to your rewards
                wallet.
            </div>
            <div class="rsc__average-rating">
                <div class="rsc__average-rating__rating" data-rating="__ROUNDED_AVERAGE_RATING__"><span>__AVERAGE_RATING__</span><span>/5.0</span>
                </div>
                <div class="rsc__average-rating__help-text">On average, users rated this product <span>__ROUNDED_AVERAGE_RATING_TEXT__</span>
                </div>
                <div class="rsc__text-apd-meter">APD METER</div>
                <div class="rsc__total-count">Total count: <span>__TOTAL_COUNT__</span></div>
            </div>
        </div>
    </div>
    <div class="rsc__average-rating-short-template">
        <div class="rsc__average-rating short">
            <div class="rsc__average-rating__rating" data-rating="__ROUNDED_AVERAGE_RATING__">
                <span>__AVERAGE_RATING__</span><span>/5.0</span>
            </div>
        </div>
    </div>
</div>

<section class="shop-carusel">
    <div class="top-ribbon">
        <h2>Most recent orders</h2>
    </div>
    <div class="carusel-wrapper">
        <?php if ($customer_orders->have_posts()): ?>
            <div class="arrow-left">
                <span class="label-top">You spent</span>
                <span class="label-bottom">You Earned</span>
            </div>

            <div class="viewport">
                <ul>
                    <?php while ($customer_orders->have_posts()) :
                        $customer_orders->the_post();
                        $customer_order = wc_get_order(get_the_ID());
                        $order_meta = get_post_meta(get_the_ID());
                        $order_items = $customer_order->get_items();

                        foreach ($order_items as $item):
                            $reward_points = get_post_meta($item['product_id'], '_reward_points', true);
                            $reward_points = is_numeric($reward_points) ? $reward_points : 0;
                            $earned = $item['total'] * $reward_points / 100;
                            $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
                            $post_thumbnail_id = get_post_thumbnail_id($item['product_id']);
                            $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
                            $color_class = (get_post_meta($item['product_id'], '_product_type_single', true) != 'yes') ? 'red' : 'blue';

                            ?>
                            <li class="item-box">
                                <span class="info-top <?php echo $color_class ?>"><?php echo number_format($item['total'], 2, '.', ''); ?></span>
                                <a href="#<?php echo $order_meta['_invoice_number_display'][0]; ?>">
                                    <img width="124" height="172" src="<?php echo $full_size_image[0]; ?>" alt="">
                                </a>
                                <span class="info-bottom <?php echo $color_class; ?>"><?php echo number_format(floatval($earned), 2, '.', ''); ?></span>
                                <?php if (APD_Rating_System::current_user_can_rate_product($item['product_id'])):
                                    $product = new WC_Product($item['product_id']);
                                    $product_data = array(
                                        'id' => $product->get_id(),
                                        'image' => $full_size_image[0],
                                        'title' => $product->get_title(),
                                        'sku' => $product->get_sku(),
                                    ); ?>
                                    <a href="#" class="btn btn-rate-product"
                                       data-product_id="<?php echo $item['product_id']; ?>"
                                       data-product="<?php echo htmlentities(json_encode($product_data)); ?>">Rate this
                                        product</a>
                                <?php else: ?>
                                    <?php APD_Rating_System::product_rating_short_html($item['product_id']); ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endwhile; ?>
                </ul>
            </div>
            <div class="arrow-right"></div>
        <?php else: ?>
            <p>No recent orders found</p>
        <?php endif; ?>
    </div>

    <div class="bottom-ribbon">
        <div class="pagination-holder">
            <?php $big = 999999999; // need an unlikely integer
            $links = paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $customer_orders->max_num_pages,
            ));
            echo $links; ?>
        </div><!-- end .pagination-holder -->
    </div><!-- end .bottom-ribbon -->
</section>

<section class="history-table">
    <div class="top-ribbon">
        <h2>History</h2>
        <div class="filter">
            <form action="" method="get" id="wallet_filter">
                <label for="wallet_filter">View</label>
                <select name="wallet_filter" id="wallet_select" placeholder="Last Days">
                    <option value="alltime">All Time</option>
                    <option value="week">Last week</option>
                    <option value="month">Last 30 days</option>
                    <option value="year">Last Year</option>
                </select>
            </form>
            <form action="" method="get" id="search_order_form">
                <input type="search" id="order_search" name="order_search" placeholder="Search Orders">
            </form>

        </div>
    </div><!-- end .top-ribbon -->

    <?php
    $meta = '';
    $date = '';
    if (isset($_GET['order_search']) && !empty($_GET['order_search'])) {
        /*$meta = array(
            'key'   => '_invoice_number_display',
            'value' => $_GET['order_search'],
        ); */
    }
    if (isset($_GET['wallet_filter'])) {
        if ($_GET['wallet_filter'] == 'week') {
            $date = array('after' => '1 week ago');
        }
        if ($_GET['wallet_filter'] == 'month') {
            $date = array('after' => '1 month ago');
        }
        if ($_GET ['wallet_filter'] == 'year') {
            $date = array('after' => '1 year ago');
        }
    }
    $args = array(
        'paged' => $paged,
        'post_type' => wc_get_order_types('view-orders'),
        'post_status' => 'wc-completed',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_customer_user',
                'value' => get_current_user_id(),
            ),
            //$meta,
        ),
        'date_query' => array(
            $date
        )
    );
    $customer_orders = new WP_Query($args);
    ?>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Company</th>
                <th>Price</th>
                <th>Rewards Earnings</th>
                <th>Invoice</th>
                <th>Download Instructions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($customer_orders->have_posts()): ?>
                <?php while ($customer_orders->have_posts()) : $customer_orders->the_post(); ?>
                    <?php
                    $customer_order = wc_get_order(get_the_ID());
                    $order_meta = get_post_meta(get_the_ID());
                    $items = $customer_order->get_items();
                    $products = array();
                    $is_find_product_name = false;
                    foreach ($items as $key => $item) {
                        $developer_name = '';
                        if (get_post_meta($item['product_id'], '_product_type_single', true) == 'yes') {
                            $objects = wp_get_post_terms($item['product_id'], 'developer');
                            if (count($objects) > 0) {
                                $developer_name = $objects[0]->name;
                            }
                        }
                        if (isset($_GET['order_search'])) {
                            if (stristr($item['name'], $_GET['order_search'])) {
                                $is_find_product_name = true;
                            }
                        }
                        $company = (!empty($developer_name)) ? $developer_name : get_field('lto_company_name', $item['product_id']);
                        $link = '';
                        $args = array(
                            'post_type' => 'page',
                            'meta_query' => array(
                                array(
                                    'key' => 'meta_product_id',
                                    'value' => $item['product_id'],
                                    'compare' => '=',
                                )
                            )
                        );

                        $query = new WP_Query($args);
                        $link = (isset($query->posts[0])) ? get_permalink($query->posts[0]->ID) : '';
                        $prod = array(
                            'id' => $item['product_id'],
                            'name' => $item['name'],
                            'price' => $item['total'],
                            'company' => $company,
                            'link' => $link
                        );
                        $products[] = $prod;
                    }
                    if ($is_find_product_name === false && isset($_GET['order_search'])) {
                        if (!stristr($order_meta['_invoice_number_display'][0], $_GET['order_search'])) {
                            continue;
                        }
                    }

                    ?>
                    <tr>
                        <td>
                            <span class="date"><a
                                        id="<?php echo $order_meta['_invoice_number_display'][0] ?>"></a><?php echo date('Y-m-d', strtotime($customer_order->get_date_created())); ?></span>
                        </td>
                        <td><span><?php echo $order_meta['_invoice_number_display'][0] ?></span></td>
                        <td>
                            <a href="<?php echo (!empty($products[0]['link'])) ? $products[0]['link'] : ''; ?>"
                               class="product-link <?php echo (get_post_meta($products[0]['id'], '_product_type_single', true) != 'yes') ? 'red' : 'blue' ?>"><?php echo $products[0]['name']; ?></a>
                        </td>
                        <td><span><?php echo $products[0]['company']; ?></span></td>
                        <td>
                            <span class="wallet_price"><?php echo number_format($products[0]['price'], 2, '.', ''); ?></span>
                        </td>
                        <td>
                            <span class="reward"><?php echo number_format($products[0]['price'] * get_post_meta($products[0]['id'], '_reward_points', true) / 100, 2, '.', ''); ?></span>
                        </td>

                        <?php
                        if (!empty($order_meta['_invoice_number'][0])) {
                            $invoice_link = get_permalink(get_page_by_path('my-account')) . 'orders/?pdfid=' . $customer_order->get_id();
                            // $order_details_url = get_permlink( get_page_by_path( 'my-account' ) ) . 'view-order/' . $customer_order->get_id();
                        }
												else if (!empty($order_meta['fastspring_invoice_url'][0])) {
                            $invoice_link = $order_meta['fastspring_invoice_url'][0];
                        } else {
                            $pdfnonce = wp_hash( $customer_order->get_order_key(), 'nonce' );
                            $invoice_link = get_permalink(get_page_by_path('my-account')) . 'orders/?pdfid=' . $customer_order->get_id() . '&pdfnonce=' . $pdfnonce;
                        }

                        ?>
                        <td><a href="<?php echo $invoice_link; ?>" class="invoice"></a></td>
                        <td><a style="cursor: pointer" class=""
                               href="<?php echo get_permalink(get_page_by_path('my-account')) . 'view-order/' . $customer_order->get_id(); ?>">View
                                Download
                                Instructions</a></td>
                    </tr>
                    <?php foreach ($products as $k => $prod): if ($k < 1) {
                        continue;
                    } ?>
                        <tr>
                            <td>

                            </td>
                            <td></td>
                            <td><a href="<?php echo (!empty($prod['link'])) ? $prod['link'] : ''; ?>"
                                   class="product-link <?php echo (get_post_meta($prod['id'], '_product_type_single', true) != 'yes') ? 'red' : 'blue' ?>""><?php echo $prod['name']; ?></a>
                            </td>
                            <td><span><?php echo $prod['company']; ?></span></td>
                            <td>
                                <span class="wallet_price"><?php echo number_format($prod['price'], 2, '.', ''); ?></span>
                            </td>
                            <td>
                                <span class="reward"><?php echo number_format($prod['price'] * get_post_meta($prod['id'], '_reward_points', true) / 100, 2, '.', ''); ?></span>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>

                <?php endwhile; ?>
            <?php endif; ?>
            <tbody>
        </table>
    </div><!-- end .table-wrapper -->

    <div class="bottom-ribbon">
        <div class="pagination-holder"><?php
            $big = 999999999; // need an unlikely integer
            $links = paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $customer_orders->max_num_pages,
            ));
            echo $links;
            ?></div>
    </div><!-- end .bottom-ribbon -->

</section><!-- end .history-table -->
<section class="cta-rewards">
    <div class="container">
        <?php
        if (get_user_meta($current_user->ID, 'fb_shared', 1) == 'yes') {
            echo '<p class=cangrats-message><span>Congratulations!</span><span>You have redeemed $20/$20</span></p>';
        } else {
            ?>
            <h2>BECOME A BOUNTY HUNTER</h2>
            <p><span>say something nice on <a style="cursor: pointer;" id="shareBtn">facebook</a></span></p>
            <p><span>about <span class="logo"></span> and earn <span class="reward">20</span> in rewards</span></p>
        <?php } ?>
    </div>
    <div class="action-holder">
        <span class="info">you earned <span>$<?php echo (get_user_meta($current_user->ID, 'fb_shared', 1) == 'yes') ? '20' : '0' ?></span> </span>
        <?php
        if (get_user_meta($current_user->ID, 'fb_shared', 1) != 'yes') {
            echo '<a style="cursor: pointer" id="fblogin" class="btn-facebook">go to login</a>';
        } ?>
    </div>
</section><!-- end .cta-rewards -->

<?php $youtube_subscribed = is_subscribed_youtube(); ?>
<section class="cta-rewards">
    <div class="container block-subscribe-youtube">
        <?php if (!$youtube_subscribed): ?>
            <p>Subscribe to our YouTube Channel now and instantly earn $10 in your rewards wallet!</p>
            <a id="btnSubscribeYoutube" class="btn-subscribe-youtube" href="#">Subscribe<img
                        src="<?php echo get_template_directory_uri(); ?>/images/subscribe-youtube.png"
                        alt="subscribe youtube"></a>
        <?php else: ?>
            <p>Thanks for subscribing to our <a style="color: #d73e1f;"
                                                href="https://www.youtube.com/channel/UCGn9KdqacVzcc7-N7Oznc3w?sub_confirmation=1"
                                                target="_blank">YouTube Channel</a>!</p>
        <?php endif; ?>
    </div>
</section>
<?php if (!$youtube_subscribed): ?>
    <script defer async>
      // youtube subscription
      (function (window, document, $, gapi) {
        'use strict';

        var GoogleAuth;
        var SCOPE = 'https://www.googleapis.com/auth/youtube';
        var apiKey = '<?php echo carbon_get_theme_option('_google_apis_key'); ?>';
        var clientId = '<?php echo carbon_get_theme_option('_google_oauth_client_id'); ?>';

        // Load the API's client and auth2 modules.
        // Call the initClient function after the modules load.
        gapi.load('client:auth2', initClient);

        function initClient() {
          // Retrieve the discovery document for version 3 of Google Drive API.

          // In practice, your app can retrieve one or more discovery documents.
          var discoveryUrl = 'https://www.googleapis.com/discovery/v1/apis/drive/v3/rest';

          // Initialize the gapi.client object, which app uses to make API requests.
          // Get API key and client ID from API Console.
          // 'scope' field specifies space-delimited list of access scopes.
          gapi.client.init({
            'apiKey': apiKey,
            'clientId': clientId,
            'scope': SCOPE,
            'discoveryDocs': [discoveryUrl],
          }).then(function () {
            GoogleAuth = gapi.auth2.getAuthInstance();

            // Listen for sign-in state changes.
            GoogleAuth.isSignedIn.listen(updateSigninStatus);

            // on click subscribe button
            document.getElementById('btnSubscribeYoutube').onclick = function (e) {
              e.preventDefault();

              if (GoogleAuth.isSignedIn.get()) {
                subscribeYoutube();
              } else {
                GoogleAuth.signIn();
              }
            };
          });
        }

        function updateSigninStatus() {
          // Handle initial sign-in state. (Determine if user is already signed in.)

          if (!GoogleAuth.isSignedIn.get()) {
            return;
          }

          subscribeYoutube();
        }

        function subscribeYoutube() {
          var user = GoogleAuth.currentUser.get();
          // window.youtubeUser = user;
          // console.log(user);
          $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            data: {
              action: 'subscribe_youtube',
              token: user.getAuthResponse().access_token,
            },
            success: function (data) {
              var $walletValue = $('.wallet-info-holder .value');
              var cur_rewards = $walletValue.html().split('$');
              var new_points = parseFloat(cur_rewards[1]) + 10;
              $walletValue.html('$' + new_points);
              $('.block-subscribe-youtube').html('<p>Thanks for subscribing to our <a style="color: #d73e1f;" href="https://www.youtube.com/channel/UCGn9KdqacVzcc7-N7Oznc3w?sub_confirmation=1" target="_blank">YouTube Channel</a>!</p>');
            }
          });
        }

      })(window, document, jQuery, gapi);
    </script>
<?php endif; ?>

<script>
  // facebook
  window.fbAsyncInit = function () {
    FB.init({
      appId: '2488123694780236',
      autoLogAppEvents: true,
      xfbml: true,
      version: 'v2.5'
    });
    document.getElementById('shareBtn').onclick = function () {
      FB.ui({
        method: 'feed',
        caption: 'AudioPlugin.Deals',
        link: '<?php echo get_permalink(get_page_by_path('shop'))?>',
      }, function (response) {
        var rep = response;
        jQuery.ajax({
          type: "POST",
          url: '/wp-admin/admin-ajax.php',
          data: {"action": "facebook", "data": rep},
          success: function (data) {
            jQuery('.info span').html('$20');
            var cur_rewards = jQuery('.wallet-info-holder .value').html().split('$');
            var new_points = parseFloat(cur_rewards[1]) + 20;
            jQuery('.wallet-info-holder .value').html('$' + new_points);
          }
        });
      });
    };

    document.getElementById('fblogin').onclick = function () {
      FB.ui({
        method: 'feed',
        caption: 'AudioPlugin.Deals',
        link: '<?php echo get_permalink(get_page_by_path('shop'))?>',
      }, function (response) {
        var rep = response;
        jQuery.ajax({
          type: "POST",
          url: '/wp-admin/admin-ajax.php',
          data: {"action": "facebook", "data": rep},
          success: function (data) {
            jQuery('.info span').html('$20');
            var cur_rewards = jQuery('.wallet-info-holder .value').html().split('$');
            var new_points = parseFloat(cur_rewards[1]) + 20;
            jQuery('.wallet-info-holder .value').html('$' + new_points);
          }
        });
      });
    }
  };

  (function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
      return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
 <div class="hot-deals-footer" style="margin-top:30px;">
        <?php
        $id = 256167;
        $post = get_post($id);
        $content = apply_filters('the_content', $post->post_content);
        echo $content;
        ?>

    </div>
<?php get_footer('shop'); ?>