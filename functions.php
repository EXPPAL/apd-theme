<?php
/**
 * Twenty Twelve functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development and
 * https://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link https://codex.wordpress.org/Plugin_API
 *
 * @package    WordPress
 * @subpackage Twenty_Twelve
 * @since      Twenty Twelve 1.0
 */

// Set up the content width value based on the theme's design and stylesheet.

include_once(get_stylesheet_directory() . '/inc/fix_get_total_spent.php');

if (!isset($content_width)) {
    $content_width = 625;
}

/**
 * check if rewards is being disabled and the site is offering base price
 *
 * @param int $product_id
 *
 * @return bool
 */
function is_disable_rewards_and_offer_base_price($product_id = 0) {
    $disable_rewards_and_offer_base_price = carbon_get_theme_option('_disable_rewards_and_offer_base_price');

    if ($disable_rewards_and_offer_base_price === 'yes') {
        return true;
    }

    if ($product_id) {
        $disable_rewards_and_offer_base_price_for_custom_products = carbon_get_theme_option('_disable_rewards_and_offer_base_price_for_custom_products');
        $disable_rewards_and_offer_base_price_for_custom_products = is_array($disable_rewards_and_offer_base_price_for_custom_products) ? wp_list_pluck($disable_rewards_and_offer_base_price_for_custom_products, 'id') : array();

        return in_array($product_id, $disable_rewards_and_offer_base_price_for_custom_products);
    }

    return false;
}

/**
 * return product price
 * @param \WC_Product $product
 * @return double
 */
function apd_get_product_price($product) {
    if (is_disable_rewards_and_offer_base_price($product->get_id())) {
        return get_post_meta($product->get_id(), '_minumum_price', true);
    }

    $sale = $product->get_sale_price();
    if ($sale) {
        return $sale;
    }

    return $product->get_regular_price();
}

/**
 * Twenty Twelve setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses  load_theme_textdomain() For translation/localization support.
 * @uses  add_editor_style() To add a Visual Editor stylesheet.
 * @uses  add_theme_support() To add support for post thumbnails, automatic feed links,
 *    custom background, and post formats.
 * @uses  register_nav_menu() To add support for navigation menus.
 * @uses  set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup() {
    /*
     * Makes Twenty Twelve available for translation.
     *
     * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentytwelve
     * If you're building a theme based on Twenty Twelve, use a find and replace
     * to change 'twentytwelve' to the name of your theme in all the template files.
     */
    load_theme_textdomain('twentytwelve');

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // Adds RSS feed links to <head> for posts and comments.
    add_theme_support('automatic-feed-links');

    // This theme supports a variety of post formats.
    add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'status'));

    // This theme uses wp_nav_menu() in one location.
    register_nav_menu('primary', __('Primary Menu', 'twentytwelve'));

    /*
     * This theme supports custom background color and image,
     * and here we also set up the default background color.
     */
    add_theme_support('custom-background', array(
        'default-color' => 'e6e6e6',
    ));

    // This theme uses a custom image size for featured images, displayed on "standard" posts.
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(624, 9999); // Unlimited height, soft crop

    // Indicate widget sidebars can use selective refresh in the Customizer.
    //add_theme_support( 'customize-selective-refresh-widgets' );

    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'twentytwelve_setup');

/**
 * Add support for a custom header image.
 */
require(get_template_directory() . '/inc/custom-header.php');

/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @return string Font stylesheet or empty string if disabled.
 * @since Twenty Twelve 1.2
 *
 */
function twentytwelve_get_font_url() {
    $font_url = '';

    /* translators: If there are characters in your language that are not supported
     * by Open Sans, translate this to 'off'. Do not translate into your own language.
     */
    if ('off' !== _x('on', 'Open Sans font: on or off', 'twentytwelve')) {
        $subsets = 'latin,latin-ext';

        /* translators: To add an additional Open Sans character subset specific to your language,
         * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
         */
        $subset = _x('no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'twentytwelve');

        if ('cyrillic' == $subset) {
            $subsets .= ',cyrillic,cyrillic-ext';
        } elseif ('greek' == $subset) {
            $subsets .= ',greek,greek-ext';
        } elseif ('vietnamese' == $subset) {
            $subsets .= ',vietnamese';
        }

        $query_args = array(
            'family' => 'Open+Sans:400italic,700italic,400,700',
            'subset' => $subsets,
        );
        $font_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    return $font_url;
}

/**
 * Enqueue scripts and styles for front end.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_scripts_styles() {
    global $wp_styles;

    /*
     * Adds JavaScript to pages with the comment form to support
     * sites with threaded comments (when in use).
     */
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    $page_name = get_query_var('pagename');
    // Adds JavaScript for handling the navigation menu hide-and-show behavior.
    wp_enqueue_script('twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20140711', true);
    wp_enqueue_script('apd-shop', get_template_directory_uri() . '/js/bundle.js', array('jquery'), '', true);
    if ($page_name != 'wishlist') {
        wp_enqueue_script('apd-rewards', get_template_directory_uri() . '/js/rewards.js', array('jquery'), '240', true);
    }

    wp_enqueue_script('apd-mask', get_template_directory_uri() . '/js/jquery.maskMoney.min.js', array('jquery'), '', true);
    $font_url = twentytwelve_get_font_url();
    if (!empty($font_url)) {
        wp_enqueue_style('twentytwelve-fonts', esc_url_raw($font_url), array(), null);
    }

    if (get_query_var('pagename') == 'cart') {
        wp_enqueue_style('apd-karam', get_template_directory_uri() . '/css/karam.css', array(), '');
    }

    // Loads our main stylesheet.
    wp_enqueue_style('twentytwelve-style', get_stylesheet_uri());

    // Loads the Internet Explorer specific stylesheet.
    wp_enqueue_style('twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array('twentytwelve-style'), '20121010');
    $wp_styles->add_data('twentytwelve-ie', 'conditional', 'lt IE 9');

    wp_enqueue_style('bf2019', get_template_directory_uri() . '/bf2019-assets/css/main.css', array('twentytwelve-style'), time());
    wp_enqueue_script('bf2019', get_template_directory_uri() . '/bf2019-assets/js/main-dist.js', array('jquery'), time());

    wp_enqueue_style('bf2020', get_template_directory_uri() . '/bf2019-assets/css/main.css', array('twentytwelve-style'), time());
    wp_enqueue_script('bf2020', get_template_directory_uri() . '/bf2019-assets/js/main-dist.js', array('jquery'), time());
    
    wp_enqueue_style('bf2021', get_template_directory_uri() . '/bf2019-assets/css/main.css', array('twentytwelve-style'), time());
    wp_enqueue_script('bf2021', get_template_directory_uri() . '/bf2019-assets/js/main-dist.js', array('jquery'), time());

    wp_enqueue_style('bf2022', get_template_directory_uri() . '/bf2019-assets/css/main.css', array('twentytwelve-style'), time());
    wp_enqueue_script('bf2022', get_template_directory_uri() . '/bf2019-assets/js/main-dist.js', array('jquery'), time());
    
    wp_enqueue_style('bfchrist', get_template_directory_uri() . '/bf2019-assets/css/main.css', array('twentytwelve-style'), time());
    wp_enqueue_script('bfchrist', get_template_directory_uri() . '/bf2019-assets/js/main-dist.js', array('jquery'), time());

    wp_enqueue_style('rating-system', get_template_directory_uri() . '/css/rating-1.0.2.css', array('twentytwelve-style'), time());
    wp_enqueue_script('rating-system', get_template_directory_uri() . '/js/rating.min.js', array('jquery'), time());

    // google apis
    $google_apis_key = carbon_get_theme_option('_google_apis_key');
    if ($google_apis_key) {
        wp_enqueue_script('google-apis', 'https://apis.google.com/js/api.js');
    }
}

add_action('wp_enqueue_scripts', 'twentytwelve_scripts_styles');

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 *
 * @return string Filtered CSS path.
 * @uses  twentytwelve_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since Twenty Twelve 1.2
 *
 */
function twentytwelve_mce_css($mce_css) {
    $font_url = twentytwelve_get_font_url();

    if (empty($font_url)) {
        return $mce_css;
    }

    if (!empty($mce_css)) {
        $mce_css .= ',';
    }

    $mce_css .= esc_url_raw(str_replace(',', '%2C', $font_url));

    return $mce_css;
}

add_filter('mce_css', 'twentytwelve_mce_css');

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 *
 * @return string Filtered title.
 * @since Twenty Twelve 1.0
 *
 */
function twentytwelve_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed()) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo('name', 'display');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if (($paged >= 2 || $page >= 2) && !is_404()) {
        $title = "$title $sep " . sprintf(__('Page %s', 'twentytwelve'), max($paged, $page));
    }

    return $title;
}

add_filter('wp_title', 'twentytwelve_wp_title', 10, 2);

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args($args) {
    if (!isset($args['show_home'])) {
        $args['show_home'] = true;
    }

    return $args;
}

add_filter('wp_page_menu_args', 'twentytwelve_page_menu_args');

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {
    register_sidebar(array(
        'name' => __('Main Sidebar', 'twentytwelve'),
        'id' => 'sidebar-1',
        'description' => __('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('First Front Page Widget Area', 'twentytwelve'),
        'id' => 'sidebar-2',
        'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Second Front Page Widget Area', 'twentytwelve'),
        'id' => 'sidebar-3',
        'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Post Archive Sidebar', 'twentytwelve'),
        'id' => 'sidebar-4',
        'description' => __('Appears on posts home', 'twentytwelve'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'twentytwelve_widgets_init');

if (!function_exists('twentytwelve_content_nav')) :
    /**
     * Displays navigation to next/previous pages when applicable.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_content_nav($html_id) {
        global $wp_query;

        if ($wp_query->max_num_pages > 1) : ?>
            <nav id="<?php echo esc_attr($html_id); ?>" class="navigation" role="navigation">
                <h3 class="assistive-text"><?php _e('Post navigation', 'twentytwelve'); ?></h3>
                <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve')); ?></div>
                <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve')); ?></div>
            </nav><!-- .navigation -->
        <?php endif;
    }
endif;

if (!function_exists('twentytwelve_comment')) :
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own twentytwelve_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                <p><?php _e('Pingback:', 'twentytwelve'); ?><?php comment_author_link(); ?><?php edit_comment_link(__('(Edit)', 'twentytwelve'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default :
                // Proceed with normal comments.
                global $post;
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <article id="comment-<?php comment_ID(); ?>" class="comment">
                    <header class="comment-meta comment-author vcard">
                        <?php
                        echo get_avatar($comment, 44);
                        printf('<cite><b class="fn">%1$s</b> %2$s</cite>',
                            get_comment_author_link(),
                            // If current post author is also comment author, make it known visually.
                            ($comment->user_id === $post->post_author) ? '<span>' . __('Post author', 'twentytwelve') . '</span>' : ''
                        );
                        printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                            esc_url(get_comment_link($comment->comment_ID)),
                            get_comment_time('c'),
                            /* translators: 1: date, 2: time */
                            sprintf(__('%1$s at %2$s', 'twentytwelve'), get_comment_date(), get_comment_time())
                        );
                        ?>
                    </header><!-- .comment-meta -->

                    <?php if ('0' == $comment->comment_approved) : ?>
                        <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'twentytwelve'); ?></p>
                    <?php endif; ?>

                    <section class="comment-content comment">
                        <?php comment_text(); ?>
                        <?php edit_comment_link(__('Edit', 'twentytwelve'), '<p class="edit-link">', '</p>'); ?>
                    </section><!-- .comment-content -->

                    <div class="reply">
                        <?php comment_reply_link(array_merge($args, array(
                            'reply_text' => __('Reply', 'twentytwelve'),
                            'after' => ' <span>&darr;</span>',
                            'depth' => $depth,
                            'max_depth' => $args['max_depth']
                        ))); ?>
                    </div><!-- .reply -->
                </article><!-- #comment-## -->
                <?php
                break;
        endswitch; // end comment_type check
    }
endif;

if (!function_exists('twentytwelve_entry_meta')) :
    /**
     * Set up post entry meta.
     *
     * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
     *
     * Create your own twentytwelve_entry_meta() to override in a child theme.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_entry_meta() {
        // Translators: used between list items, there is a space after the comma.
        $categories_list = get_the_category_list(__(', ', 'twentytwelve'));

        // Translators: used between list items, there is a space after the comma.
        $tag_list = get_the_tag_list('', __(', ', 'twentytwelve'));

        $date = sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
            esc_url(get_permalink()),
            esc_attr(get_the_time()),
            esc_attr(get_the_date('c')),
            esc_html(get_the_date())
        );

        $author = sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            esc_attr(sprintf(__('View all posts by %s', 'twentytwelve'), get_the_author())),
            get_the_author()
        );

        // Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
        if ($tag_list) {
            $utility_text = __('This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
        } elseif ($categories_list) {
            $utility_text = __('This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
        } else {
            $utility_text = __('This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
        }

        printf(
            $utility_text,
            $categories_list,
            $tag_list,
            $date,
            $author
        );
    }
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @param array $classes Existing class values.
 *
 * @return array Filtered class values.
 * @since Twenty Twelve 1.0
 *
 */
function twentytwelve_body_class($classes) {
    $background_color = get_background_color();
    $background_image = get_background_image();

    if (!is_active_sidebar('sidebar-1') || is_page_template('page-templates/full-width.php')) {
        $classes[] = 'full-width';
    }

    if (is_page_template('page-templates/front-page.php')) {
        $classes[] = 'template-front-page';
        if (has_post_thumbnail()) {
            $classes[] = 'has-post-thumbnail';
        }
        if (is_active_sidebar('sidebar-2') && is_active_sidebar('sidebar-3')) {
            $classes[] = 'two-sidebars';
        }
    }

    if (empty($background_image)) {
        if (empty($background_color)) {
            $classes[] = 'custom-background-empty';
        } elseif (in_array($background_color, array('fff', 'ffffff'))) {
            $classes[] = 'custom-background-white';
        }
    }

    // Enable custom font class only if the font CSS is queued to load.
    if (wp_style_is('twentytwelve-fonts', 'queue')) {
        $classes[] = 'custom-font-enabled';
    }

    if (!is_multi_author()) {
        $classes[] = 'single-author';
    }

    return $classes;
}

add_filter('body_class', 'twentytwelve_body_class');

/**
 * Adjust content width in certain contexts.
 *
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_width() {
    if (is_page_template('page-templates/full-width.php') || is_attachment() || !is_active_sidebar('sidebar-1')) {
        global $content_width;
        $content_width = 960;
    }
}

add_action('template_redirect', 'twentytwelve_content_width');

/**
 * Register postMessage support.
 *
 * Add postMessage support for site title and description for the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @since Twenty Twelve 1.0
 *
 */
function twentytwelve_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector' => '.site-title > a',
            'container_inclusive' => false,
            'render_callback' => 'twentytwelve_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector' => '.site-description',
            'container_inclusive' => false,
            'render_callback' => 'twentytwelve_customize_partial_blogdescription',
        ));
    }
}

add_action('customize_register', 'twentytwelve_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 * @see   twentytwelve_customize_register()
 *
 * @since Twenty Twelve 2.0
 */
function twentytwelve_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 * @see   twentytwelve_customize_register()
 *
 * @since Twenty Twelve 2.0
 */
function twentytwelve_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_customize_preview_js() {
    wp_enqueue_script('twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array('customize-preview'), '20141120', true);
}

add_action('customize_preview_init', 'twentytwelve_customize_preview_js');

add_filter('wp_nav_menu_items', 'sk_wcmenucart', 10, 2);
function sk_wcmenucart($menu, $args) {
    // Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || 'Account Menu' !== $args->menu) {
        return $menu;
    }
    ob_start();
    global $woocommerce;
    $viewing_cart = __('View your shopping cart', 'your-theme-slug');
    $start_shopping = __('Start shopping', 'your-theme-slug');
    $current_user = wp_get_current_user();
    $menu_item = '';

    $cart_url = wc_get_cart_url();
    $shop_page_url = get_permalink(wc_get_page_id('shop'));
    $cart_contents_count = $woocommerce->cart->cart_contents_count;
    $cart_contents = sprintf(_n('%d item', '%d items', $cart_contents_count, 'your-theme-slug'), $cart_contents_count);
    $cart_total = $woocommerce->cart->get_cart_total();
    // Uncomment the line below to hide nav menu cart item when there are no items in the cart
    // if ( $cart_contents_count > 0 ) {
    if ($cart_contents_count == 0) {
        $menu_item .= '<li class="right" data-icon="cart"><a  class="wcmenucart-contents" href="' . $cart_url . '" title="' . $start_shopping . '">';
    } else {
        $menu_item .= '<li class="right" data-icon="cart"><a  class="wcmenucart-contents" href="' . $cart_url . '" title="' . $viewing_cart . '">';
    }

    $menu_item .= '';

    $menu_item .= 'Cart (' . $cart_contents_count . ')';
    $menu_item .= '</a></li>';
    // Uncomment the line below to hide nav menu cart item when there are no items in the cart
    // }
    if ($current_user->ID) {
        //$reward_points = (get_user_meta($current_user->ID, '_reward_points', true)) ? get_user_meta($current_user->ID, '_reward_points', true) : 0;
        $menu_item .= '<li class="right" data-icon="wishlist"><a  href="' . get_permalink(get_page_by_path("wishlist")) . '">Wishlist</a></li>';
        //$menu_item .= '<li class="right" data-icon="wallet"><a  href="' . get_permalink(get_page_by_path('wallet')) . '" id="' . uniqid() . '" class="your_rewards">Your rewards: $' . number_format($reward_points, 2, '.', '') . '</a></li>';
    }
    echo $menu_item;
    $social = ob_get_clean();

    return $menu . $social;
}

//add_action('woocommerce_created_customer', 'admin_email_on_registration');
function admin_email_on_registration() {
    $user_id = get_current_user_id();
    wp_new_user_notification($user_id);
}

//add_filter( 'woocommerce_payment_complete_order_status', 'virtual_order_payment_complete_order_status', 10, 2 );

function virtual_order_payment_complete_order_status($order_status, $order_id) {
    $order = new WC_Order($order_id);

    if ('processing' == $order_status &&
        ('on-hold' == $order->status || 'pending' == $order->status || 'failed' == $order->status)
    ) {
        $virtual_order = null;

        if (count($order->get_items()) > 0) {
            foreach ($order->get_items() as $item) {
                if ('line_item' == $item['type']) {
                    $_product = $order->get_product_from_item($item);

                    if (!$_product->is_virtual()) {
                        // once we've found one non-virtual product we know we're done, break out of the loop
                        $virtual_order = false;
                        break;
                    } else {
                        $virtual_order = true;
                    }
                }
            }
        }

        // virtual order, mark as completed
        if ($virtual_order) {
            return 'completed';
        }
    }

    // non-virtual order, return original status
    return $order_status;
}

// Add Shortcode
function LTO_Company_Name($item_id, $item, $order) {
    do_action('woocommerce_order_item_meta_start', $item_id, $item, $order);
    $order->display_item_meta($item);
    $company_name = get_field('lto_company_name', $item['product_id']);

    return $company_name;
}

add_shortcode('LTO_Company_Name', 'LTO_Company_Name');

function company_url($item_id, $item, $order) {
    do_action('woocommerce_order_item_meta_start', $item_id, $item, $order);
    $order->display_item_meta($item);
    $company_url = get_field('url', $item['product_id']);

    return $company_url;
}

add_shortcode('LTO_Company_URL', 'company_url');
// Empty cart message
add_filter('wc_empty_cart_message', 'custom_wc_empty_cart_message');

function custom_wc_empty_cart_message() {
    return 'Your cart is currently empty!';
}

// Tracking Code to get order value for Adroll
add_action('woocommerce_thankyou', 'my_custom_tracking');

function my_custom_tracking($order_id) {
    // Lets grab the order
    $order = wc_get_order($order_id);

    /**
     * Put your tracking code here
     * You can get the order total etc e.g. $order->get_total();
     */

    // This is the order total
    $order->get_total();

    // This is how to grab line items from the order
    $line_items = $order->get_items();

    // This loops over line items
    foreach ($line_items as $item) {
        // This will be a product
        $product = $order->get_product_from_item($item);

        // This is the products SKU
        $sku = $product->get_sku();

        // This is the qty purchased
        $qty = $item['qty'];

        // Line item total cost including taxes and rounded
        $total = $order->get_line_total($item, true, true);

        // Line item subtotal (before discounts)
        $subtotal = $order->get_line_subtotal($item, true, true);
    }
}

function get_product_rewards_percentage($product_id) {
    // custom rewards earning event active
    $is_global_rewards_active = carbon_get_theme_option('_active_global_reward_percentage');
    if ($is_global_rewards_active == 'yes') {
        $rewards_percentage = carbon_get_theme_option('_global_reward_percentage');
        return intval($rewards_percentage);
    }

    // no event
    $rewards_percentage = get_post_meta($product_id, '_reward_points', true);
    return intval($rewards_percentage);
}

function apd_add_product_type_field() {
    global $woocommerce, $post;
    echo '<div class="options_group">';

    woocommerce_wp_checkbox(
        array(
            'id' => '_product_type_single',
            'label' => __('Shop Product', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Check this field if the product should appear in the Shop section', 'woocommerce')
        ));

    woocommerce_wp_checkbox(
        array(
            'id' => '_product_big_deal',
            'label' => __('Big Deal Product', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Check this field for the big deal product', 'woocommerce')
        ));
    
    woocommerce_wp_checkbox(
        array(
            'id' => '_product_air_drop',
            'label' => __('Air Drop Product', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Check this field for the air drop product', 'woocommerce')
        ));

    woocommerce_wp_text_input(
        array(
            'id' => '_reward_points',
            'label' => __('Reward points value %', 'woocommerce'),
            'placeholder' => '',
            'description' => __('Enter the reward points value in percents', 'woocommerce'),
            'type' => 'number',
            'value' => (get_post_meta($post->ID, '_reward_points', true)) ? get_post_meta($post->ID, '_reward_points', true) : 10,
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => '_minumum_price',
            'label' => __('Minimum price', 'woocommerce'),
            'placeholder' => '',
            'description' => __('Enter the minumum price', 'woocommerce'),
            'type' => 'number',
            'value' => (get_post_meta($post->ID, '_minumum_price', true)) ? get_post_meta($post->ID, '_minumum_price', true) : 0,
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => '_redeem_link',
            'label' => __('Redeem link', 'woocommerce'),
            'placeholder' => '',
            'description' => __('Enter the redeem link', 'woocommerce'),
            'type' => 'text',
            'value' => (get_post_meta($post->ID, '_redeem_link', true)) ? get_post_meta($post->ID, '_redeem_link', true) : '',
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => '_blue_light_sort',
            'label' => __('Blue Light Special sort', 'woocommerce'),
            'placeholder' => '',
            'description' => __('Enter the ordering value. The lower the number the higher the position', 'woocommerce'),
            'type' => 'number',
            'value' => (get_post_meta($post->ID, '_blue_light_sort', true)) ? get_post_meta($post->ID, '_blue_light_sort', true) : 0,
            'custom_attributes' => array(
                'step' => 'any',
            )
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => '_trending_sort',
            'label' => __('Trending sort', 'woocommerce'),
            'placeholder' => '',
            'description' => __('Enter the ordering value. The lower the number the higher the position', 'woocommerce'),
            'type' => 'number',
            'value' => (get_post_meta($post->ID, '_trending_sort', true)) ? get_post_meta($post->ID, '_trending_sort', true) : 0,
            'custom_attributes' => array(
                'step' => 'any',
            )
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => '_new_arrivals_sort',
            'label' => __('New arrivals sort', 'woocommerce'),
            'placeholder' => '',
            'description' => __('Enter the ordering value. The lower the number the higher the position', 'woocommerce'),
            'type' => 'number',
            'value' => (get_post_meta($post->ID, '_new_arrivals_sort', true)) ? get_post_meta($post->ID, '_new_arrivals_sort', true) : 0,
            'custom_attributes' => array(
                'step' => 'any',
            )
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => '_gift_certificates_sort',
            'label' => __('Gift Certificates sort', 'woocommerce'),
            'placeholder' => '',
            'description' => __('Enter the ordering value. The lower the number the higher the position', 'woocommerce'),
            'type' => 'number',
            'value' => (get_post_meta($post->ID, '_gift_certificates_sort', true)) ? get_post_meta($post->ID, '_gift_certificates_sort', true) : 0,
            'custom_attributes' => array(
                'step' => 'any',
            )
        )
    );

    echo '</div>';
}

add_action('woocommerce_product_options_general_product_data', 'apd_add_product_type_field');

function apd_save_product_type_value($post_id) {
    $product_type = isset($_POST['_product_type_single']) ? 'yes' : 'no';
    update_post_meta($post_id, '_product_type_single', $product_type);

    $product_type = isset($_POST['_product_big_deal']) ? 'yes' : 'no';
    update_post_meta($post_id, '_product_big_deal', $product_type);
      
    $is_airdrop_product = isset($_POST['_product_air_drop']) ? 'yes' : 'no';
    update_post_meta($post_id, '_product_air_drop', $is_airdrop_product);
    

    $reward_points = $_POST['_reward_points'];

    if (!empty($reward_points)) {
        update_post_meta($post_id, '_reward_points', esc_attr($reward_points));
    }

    $min_price = $_POST['_minumum_price'];

    if (!empty($min_price)) {
        update_post_meta($post_id, '_minumum_price', esc_attr($min_price));
    }

    $redeem_link = $_POST['_redeem_link'];

    if (!empty($redeem_link)) {
        update_post_meta($post_id, '_redeem_link', esc_attr($redeem_link));
    }

    $blue_light = $_POST['_blue_light_sort'];

    update_post_meta($post_id, '_blue_light_sort', esc_attr($blue_light));

    $trending = $_POST['_trending_sort'];

    update_post_meta($post_id, '_trending_sort', esc_attr($trending));

    $new_arrivals = $_POST['_new_arrivals_sort'];

    update_post_meta($post_id, '_new_arrivals_sort', esc_attr($new_arrivals));

    $gift_certificates = $_POST['_gift_certificates_sort'];

    update_post_meta($post_id, '_gift_certificates_sort', esc_attr($gift_certificates));
}

add_action('woocommerce_process_product_meta', 'apd_save_product_type_value');

function apd_user_rewards_diplay_column($rewards) {
    $rewards['reward_points'] = 'Reward Points';

    return $rewards;
}

add_filter('user_rewards', 'apd_user_rewards_diplay_column', 10, 1);

function apd_rewards_field_user_table($column) {
    $column['reward_points'] = 'Reward Points';

    return $column;
}

add_filter('manage_users_columns', 'apd_rewards_field_user_table');

function apd_rewards_field_retrieve_value($val, $column_name, $user_id) {
    switch ($column_name) {
        case 'reward_points' :
            $reward_points = get_the_author_meta('_reward_points', $user_id);

            return ($reward_points) ? $reward_points : '0';
            break;
        default:
    }

    return $val;
}

add_filter('manage_users_custom_column', 'apd_rewards_field_retrieve_value', 10, 3);

function apd_fill_rewards_button($wp_admin_bar) {
    $args = array(
        'id' => 'fill-rewards-button',
        'title' => 'Fill Rewards',
        'href' => '/wp-admin/users.php?fill_rewards=all',

    );
    $wp_admin_bar->add_node($args);
}

add_action('admin_bar_menu', 'apd_fill_rewards_button', 10000);

function apd_fill_rewards_action() {
    if (isset($_GET['fill_rewards']) && $_GET['fill_rewards'] == 'all' && get_option('_filled_rewards') != 1) {
        $customers = get_users();
        if (count($customers) > 0) {
            $total = 0;
            foreach ($customers as $customer) {
                $total = wc_get_customer_total_spent($customer->ID);
                update_user_meta($customer->ID, '_reward_points', number_format(($total / 10), 2, '.', ''));
                $total = 0;
            }
        }
        update_site_option('_filled_rewards', 1);
    } else {
        return;
    }
}

add_action('admin_init', 'apd_fill_rewards_action');

function apd_reward_points_edit_user_profile($user) { ?>

    <h3>Edit customer's reward points</h3>

    <table class="form-table">

        <tr>
            <th><label for="twitter">Reward Points</label></th>

            <td>
                <input type="text" name="reward_points" id="reward_points"
                       value="<?php echo esc_attr(get_the_author_meta('_reward_points', $user->ID)); ?>"
                       class="regular-text" placeholder="10"/><br/>
                <span class="description">Please enter customer's reward points</span>
            </td>
        </tr>

    </table>
<?php }

add_action('show_user_profile', 'apd_reward_points_edit_user_profile');
add_action('edit_user_profile', 'apd_reward_points_edit_user_profile');

function apd_reward_points_save($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    update_user_meta($user_id, '_reward_points', $_POST['reward_points']);
}

add_action('personal_options_update', 'apd_reward_points_save');
add_action('edit_user_profile_update', 'apd_reward_points_save');

add_filter('bulk_actions-users', 'apd_register_reward_points_bulk_actions');

function apd_register_reward_points_bulk_actions($bulk_actions) {
    $bulk_actions['update_rewards'] = __('Update reward points', 'update_rewards');

    return $bulk_actions;
}

add_action('admin_footer', 'apd_mass_update_rewards_field');

function apd_mass_update_rewards_field() {
    $screen = get_current_screen();
    if ($screen->id != "users")   // Only add to users.php page
    {
        return;
    }
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function ($) {
        $('.tablenav.top .clear, .tablenav.bottom .clear').before('<div class="alignleft actions"><label for="mass_reward_point">Mass update reward point: </label><input type="text" id="mass_reward_point" name="mass_reward_point" size="1" value=""/></div>');
        var form = $('#mass_reward_point').closest("form");
        $(form).on('submit', function (e) {
          var val = $('#mass_reward_point').val();
          $(form).append('<input type="hidden" name="mass_reward_points" value="' + val + '"/>');
        });
      });

    </script>
    <?php
}

function apd_mass_update_rewards($redirect_to, $action_name, $post_ids) {
    if ($action_name == 'update_rewards' && $_REQUEST['mass_reward_points'] > 0) {
        foreach ($_REQUEST['users'] as $user) {
            update_user_meta($user, '_reward_points', $_REQUEST['mass_reward_points']);
        }
    }

    return $redirect_to;
}

add_filter('handle_bulk_actions-users', 'apd_mass_update_rewards', 10, 3);


function apd_product_custom_price( $cart_item_data, $product_id, $variation_id, $quantity ) { 
    $current_user = wp_get_current_user();
    
    $product = wc_get_product($product_id);
    
    if ( $product && $product->is_in_stock() ) {
        $cart_item_data['use_rewards'] = 0;
        $unique_cart_item_key = md5(microtime() . rand());
        $cart_item_data['unique_key'] = $unique_cart_item_key;
        if (isset($_POST['use_rewards']) && $_POST['use_rewards'] >= 0 && !empty($_POST['use_rewards'])) {
            if ((float)get_user_meta($current_user->ID, '_reward_points', true) >= $_POST['use_rewards']) {
                $cart_item_data['use_rewards'] += (float)$_POST['use_rewards'];
                $bonus_points = (float)get_user_meta($current_user->ID, 'temp_reward_points', true);
                if ($bonus_points > 0 && $_POST['use_rewards'] <= $bonus_points) {
                    $bonus_points = $bonus_points - $_POST['use_rewards'];
                } elseif ($bonus_points > 0 && $_POST['use_rewards'] > $bonus_points) {
                    $bonus_points = 0;
                }
                $current_points = (float)get_user_meta($current_user->ID, '_reward_points', true);
                $current_points = number_format($current_points - $_POST['use_rewards'], 2, '.', '');
                update_user_meta($current_user->ID, '_reward_points', $current_points);
                #update_user_meta( $current_user->ID, 'temp_reward_points', $bonus_points );
            }
        }
    }
    else {
        if ( ! $product ) {
            wc_add_notice("Error: product you are adding to cart is not found ( ID = $product_id )", 'notice');
        }
        elseif ( ! $product->is_in_stock() ) {
            $product_name = $product->get_title();
            wc_add_notice("Error: product you are adding to cart is out of stock ( $product_name ), reward money restored", 'notice');
        }
    }
    return $cart_item_data;
}



add_filter('woocommerce_add_cart_item_data', 'apd_product_custom_price', 10, 4);

/**
 * @param \WC_Cart $cart_object
 */
function apd_apply_custom_price_to_cart_item($cart_object) {
    // disable rewards and offer base price
    $disable_rewards_and_offer_base_price = is_disable_rewards_and_offer_base_price();
    if ($disable_rewards_and_offer_base_price) {
        foreach ($cart_object->cart_contents as $key => $cart_item) {
            /**
             * @var \WC_Product $item_product
             */
            $item_product = $cart_item['data'];

            $item_product->set_price(apd_get_product_price($item_product));
        }
        return;
    }

    // rewards
    if (!WC()->session->__isset('reload_checkout')) {
        foreach ($cart_object->cart_contents as $key => $value) {
            if (isset($value['smart_offers'])) continue;

            // rewards
            if ((isset($value['use_rewards']) && $value['use_rewards'] >= 0) || isset($_POST['cart'][$key]['rewards'])) {
                $cart_rewards = (!isset($_POST['cart'])) ? 0 : $_POST['cart'][$key]['rewards'];
                $cart = (isset($_POST['cart'])) ? $_POST['cart'] : array();
                if (array_key_exists($key, $cart) && $value['use_rewards'] != $cart_rewards) {
                    $current_user = wp_get_current_user();
                    $virtual_points = (float)get_user_meta($current_user->ID, '_reward_points', true) + $value['use_rewards'] - $_POST['cart'][$key]['rewards'];
                    if ($virtual_points > 0) {
                        if ($virtual_points >= $_POST['cart'][$key]['rewards']) {
                            $bonus_points = (float)get_user_meta($current_user->ID, 'temp_reward_points', true);
                            if ($bonus_points > 0 && $_POST['cart'][$key]['rewards'] <= $bonus_points) {
                                $bonus_points = $bonus_points - $_POST['cart'][$key]['rewards'];
                            } elseif ($bonus_points > 0 && $_POST['cart'][$key]['rewards'] > $bonus_points) {
                                $bonus_points = 0;
                            }
                            $current_points = number_format($virtual_points, 2, '.', '');
                            update_user_meta($current_user->ID, '_reward_points', $current_points);
                            #update_user_meta( $current_user->ID, 'temp_reward_points', $bonus_points );
                        } else {
                            update_user_meta($current_user->ID, '_reward_points', $virtual_points + $_POST['cart'][$key]['rewards']);
                        }
                        $cart_object->cart_contents[$key]['use_rewards'] = $_POST['cart'][$key]['rewards'];
                        $value['use_rewards'] = $_POST['cart'][$key]['rewards'];
                    }
                }
                WC()->session->set(
                    $value['product_id'], array(
                        'use_rewards' => $value['use_rewards']
                    )
                );

                $_pf = new WC_Product_Factory();
                $product = $_pf->get_product($value['product_id']);
                if (!$product->is_type('variable')) {
                    if ($value['quantity'] > 1) {
                        $price = $product->get_price() - (float)$value['use_rewards'] / $value['quantity'];
                    } else {
                        $price = $product->get_price() - (float)$value['use_rewards'];
                    }

                    $price = round($price, 2);

                    if ($price >= (float)get_post_meta($value['product_id'], '_minumum_price', true)) {
                        $value['data']->set_price($price);
                    } else {
                        $cart_object->cart_contents[$key]['use_rewards'] = 0;
                    }
                }
            }
        }
    }
}

add_action('woocommerce_before_calculate_totals', 'apd_apply_custom_price_to_cart_item');

function get_cart_items_from_session($item, $values, $key) {
    if (array_key_exists('use_rewards', $values)) {
        $item['use_rewards'] = $values['use_rewards'];
    }

    return $item;
}

add_filter('woocommerce_get_cart_item_from_session', 'get_cart_items_from_session', 1, 3);

function apd_store_rewards_to_order_meta($item_id, $item_values, $item_key) {
    $developer_id = '';
    $developer_name = '';
    $developer_email = '';
    $session_data = WC()->session->get($item_values['product_id']);
    if (!empty($session_data['use_rewards'])) {
        wc_update_order_item_meta($item_id, '_used_rewards', sanitize_text_field($session_data['use_rewards']));
    }
    if (get_post_meta($item_values['product_id'], '_product_type_single', true) == 'yes') {
        $objects = wp_get_post_terms($item_values['product_id'], 'developer');
        if (count($objects) > 0) {
            $developer_id = $objects[0]->term_id;
            $developer_name = $objects[0]->name;
            $developer_email = get_term_meta($developer_id, 'developer_email', true);
        }
        wc_update_order_item_meta($item_id, 'shop_product', 1);
        wc_update_order_item_meta($item_id, 'developer_name', $developer_name);
        wc_update_order_item_meta($item_id, 'developer_email', $developer_email);
    } elseif (get_post_meta($item_values['product_id'], '_product_big_deal', true) == 'yes') {
        wc_update_order_item_meta($item_id, 'bigdeal', 1);
    } else {
        wc_update_order_item_meta($item_id, 'deal_product', 1);
    }
}

add_action('woocommerce_new_order_item', 'apd_store_rewards_to_order_meta', 1, 3);

add_action('woocommerce_checkout_update_order_meta', function ($order_id, $posted) {
    $order = wc_get_order($order_id);
    $shop_product = false;
    $deal_product = false;
    $big_deal_product = false;
    $order->update_meta_data('order_type', 'mixed');
    $items = $order->get_items();
    foreach ($items as $key => $item) {
        if (get_post_meta($item['product_id'], '_product_type_single', true) == 'yes') {
            $shop_product = true;
        } elseif (get_post_meta($item['product_id'], '_product_big_deal', true) == 'yes') {
            $big_deal_product = true;
        } else {
            $deal_product = true;
        }
    }

    if ($shop_product && !$deal_product && !$big_deal_product) {
        $order->update_meta_data('order_type', 'shop');
    }

    if ($big_deal_product && !$shop_product && !$deal_product) {
        $order->update_meta_data('order_type', 'bigdeal');
    }

    if ($deal_product && !$shop_product && !$big_deal_product) {
        $order->update_meta_data('order_type', 'deal');
    }

    $order->save();
}, 10, 2);

function apd_order_completed($order_id) {
    global $woocommerce;

    $order = wc_get_order($order_id);
    $customer_id = $order->get_user_id();
    $order_items = $order->get_items();
    $current_points = get_user_meta($customer_id, '_reward_points', true);
    $developers = array();
    $earned = 0;
    foreach ($order_items as $id => $item) {
        $item_total = wc_get_order_item_meta($id, '_line_total', true);
        // $reward_percentage = get_post_meta($item['product_id'], '_reward_points', true);
        $reward_percentage = get_product_rewards_percentage($item['product_id']);
        $earned += $item_total * $reward_percentage / 100;
        $current_points = number_format($current_points + $item_total * $reward_percentage / 100, 2, '.', '');
        //wc_update_order_item_meta( $id, '_used_rewards', '0' );
        if (get_post_meta($item['product_id'], '_product_type_single', true) == 'yes') {
            $developers[] = array(
                'name' => $item['name'],
                'developer_name' => $item['developer_name'],
                'developer_email' => $item['developer_email'],
                'product_price' => $item_total,
                'rewards_used' => wc_get_order_item_meta($id, '_used_rewards', true)
            );
        }
    }
    update_user_meta($customer_id, '_reward_points', $current_points);
    $mailer = $woocommerce->mailer();
    add_filter('woocommerce_email_styles', 'apd_woocommerce_email_styles');
    $image = '<img class="header_logo" src="' . esc_url(get_template_directory_uri()) . '/images/shop/logo_shop.png"/>';
    if (!empty($developers)) {
        foreach ($developers as $developer) {
            $email_message = 'Hello!<br/>
            Your item has been sold! Here is the details below:
            Item name: ' . $developer['name'] . '<br/>
            Price: $' . $developer['product_price'] . '<br/>
            Rewards used: $' . $developer['rewards_used'] . '<br/><br/>
            Best Regards,<br/>
            APD Team
            ';
            $message = $mailer->wrap_message(
                sprintf(__('Audio Plugin Deals: Your Item Has Been Sold!'), $order->get_order_number()), $email_message);
            $message = str_replace('[header_image]', $image, $message);
            if (get_post_meta($order_id, 'order_type', true) == 'shop') {
                $message = str_replace('#d73e1f', '#2773c9', $message);
            }
            $message = str_replace('#e78b79', '#2773c9', $message);
            $message = str_replace('#d73e1f', '#2773c9', $message);
            $mailer->send($developer['developer_email'], sprintf(__('Audio Plugin Deals: Your Item Has Been Sold!'), $order->get_order_number()), $message);
        }
    }
    $reward_message = 'Dear ' . $order->get_billing_first_name() . '.<br/>
    <b>You\'ve got money!</b><br/><br/>
    From your last order, you earned $' . (float)(floor($earned * 100) / 100) . '!<br/></br>
    <center><img src="' . esc_url(get_template_directory_uri()) . '/images/shop/email_wallet.png"></center>
    <center>Your rewards wallet balance:</center>
    <center><span style="font-size: 24px;"><b><a style="text-decoration:none;color:#339966" href="https://audioplugin.deals/wallet">$' . $current_points . '</a></b></span></center> <br/>
    <span style="text-align: left">Don\'t forget that for every order you make in <a style="color:#d73e1d" href="https://audioplugin.deals/">The Deal</a> section or in <a href="https://audioplugin.deals/shop/">The Shop</a>, you get at least 10% of the amount you spend added to your wallet for future purchases.</span><br/><br/>
    <div style="margin-left: auto;margin-right: auto;float: none;max-width:150px;margin-bottom:20px;"><table border="0" cellpadding="0" cellspacing="0" style="background-color:#2773c9; border:1px solid #353535; border-radius:5px;"><tr><td align="center" valign="middle" style="color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; letter-spacing:-.5px; line-height:150%; padding-top:10px; padding-right:30px; padding-bottom:10px; padding-left:30px;"><a href="https://audioplugin.deals/shop/" target="_blank" style="color:#FFFFFF; text-decoration:none;">Shop Now</a></td></tr></table></div>
    ';
    $msg = $mailer->wrap_message(
        sprintf(__('Audio Plugin Deals - The Shop: Rewards Money Balance'), $order->get_order_number()), $reward_message);

    $msg = str_replace('[header_image]', $image, $msg);
    if (get_post_meta($order_id, 'order_type', true) == 'shop') {
        $msg = str_replace('#e78b79', '#2773c9', $msg);
        $msg = str_replace('#d73e1f', '#2773c9', $msg);
    }

    $mailer->send($order->get_billing_email(), __('Audio Plugin Deals - The Shop: Rewards Money Balance'), $msg);
    remove_filter('woocommerce_email_styles', 'apd_woocommerce_email_styles');
}

function apd_woocommerce_email_styles($css) {
    $css .= "#template_header { background-color: #2773c9; }";
    $css .= "a {color:#2773c9;}";
    $css .= "#credit p{color:#2773c9;} ";
    $css .= ".link{color:#2773c9;}";
    $css .= "h2 {color:#2773c9;}";

    return $css;
}

add_action('woocommerce_order_status_completed', 'apd_order_completed');

function apd_order_cancelled($order_id) {
    $order = wc_get_order($order_id);
    $customer_id = $order->get_user_id();
    $order_items = $order->get_items();
    $current_points = get_user_meta($customer_id, '_reward_points', true);
    $temp_reward_bonus = get_option('temp_rewards_points', true);
    $user_temp_points = get_user_meta($customer_id, 'temp_reward_points', true);
    foreach ($order_items as $id => $item) {
        $used_rewards = wc_get_order_item_meta($id, '_used_rewards', true);
        if ($used_rewards) {
            $current_points = $current_points + $used_rewards;
            if ($used_rewards <= $temp_reward_bonus) {
                $user_temp_points = $user_temp_points + $used_rewards;
            } else {
                $user_temp_points = $temp_reward_bonus;
            }
        } else {
            $current_points = $current_points;
        }
        //wc_update_order_item_meta( $id, '_used_rewards', '0' );
    }
    update_user_meta($customer_id, '_reward_points', number_format($current_points, 2, '.', ''));
    #update_user_meta( $customer_id, 'temp_reward_points', number_format( $user_temp_points, 2, '.', '' ) );
}

add_action('woocommerce_order_status_cancelled', 'apd_order_cancelled');

function apd_order_refunded($order_id) {
    $order = wc_get_order($order_id);
    $customer_id = $order->get_user_id();
    $order_items = $order->get_items();
    $current_points = get_user_meta($customer_id, '_reward_points', true);
    $temp_reward_bonus = get_option('temp_rewards_points', true);
    $user_temp_points = get_user_meta($customer_id, 'temp_reward_points', true);
    foreach ($order_items as $id => $item) {
        $used_rewards = wc_get_order_item_meta($id, '_used_rewards', true);
        if ($used_rewards) {
            $current_points = $current_points + $used_rewards;
            if ($used_rewards <= $temp_reward_bonus) {
                $user_temp_points = $user_temp_points + $used_rewards;
            } else {
                $user_temp_points = $temp_reward_bonus;
            }
        } else {
            $current_points = $current_points;
        }
        //wc_update_order_item_meta( $id, '_used_rewards', '0' );
    }
    update_user_meta($customer_id, '_reward_points', number_format($current_points, 2, '.', ''));
    #update_user_meta( $customer_id, 'temp_reward_points', number_format( $user_temp_points, 2, '.', '' ) );
}

add_action('woocommerce_order_status_refunded', 'apd_order_refunded');

function apd_remove_cart_item($cart_item_key, $cart) {
    if (isset($cart->cart_contents[$cart_item_key]['use_rewards'])) {
        $current_user = wp_get_current_user();
        $current_points = get_user_meta($current_user->ID, '_reward_points', true);
        $temp_reward_bonus = get_option('temp_rewards_points', true);
        $user_temp_points = get_user_meta($current_user->ID, 'temp_reward_points', true);
        $current_points = number_format($current_points + $cart->cart_contents[$cart_item_key]['use_rewards'], 2, '.', '');
        if ($cart->cart_contents[$cart_item_key]['use_rewards'] <= $temp_reward_bonus) {
            $user_temp_points = $user_temp_points + $cart->cart_contents[$cart_item_key]['use_rewards'];
        } else {
            $user_temp_points = $temp_reward_bonus;
        }
        update_user_meta($current_user->ID, '_reward_points', number_format($current_points, 2, '.', ''));
        #update_user_meta( $current_user->ID, 'temp_reward_points', number_format( $user_temp_points, 2, '.', '' ) );
    }
}

add_action('woocommerce_remove_cart_item', 'apd_remove_cart_item', 10, 2);

function apd_developer_register() {
    register_taxonomy('developer', array('product'), array(
        'labels' => array(
            'name' => 'Developers',
            'singular_name' => 'Developers',
            'search_items' => 'Search Developer',
            'popular_items' => 'Popular Developers',
            'all_items' => 'All Developers',
            'parent_item' => 'Parent Developer',
            'parent_item_colon' => 'Parent Developer:',
            'edit_item' => 'Edit Developer',
            'update_item' => 'Update Developer',
            'add_new_item' => 'Add New Developer',
            'new_item_name' => 'New Developer',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'developer'),
    ));
    flush_rewrite_rules();
}

add_action('widgets_init', 'apd_developer_register');

function apd_add_developer_email_field() {
    ?>

    <div class="form-field term-slug-wrap">
        <label><?php _e('Developer email', 'woocommerce'); ?></label>
        <input name="developer_email" id="developer_email" type="text" value="" size="40"/>
        <p>Enter here an email of Developer for email reporting</p>
    </div>
    <div class="form-field term-slug-wrap">
        <label><?php _e('Payout method', 'woocommerce'); ?></label>
        Paypal: <input type="radio" name="developer_payout" value="Paypal">
        Check: <input type="radio" name="developer_payout" value="Check">
        <p>Select preferable payout method for the developer</p>
    </div>
    <?php
}

add_action('developer_add_form_fields', 'apd_add_developer_email_field');

function apd_save_developer_company_email_field($term_id) {
    if (isset($_POST['developer_email'])) {
        update_term_meta($term_id, 'developer_email', $_POST['developer_email']);
    }
    if (isset($_POST['developer_payout'])) {
        update_term_meta($term_id, 'developer_method', $_POST['developer_payout']);
    }
}

add_action('created_developer', 'apd_save_developer_company_email_field', 10, 1);
add_action('edited_developer', 'apd_save_developer_company_email_field', 10, 3);

function apd_edit_lto_developer_email_field($term, $taxonomy) {
    $developer_email = get_term_meta($term->term_id, 'developer_email', true);
    $developer_method = get_term_meta($term->term_id, 'developer_method', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label><?php _e('Developer email', 'woocommerce'); ?></label></th>
        <td>
            <div>
                <input type="text" id="developer_email" name="developer_email" value="<?php echo $developer_email; ?>"/>
            </div>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label><?php _e('Payout method', 'woocommerce'); ?></label></th>
        <td>
            <div>
                Paypal: <input type="radio" name="developer_payout"
                               value="Paypal" <?php echo ($developer_method == 'Paypal') ? 'checked' : '' ?>>
                Check: <input type="radio" name="developer_payout"
                              value="Check" <?php echo ($developer_method == 'Check') ? 'checked' : '' ?>>
            </div>
        </td>
    </tr>
    <?php
}

add_action('developer_edit_form_fields', 'apd_edit_lto_developer_email_field', 10, 2);

function apd_customer_apd_report() {
    global $woocommerce;
    $mailer = $woocommerce->mailer();
    $user = wp_get_current_user();
    $reward_points = get_user_meta($user->ID, '_reward_points', true);
    $reward_message = 'Dear ' . $user->first_name . '.<br/>
    <b>You\'ve got money!</b><br/>
    We would like to let you know you have money in your <a href="https://audioplugin.deals/wallet/">APD Rewards Wallet</a>
    which gives a further discount on a wide array of 60+ award-winning products in <a href="https://audioplugin.deals/shop/">The Shop</a>.<br/>
    <center>You have ' . $reward_points . ' in your rewards wallet!</center> <br/><br/>
    Don\'t forget that for every order you make in <a style="color:#d73e1f" href="https://audioplugin.deals/">The Deal</a> section or in <a href="https://audioplugin.deals/shop/">The Shop</a>, you get at least 10% of the amount you spend added to your wallet for future purchases.
    <br/>
    <br/>
    <a href="https://audioplugin.deals/shop/">Shop Now</a>
    ';
    add_filter('woocommerce_email_styles', 'apd_woocommerce_email_styles');
    $image = '<img class="header_logo" src="' . esc_url(get_template_directory_uri()) . '/images/shop/logo_shop.png"/>';

    $msg = $mailer->wrap_message(
        sprintf(__('Audio Plugin Deals - The Shop: Rewards Money Balance'), ''), $reward_message);
    $msg = str_replace('[header_image]', $image, $msg);
    $msg = str_replace('#e78b79', '#2773c9', $msg);
    $msg = str_replace('#d73e1f', '#2773c9', $msg);
    $mailer->send($user->user_email, __('Audio Plugin Deals - The Shop: Rewards Money Balance'), $msg);
}

//add_action('init','apd_customer_apd_report');

function apd_instrument_type_register() {
    register_taxonomy('instrument_type', array('product'), array(
        'labels' => array(
            'name' => 'Instrument Types',
            'singular_name' => 'Instrument Types',
            'search_items' => 'Search Instrument Type',
            'popular_items' => 'Popular Instrument Types',
            'all_items' => 'All Instrument Types',
            'parent_item' => 'Parent Instrument Type',
            'parent_item_colon' => 'Parent Instrument Type:',
            'edit_item' => 'Edit Instrument Type',
            'update_item' => 'Update Instrument Type',
            'add_new_item' => 'Add New Instrument Type',
            'new_item_name' => 'New Instrument Type',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'instrument_type'),
    ));
    flush_rewrite_rules();
}

add_action('widgets_init', 'apd_instrument_type_register');

function apd_format_type_register() {
    register_taxonomy('format_type', array('product'), array(
        'labels' => array(
            'name' => 'Format Types',
            'singular_name' => 'Format Types',
            'search_items' => 'Search Format Type',
            'popular_items' => 'Popular Format Types',
            'all_items' => 'All Format Types',
            'parent_item' => 'Parent Format Type',
            'parent_item_colon' => 'Parent Format Type:',
            'edit_item' => 'Edit Format Type',
            'update_item' => 'Update Format Type',
            'add_new_item' => 'Add New Format Type',
            'new_item_name' => 'New Format Type',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'format_type'),
    ));
    flush_rewrite_rules();
}

add_action('widgets_init', 'apd_format_type_register');

function apd_cart_count_fragments($fragments) {
    ob_start();
    ?>
    <a class="wcmenucart-contents" href="<?php echo wc_get_cart_url(); ?>" title="Start shopping">Cart
        (<?php echo WC()->cart->get_cart_contents_count(); ?>)</a>
    <?php
    $fragments['a.wcmenucart-contents'] = ob_get_clean();

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'apd_cart_count_fragments', 10, 1);

function apd_rewards_update($fragments) {
    $current_user = wp_get_current_user();
    if ($current_user->ID) {
        $reward_points = (get_user_meta($current_user->ID, '_reward_points', true)) ? get_user_meta($current_user->ID, '_reward_points', true) : 0;
    }
    ob_start();
    ?>
    <a href="<?php echo get_permalink(get_page_by_path('wallet')); ?>" class="your_rewards">Your rewards:
        $<?php echo number_format($reward_points, 2, '.', ''); ?></a>
    <?php
    $fragments['a.your_rewards'] = ob_get_clean();

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'apd_rewards_update', 10, 1);

function apd_process_order_email($classes) {
    include(get_stylesheet_directory() . '/inc/class.ShopOrderEmail.php');

    $classes['WC_Apd_Shop_Order_Email'] = new ApdShopOrderEmail();

    return $classes;
}

add_filter('woocommerce_email_classes', 'apd_process_order_email');

function apd_process_big_order_email($classes) {
    include(get_stylesheet_directory() . '/inc/class.BigDealOrderEmail.php');

    $classes['WC_Apd_BigDeal_Order_Email'] = new ApdBigDealOrderEmail();

    return $classes;
}

add_filter('woocommerce_email_classes', 'apd_process_big_order_email');


function apd_process_airdrop_email($classes) {
    include(get_stylesheet_directory() . '/inc/class.AirDropEmail.php');

    $classes['WC_Apd_AirDrop_Email'] = new ApdAirDropOrderEmail();

    return $classes;
}

add_filter('woocommerce_email_classes', 'apd_process_airdrop_email');


function apd_myaccount_label_change($items, $args) {
    if (!is_user_logged_in() && $args->menu == 'Account Menu') {
        $items = str_replace("My Account", "Login", $items);
    }
    $items = str_replace('id="menu-item-204275"', 'data-icon="account" id="menu-item-204275"', $items);

    return $items;
}

add_filter('wp_nav_menu_items', 'apd_myaccount_label_change', 10, 2);

function apd_product_id_callback($post) {
    $apd_stored_meta = (get_post_meta($post->ID, 'meta_product_id', true)) ? get_post_meta($post->ID, 'meta_product_id', true) : false;
    $params = array(
        'posts_per_page' => -1,
        'post_type' => 'product',
        'stock' => 1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $wc_query = new WP_Query($params);
    ?>
    <p>
        <label for="meta-text" class="prfx-row-title"><?php _e('Select a product from the list', 'apd') ?></label>
        <select name="meta_product_id">
            <option>Select a product</option>
            <?php
            foreach ($wc_query->posts as $product):
                $selected = ($apd_stored_meta && $apd_stored_meta == $product->ID) ? 'selected' : '';
                echo '<option value="' . $product->ID . '" ' . $selected . '>' . $product->post_title . '</option>';
            endforeach;
            ?>
        </select>
    </p>
    <?php
}

function apd_metabox_callback($post) {
    $apd_stored_meta = get_post_meta($post->ID, 'is_shop_page', true);
    ?>
    <p>
        <label for="meta-text" class="prfx-row-title"><?php _e('Is Shop Page: ', 'apd') ?></label>
        <input type="checkbox" name="is_shop_page" <?php echo ($apd_stored_meta == 'yes') ? 'checked' : ''; ?>>
    </p>
    <?php
}


function apd_product_email_contents($post) {
    $email_template = get_post_meta($post->ID, 'apd_product_email_template', true);
    $email_heading = get_post_meta($post->ID, 'apd_product_email_heading', true);
    ?>
    
    <label style="margin: 10px 0px; font-weight: bold; display: block;"><?php _e('Shop Product email subject:', 'apd') ?></label>
    <div><input type="text" name="apd_product_email_heading" size="50" value="<?php echo $email_heading; ?>"></div>
    
    <label  style="margin: 10px 0px; font-weight: bold; display: block;" for="meta_email_template" class="prfx-row-title"><?php _e('Shop Product email template:', 'apd') ?></label>
  <?php echo wp_editor($email_template, 'apd_product_email_template', array('textarea_name' => 'apd_product_email_template')); ?>
    
    <?php
    $deal_email_template = get_post_meta($post->ID, 'apd_deal_product_email_template', true);
    $deal_email_heading = get_post_meta($post->ID, 'apd_deal_product_email_heading', true);
    ?>
    <label style="margin: 10px 0px; font-weight: bold; display: block;"><?php _e('Deal Product email subject:', 'apd') ?></label>
    <div><input type="text" name="apd_deal_product_email_heading"  size="50" value="<?php echo $deal_email_heading; ?>"></div>
    
    <label style="margin: 10px 0px; font-weight: bold; display: block;"  for="meta_email_template" class="prfx-row-title"><?php _e('Deal Product email template:', 'apd') ?></label>
  <?php echo wp_editor($deal_email_template, 'apd_deal_product_email_template', array('textarea_name' => 'apd_deal_product_email_template')); ?>
    <?php
}

function apd_product_meta_boxes() {

    add_meta_box('product_email_contents', 'Product Emails', 'apd_product_email_contents', 'product', 'normal', 'high');

    add_meta_box('apd_shop_product_id_page_meta', __('Select a parent product', 'apd'), 'apd_product_id_callback', 'page', 'side');

    add_meta_box('apd_shop_product_page_meta', __('Is Shop Product', 'apd'), 'apd_metabox_callback', 'page', 'side');
}


add_action('add_meta_boxes', 'apd_product_meta_boxes');

function apd_meta_save($post_id) {
    if (isset($_POST['is_shop_page'])) {
        update_post_meta($post_id, 'is_shop_page', 'yes');
    } else {
        update_post_meta($post_id, 'is_shop_page', 'no');
    }

    if (isset($_POST['meta_product_id'])) {
        update_post_meta($post_id, 'meta_product_id', $_POST['meta_product_id']);
    }

    if (isset($_POST['apd_product_email_template'])) {
        update_post_meta($post_id, 'apd_product_email_template', $_POST['apd_product_email_template']);
    }

    if (isset($_POST['apd_product_email_heading'])) {
        update_post_meta($post_id, 'apd_product_email_heading', $_POST['apd_product_email_heading']);
    }
    
    if (isset($_POST['apd_deal_product_email_template'])) {
        update_post_meta($post_id, 'apd_deal_product_email_template', $_POST['apd_deal_product_email_template']);
    }

    if (isset($_POST['apd_deal_product_email_heading'])) {
        update_post_meta($post_id, 'apd_deal_product_email_heading', $_POST['apd_deal_product_email_heading']);
    }
}

add_action('save_post', 'apd_meta_save');


function apd_custom_search($keyword, $pagenum, $orderby ) {
  
    switch ($orderby) {
        case 'price':
            $order = 'ASC';
            $orderby = 'meta_value_num';
            break;
        case 'price-desc':
            $order = 'DESC';
            $orderby = 'meta_value_num';
            break;
        case 'popularity': // todo later
        case 'date':
        default:
            $order = 'DESC';
            $orderby = 'date';
    }
    
    
    $params = array(
        'paged' => $pagenum,
        'post_type' => 'product',
        'stock' => 1,
        'meta_query' => array(
            array(
                'key' => '_product_type_single',
                'value' => 'yes'
            )
        ),
        's' => $keyword,
        'order' => $order,
        'orderby' => $orderby
    );
    
    if ( $orderby == 'meta_value_num' ) {
        $params['meta_key']  = '_price';
    }

    return $wc_query = new WP_Query($params);
}

function apd_pre_get_posts_query($q) {
    if (!$q->is_main_query()) {
        return;
    }
    if ($q->is_archive() || get_queried_object()->post_name == 'new-arrivals' || get_queried_object()->post_name == 'trending' || get_queried_object()->post_name == 'search') {
        $q->set('meta_key', '_product_type_single');
        $q->set('meta_value', 'yes');
    }
}

add_action('woocommerce_product_query', 'apd_pre_get_posts_query');

function apd_temporary_rewards_addon() {
    add_management_page('Add rewards', 'Add rewards', 'manage_options', 'temp_rewards', 'apd_add_rewards');
}

add_action('admin_menu', 'apd_temporary_rewards_addon');

function apd_add_rewards() {
    if (isset($_POST['add_temp_rewards'])) {
//        $customers = get_users();
//        if (count($customers) > 0) {
//            foreach ($customers as $customer) {
//                $current_points = (!empty(get_user_meta($customer->ID, '_reward_points', true))) ? get_user_meta($customer->ID, '_reward_points', true) : 0;
//                update_user_meta($customer->ID, '_reward_points', $current_points + $_POST['temp_rewards']);
//            }
//        }
        global $wpdb;
        $wpdb->query($wpdb->prepare("UPDATE `{$wpdb->usermeta}` SET `meta_value` = CAST(`meta_value` AS UNSIGNED) + %d WHERE `meta_key`='_reward_points'", intval($_POST['temp_rewards'])));
        // var_dump($wpdb->last_query, $wpdb->last_error);
    }
    ?>
    <div class="wrap">
        <h1>Add rewards for time period</h1>
        <form action="" method="POST">
            <p><label>Enter the points that should be applied</label></p>
            <p><input type="number" name="temp_rewards"></p>
            <p><input type="submit" name="add_temp_rewards" class="button"></p>
        </form>
    </div>
    <?php
}

function remove_temp_points() {
    $today = strtotime(date('Y-m-d H:i'));
    $expiration_date = strtotime(get_option('temp_rewards_period', true));
    if ($expiration_date && $today >= $expiration_date) {
        $customers = get_users();
        if (count($customers) > 0) {
            foreach ($customers as $customer) {
                $temp_points = (get_user_meta($customer->ID, 'temp_reward_points', true)) ? get_user_meta($customer->ID, 'temp_reward_points', true) : 0;
                if ($temp_points > 0) {
                    $current_points = get_user_meta($customer->ID, '_reward_points', true);
                    update_user_meta($customer->ID, 'temp_reward_points', 0);
                    update_user_meta($customer->ID, '_reward_points', $current_points - $temp_points);
                }
            }
            update_option('temp_rewards_period', 0);
            update_option('temp_rewards_points', 0);
        }
    }
}

add_action('remove_temp_points_hook', 'remove_temp_points');

function apd_rewards_ajax() {
    $current_user = wp_get_current_user();
    if ($current_user->ID) {
        $reward_points = (get_user_meta($current_user->ID, '_reward_points', true)) ? get_user_meta($current_user->ID, '_reward_points', true) : 0;
    }
    ob_start();
    ?>
    Your rewards: $<?php echo number_format($reward_points, 2, '.', ''); ?>
    <?php
    $fragments = ob_get_clean();
    echo $fragments;
    die();
}

// add_action('wp_ajax_rewards', 'apd_rewards_ajax');

function apd_resend_ajax() {
    global $woocommerce;
    if (isset($_POST['orderid'])) {
        $mailer = $woocommerce->mailer();
        $header = "Content-Type: text/html\r\n";
        $order = wc_get_order($_POST['orderid']);

        $recipient = $order->get_billing_email();
        foreach ($order->get_items() as $item) {
            if (get_post_meta($item['product_id'], '_product_type_single', true) == 'yes') {
                $template = get_post_meta($_POST['orderid'], 'email_template_content_' . $item['product_id'], true);
                $subject = "Download instruction for " . $item['name'];
                add_filter('woocommerce_email_styles', 'apd_woocommerce_email_styles');
                $template = str_replace('#e78b79', '#2773c9', $template);
                $template = str_replace('#d73e1f', '#2773c9', $template);
            } elseif (get_post_meta($_POST['orderid'], 'email_template_content_deal', true)) {
                remove_filter('woocommerce_email_styles', 'apd_woocommerce_email_styles');
                $template = get_post_meta($_POST['orderid'], 'email_template_content_deal', true);
                $subject = "Download instruction for " . $item['name'];
            } else {
                remove_filter('woocommerce_email_styles', 'apd_woocommerce_email_styles');
                $template = get_post_meta($_POST['orderid'], 'email_template_content', true);
                $subject = "Download instruction for " . $item['name'];
            }
            $mailer->send($recipient, $subject, $template, $header);
        }
        echo '1';
    }
    die();
}

add_action('wp_ajax_resend', 'apd_resend_ajax');

function apd_facebook_ajax() {
    $current_user = wp_get_current_user();
    if ($current_user->ID) {
        $reward_points = (get_user_meta($current_user->ID, '_reward_points', true)) ? get_user_meta($current_user->ID, '_reward_points', true) : 0;
        $is_used = (get_user_meta($current_user->ID, 'fb_shared', true) == 'yes') ? get_user_meta($current_user->ID, 'fb_shared', true) : 'no';
        if ($is_used == 'no') {
            $reward_points += 20;
            update_user_meta($current_user->ID, '_reward_points', $reward_points);
            update_user_meta($current_user->ID, 'fb_shared', 'yes');
        }
    }
    die();
}

add_action('wp_ajax_facebook', 'apd_facebook_ajax');

function is_subscribed_youtube() {
    if (!is_user_logged_in()) {
        return true;
    }

    $current_user = wp_get_current_user();
    return get_user_meta($current_user->ID, '_youtube_subscribed', true);
}

function apd_wc_login_redirect($redirect) {
    if (isset($_POST['wp_referrer']) && !empty($_POST['wp_referrer'])) {
        $redirect = esc_url($_POST['wp_referrer']);
    } else {
        $redirect = esc_url(get_permalink(get_page_by_path('checkout')));
    }

    return $redirect;
}

add_filter('woocommerce_login_redirect', 'apd_wc_login_redirect');

function apd_wc_load_persistent_cart($user_login, $user = 0) {
    if (!$user) {
        return;
    }

    $saved_cart = (get_user_meta($user->ID, '_woocommerce_persistent_cart_1', true)) ? get_user_meta($user->ID, '_woocommerce_persistent_cart_1', true) : get_user_meta($user->ID, '_woocommerce_persistent_cart', true);
    if ($saved_cart) {
        $cart = array_merge($saved_cart['cart'], WC()->session->cart);
        WC()->session->cart = $cart;
    }
}

add_action('wp_login', 'apd_wc_load_persistent_cart', 1, 2);

function apd_reorder_account_menu($items) {
    // titles
    $items['orders'] = 'Dashboard';
    $items['edit-address'] = 'Billing Details';
    $items['customer-logout'] = 'Log out';

    // remove
    unset($items['dashboard']);
    unset($items['wc-smart-coupons']);
    return $items;
}

add_filter('woocommerce_account_menu_items', 'apd_reorder_account_menu', 99, 1);

function apd_rewards_orders_endpoint($url, $endpoint, $value, $permalink) {
    if ($endpoint === 'orders') {
        $url = get_permalink(get_page_by_path('wallet'));
    }

    return $url;
}

add_filter('woocommerce_get_endpoint_url', 'apd_rewards_orders_endpoint', 10, 4);

function apd_archive_sort_order($query) {
    if ($query->is_archive() && $query->is_main_query()) {
        $category = get_queried_object();
        if ($category->slug == 'blue-light-specials') {
            $query->query_vars['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'terms' => $category->term_id,
                    'operator' => 'IN'
                ),
            );
            $query->query_vars['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key' => '_product_type_single',
                    'value' => 'yes',
                    'compare' => '=',
                ),
                'sort' => array(
                    'key' => '_blue_light_sort',
                    'compare' => 'EXISTS',
                )
            );
            $query->query_vars['orderby'] = array('sort' => 'ASC', 'date' => 'DESC');
        }

        if ($category->slug == 'gift-certificate') {
            $query->query_vars['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'terms' => $category->term_id,
                    'operator' => 'IN'
                ),
            );
            $query->query_vars['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key' => '_product_type_single',
                    'value' => 'yes',
                    'compare' => '=',
                ),
                'sort' => array(
                    'key' => '_gift_certificates_sort',
                    'compare' => 'EXISTS',
                ),
            );
            $query->query_vars['orderby'] = array('sort' => 'ASC');
        }
    }
}

add_action('pre_get_posts', 'apd_archive_sort_order');

function apd_remove_from_wish() {
    file_put_contents('/tmp/test.txt', print_r($_REQUEST, true));
    if (isset($_GET['remove_from_wishlist_after_add_to_cart'])) {
        file_put_contents('/tmp/test1.txt', 'dfddd');
        global $wpdb;
        $prod_id = $_REQUEST['remove_from_wishlist_after_add_to_cart'];
        $wishlist_id = $_REQUEST['wishlist_id'];
        $result = $wpdb->delete('wp_yith_wcwl', array('prod_id' => $prod_id, 'wishlist_id' => $wishlist_id));
    }
}

add_action('woocommerce_add_to_cart', 'apd_remove_from_wish');

function apd_shop_order_success_email_template($order_id, $product_id) {
    $email_template_content = get_post_meta($order_id, 'email_template_content_' . $product_id, 'true');

    return $email_template_content;
}

add_filter('woocommerce_continue_shopping_redirect', 'apd_changed_woocommerce_continue_shopping_redirect', 10, 1);
function apd_changed_woocommerce_continue_shopping_redirect($return_to) {
    $return_to = get_permalink(get_page_by_path('shop'));

    return $return_to;
}

add_action('woocommerce_email_order_details', 'apd_order_email', 10, 4);

function apd_order_email($order, $sent_to_admin, $plain_text, $email) {
    if ($sent_to_admin) {
        if (get_post_meta($order->get_id(), 'order_type', true) == 'shop') {
            add_filter('woocommerce_email_styles', 'apd_woocommerce_email_styles');
            $image = '<img class="header_logo" src="' . esc_url(get_template_directory_uri()) . '/images/shop/logo_shop.png"/>';
            $email->template_html = str_replace('#e78b79', '#2773c9', $email->template_html);
            $email->template_html = str_replace('#d73e1f', '#2773c9', $email->template_html);
            $email->template_html = str_replace('[header_image]', $image, $email->template_html);
        } else {
            remove_filter('woocommerce_email_styles', 'apd_woocommerce_email_styles');
            $image = '<img class="header_logo" src="' . esc_url(get_template_directory_uri()) . '/images/logo.png"/>';
            $email->template_html = str_replace('[header_image]', $image, $email->template_html);
        }
    }
}

add_action('woocommerce_payment_complete', 'apd_payment_complete');

function apd_payment_complete($order_id) {
    global $woocommerce;
    $woocommerce->cart->empty_cart();
    $order = new WC_Order($order_id);
    $order->update_status('completed');
}

add_action('woocommerce_thankyou', 'apd_thankyou_hook');

function apd_thankyou_hook() {
    global $woocommerce;
    $woocommerce->cart->empty_cart();
}

function create_post_type() {
    register_post_type('apd_hero_banner',
        array(
            'labels' => array(
                'name' => __('Banner'),
                'singular_name' => __('Banner')
            ),
            'public' => true,
            'has_archive' => false,
        )
    );
}

add_action('init', 'create_post_type');

add_action('user_register', 'apd_registration_bonus', 10, 1);

function apd_registration_bonus($user_id) {
    update_user_meta($user_id, '_reward_points', '20');
}

// slider_custom_post_field

function create_post_your_post() {


    register_post_type( 'your_post',
        array(
            'labels'       => array(
'name'       => __( 'Slider' ),
            ),
            'public'       => true,
            'hierarchical' => true,
            'has_archive'  => true,
            'supports'     => array(
'title',
'editor',
'excerpt',
'thumbnail',
            ),
            'taxonomies'   => array(
'post_tag',
'category',
            )
        )
    );
    register_taxonomy_for_object_type( 'category', 'your_post' );
    register_taxonomy_for_object_type( 'post_tag', 'your_post' );
}

add_action( 'init', 'create_post_your_post' );



// Multiple Add to Cart in URL
function woocommerce_maybe_add_multiple_products_to_cart($url = false) {
    // Make sure WC is installed, and add-to-cart qauery arg exists, and contains at least one comma.
    if (!class_exists('WC_Form_Handler') || empty($_REQUEST['add-to-cart']) || false === strpos($_REQUEST['add-to-cart'], ',')) {
        return;
    }

    // Remove WooCommerce's hook, as it's useless (doesn't handle multiple products).
    remove_action('wp_loaded', array('WC_Form_Handler', 'add_to_cart_action'), 20);

    $product_ids = explode(',', $_REQUEST['add-to-cart']);
    $count = count($product_ids);
    $number = 0;

    foreach ($product_ids as $id_and_quantity) {
        // Check for quantities defined in curie notation (<product_id>:<product_quantity>)
        // https://dsgnwrks.pro/snippets/woocommerce-allow-adding-multiple-products-to-the-cart-via-the-add-to-cart-query-string/#comment-12236
        $id_and_quantity = explode(':', $id_and_quantity);
        $product_id = $id_and_quantity[0];

        $_REQUEST['quantity'] = !empty($id_and_quantity[1]) ? absint($id_and_quantity[1]) : 1;

        if (++$number === $count) {
            // Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
            $_REQUEST['add-to-cart'] = $product_id;

            return WC_Form_Handler::add_to_cart_action($url);
        }

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($product_id));
        $was_added_to_cart = false;
        $adding_to_cart = wc_get_product($product_id);

        if (!$adding_to_cart) {
            continue;
        }

        $add_to_cart_handler = apply_filters('woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart);

        // Variable product handling
        if ('variable' === $add_to_cart_handler) {
            woo_hack_invoke_private_method('WC_Form_Handler', 'add_to_cart_handler_variable', $product_id);
            // Grouped Products
        } elseif ('grouped' === $add_to_cart_handler) {
            woo_hack_invoke_private_method('WC_Form_Handler', 'add_to_cart_handler_grouped', $product_id);
            // Custom Handler
        } elseif (has_action('woocommerce_add_to_cart_handler_' . $add_to_cart_handler)) {
            do_action('woocommerce_add_to_cart_handler_' . $add_to_cart_handler, $url);
            // Simple Products
        } else {
            woo_hack_invoke_private_method('WC_Form_Handler', 'add_to_cart_handler_simple', $product_id);
        }
    }
}

// Fire before the WC_Form_Handler::add_to_cart_action callback.
add_action('wp_loaded', 'woocommerce_maybe_add_multiple_products_to_cart', 15);

/**
 * Invoke class private method
 *
 * @param string $class_name
 * @param string $methodName
 *
 * @return  mixed
 * @since   0.1.0
 *
 */
function woo_hack_invoke_private_method($class_name, $methodName) {
    if (version_compare(phpversion(), '5.3', '<')) {
        throw new Exception('PHP version does not support ReflectionClass::setAccessible()', __LINE__);
    }

    $args = func_get_args();
    unset($args[0], $args[1]);
    $reflection = new ReflectionClass($class_name);
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);

    $args = array_merge(array($class_name), $args);
    return call_user_func_array(array($method, 'invoke'), $args);
}

/** function force_clear_woocommerce_cart()
 * {
 * $user_ID = get_current_user_id();
 * if ($user_ID === 11368)
 * {
 * error_log("Clearing cart");
 * global $woocommerce;
 * $woocommerce->cart->empty_cart();
 * }
 * }
 * add_action( 'init', 'force_clear_woocommerce_cart' ); **/
function wc_save_account_details_required_fields($required_fields) {
    unset($required_fields['account_display_name']);
    return $required_fields;
}

add_filter('woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields', 99999);

/* Defer g of Javascript */
function defer_parsing_of_js($url) {
    if (FALSE === strpos($url, '.js')) return $url;
    if (strpos($url, 'jquery.js')) return $url;
    return "$url' defer";
}

// add_filter('clean_url', 'defer_parsing_of_js', 11, 1);
// Changing excerpt more
function new_excerpt_more($more) {
    global $post;
    return '... <a href="' . get_permalink($post->ID) . '">' . 'Read More &raquo;' . '</a>';
}

add_filter('excerpt_more', 'new_excerpt_more');
// Customer excerpt length
function ld_custom_excerpt_length($length) {
    return 50;
}

add_filter('excerpt_length', 'ld_custom_excerpt_length', 999);
// Remove the last link in breadcrumbs
// WHY? Because it an span tag that contains the title of the page
// But you're already on the page, so what's the point?
add_filter('wpseo_breadcrumb_links', 'jj_wpseo_breadcrumb_links');
function jj_wpseo_breadcrumb_links($links) {
    //pk_print( sizeof($links) );
    if (sizeof($links) > 1) {
        array_pop($links);
    }
    return $links;
}

// Add link to the last item in the breadcrumbs
// WHY? Because, by default, WP-SEO doesn't include the link on the last item
// Since we removed in the function above, we need to add the link back in.
add_filter('wpseo_breadcrumb_single_link', 'jj_link_to_last_crumb', 10, 2);
function jj_link_to_last_crumb($output, $crumb) {
    $output = '<a property="v:title" rel="v:url" href="' . $crumb['url'] . '" >';
    $output .= $crumb['text'];
    $output .= '</a>';
    return $output;
}

// Disable repeat purchases of Free Offers
function sv_disable_repeat_purchase($purchasable, $product) {
    // Enter the ID of the product that shouldn't be purchased again
    $non_purchasable = 1584149;

    // Get the ID for the current product (passed in)
    $product_id = $product->is_type('variation') ? $product->variation_id : $product->id;


    
    // Bail unless the ID is equal to our desired non-purchasable product
    if ($non_purchasable == $product_id) {

        // return false if the customer has bought the product
        if (wc_customer_bought_product(wp_get_current_user()->user_email, get_current_user_id(), $product_id)) {
            $purchasable = false;
        }
    }

    // Double-check for variations: if parent is not purchasable, then variation is not
    if ($purchasable && $product->is_type('variation')) {
        $purchasable = $product->parent->is_purchasable();
    }

    return $purchasable;
}

add_filter('woocommerce_variation_is_purchasable', 'sv_disable_repeat_purchase', 10, 2);
add_filter('woocommerce_is_purchasable', 'sv_disable_repeat_purchase', 10, 2);

// Let customers know they have already purchased the product
add_filter('gettext', 'renaming_purshasable_notice', 100, 3 );
function renaming_purshasable_notice( $translated_text, $text, $domain ) {
    if( $text === 'Sorry, this product cannot be purchased.' ) {
        $post_title = get_post($GLOBALS['_POST']['add-to-cart'])->post_title;

        $translated_text = sprintf( __( 'You have already purchased this item. Please check your email address for download instructions. Also check your spam folder in case the instructions ended up there.', $domain ), '&quot;'.$post_title.'&quot;' );
    }
    return $translated_text;
}



function filter_woocommerce_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = null, $variations = null ) {
    // Retrieve the current user object
    $current_user = wp_get_current_user();
    
    // Check for variantions, if you don't want this, delete this code line
    $product_id = $variation_id > 0 ? $variation_id : $product_id;
    
    // Checks if a user (by email or ID or both) has bought an item
    if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id ) ) {
        // Display an error message
        wc_add_notice( __( 'You have already purchased this item. Please check your email address for download instructions. Also check your spam folder in case the instructions ended up there', 'woocommerce' ), 'error' );
        
        $passed = false;
    }
    else {


        // Loop through cart items
        foreach( WC()->cart->get_cart() as $cart_item ) {
            // Check for variantions
            $cart_product_id = $cart_item['variation_id'] > 0 ? $cart_item['variation_id'] : $cart_item['product_id'];
            
            if ( $product_id == $cart_product_id ) {

                wc_add_notice( __( 'You have already added this item to cart. Only one item allowed per order', 'woocommerce' ), 'error' );

                $passed = false;
                break;
            }
        }

    }

    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'filter_woocommerce_add_to_cart_validation', 10, 5 );




function tutsplus_widgets_init() {
    // First footer widget area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('First Footer Widget Area', 'tutsplus'),
        'id' => 'first-footer-widget-area',
        'description' => __('The first footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    // Second Footer Widget Area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Second Footer Widget Area', 'tutsplus'),
        'id' => 'second-footer-widget-area',
        'description' => __('The second footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    // Third Footer Widget Area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Third Footer Widget Area', 'tutsplus'),
        'id' => 'third-footer-widget-area',
        'description' => __('The third footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    // Fourth Footer Widget Area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Fourth Footer Widget Area', 'tutsplus'),
        'id' => 'fourth-footer-widget-area',
        'description' => __('The fourth footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

// Register sidebars by running tutsplus_widgets_init() on the widgets_init hook.
add_action('widgets_init', 'tutsplus_widgets_init');

// Owl Js Add

function owl_carousel_file() {
    $aa = rand(10, 10000);
    wp_enqueue_style('owl_carousel_min_css', get_template_directory_uri() . '/css/owl.carousel.min.css');
    wp_enqueue_style('owl_carousel_min_theme_css', get_template_directory_uri() . '/css/owl.theme.default.min.css');
    wp_enqueue_style('slick_css', get_template_directory_uri() . '/js/slick.css');
    wp_enqueue_style('slick_theme_css', get_template_directory_uri() . '/js/slick-theme.css');
    wp_enqueue_script('owl_carousel_min', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), null, true);
    wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.js', array(), null, true);
    wp_enqueue_script('custom_js', get_template_directory_uri() . '/custom.js', array(), $aa, true);
}

add_action('wp_enqueue_scripts', 'owl_carousel_file');
?>
<?php
add_action('save_post', 'custom_update_percentage_price');
function custom_update_percentage_price($post_id) {
    if ($_POST['post_type'] != 'product')
        return false;

    if (isset($_POST['_minumum_price']) && isset($_POST['_regular_price'])) {
        $m_price = $_POST['_minumum_price'];
        $r_price = $_POST['_regular_price'];

        $c_price = round(($r_price - $m_price) / $r_price, 2);

        $percent = $c_price * 100;
        update_post_meta($post_id, '_custom_percentage_price', $percent);
    }
}

function wpb_total_posts() {
    $total = wp_count_posts()->publish;
    return $total;
}

add_shortcode('total_posts', 'wpb_total_posts');

// carbonfields
function crb_load() {
    require_once(get_theme_file_path('/vendor/autoload.php'));
    \Carbon_Fields\Carbon_Fields::boot();
}

add_action('after_setup_theme', 'crb_load');

function crb_attach_theme_options() {
    require_once(get_theme_file_path('/inc/carbonfields/bf2019.php'));
    require_once(get_theme_file_path('/inc/carbonfields/bf2020.php'));
    require_once(get_theme_file_path('/inc/carbonfields/bf2021.php'));
    require_once(get_theme_file_path('/inc/carbonfields/bf2022.php'));
    require_once(get_theme_file_path('/inc/carbonfields/bfchrist.php'));
    require_once(get_theme_file_path('/inc/carbonfields/theme-options.php'));
}

add_action('carbon_fields_register_fields', 'crb_attach_theme_options');

// youtube subscription
require_once(get_theme_file_path('/inc/youtube-subscription.php'));

// rating system
require_once(get_theme_file_path('/inc/rating-system.php'));
?>
<?php 

function my_theme_scripts() {

  wp_enqueue_script( 'theme_js', get_stylesheet_directory_uri() . '/js/theme.js', array('jquery'), false, true ); 

}
add_action("wp_enqueue_scripts", "my_theme_scripts");

?>
<?php


//[custom_post_slider]
function foobar_func( $atts ){
    global $wp_post_types;

    $args = array(
        'post_type'       => 'your_post',
        'posts_per_page'  => -1,
    );
    query_posts( $args );

    ?>
<div class="slideshow-container">
<?php

        while ( have_posts() ) : the_post(); ?>
<div class="sldier_section">
            <div class="slider_content" style="background: url(https://audioplugin.deals/wp-content/uploads/2019/03/bg-memory-v-100off-624x596.png); background-repeat: no-repeat;">
                <div class="slider-left">
<?php

                    $image = get_field('image');
                    if( !empty( $image ) ): ?>
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    <?php endif; ?>
                </div>
                <div class="slider-content">
                    <?php
                    $image_two = get_field('image_two');
                    if( !empty( $image_two ) ): ?>
                        <img src="<?php echo esc_url($image_two['url']); ?>" alt="<?php echo esc_attr($image_two['alt']); ?>" />
                    <?php endif; ?>
                </div>
                <div class="slider-right">
                    <p class="slider-des">
                    <?php
                    $text = get_field('text');
                    if( !empty( $text ) ): ?>
                        <?php echo ($text); ?>
                    <?php endif; ?>
                    </p>
                    <div class="slider_btn vc_btn3-container download-for-free-btn vc_btn3-inline">
                    <!--     <a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-modern vc_btn3-color-default form-btn" title="DOWNLOAD FOR FREE!" data-sumome-listbuilder-id="f3f1db7d-cbf6-4033-bc3e-7482f0afe06a">DOWNLOAD FOR FREE!</a> -->
                         <a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-modern vc_btn3-color-default form-btn" data-toggle="modal" data-target="#myModal">DOWNLOAD FOR FREE!</a>
                    </div>
                </div>
                <div class="modal-section">
                    <form method="post" role="form" action="/">
                        <p class="modal-header-des"><?php $modal_header = get_field('modal_header'); echo ($modal_header);?></p>
                        <div class="form-des">
                            <p class="modal-content-des"><?php $modal_des = get_field('modal_des'); echo ($modal_des);?></p>
                            <input type="hidden" name="action" value="process_form">
                            <input type="hidden" name="redirect_url" value ="<?php $redirect_url = get_field('redirect_url'); echo($redirect_url); ?>">
                            <input class="form-control" type="email" required="" id="email" name="email" value="<?php  if(!empty($email)) echo $email; ?>" 
                                placeholder="Enter your Email" oninvalid="this.setCustomValidity('Please provide a valid email address')">
                            </input>
                            <!-- <input type="email" class="form-control" id="email" name="email" value="<?php  if(!empty($email)) echo $email; ?>" placeholder="Enter your Email" required /> -->
                            <button type="submit" name="submit" class="form-submit"><?php $modal_btn_text = get_field('modal_btn_text'); echo ($modal_btn_text);?></button>   
                            <button class="form-btn-des close" data-dismiss="modal"><?php $modal_bot_des = get_field('modal_bot_des'); echo ($modal_bot_des);?></button>   
                        </div>
                        <div class="form-image">
                             <?php
                                $modal_image = get_field('modal_image');?>
                                 <img src="<?php echo esc_url($modal_image['url']); ?>" alt="<?php echo esc_attr($modal_image['alt']); ?>" />       
                        </div>
                    </form>
                </div>
            </div>        
        </div>
<?php endwhile;
        ?>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog download-modal">
        <div class="modal-top"><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
        </div>
      </div>
    </div>
 <script type="text/javascript">
        jQuery(document).on('ready', function() {
          jQuery(".slideshow-container").slick({
            lazyLoad: 'ondemand', // ondemand progressive anticipated
            infinite: true,
            autoplay: true,
            dots: true
          });
        });
        jQuery(document).on('ready', function(){
            jQuery(".form-btn").click(function(){
                jQuery('.modal-body').html(jQuery(this).parents('.sldier_section').find('.modal-section').html());
            });

        });
</script>
    </body>
    <?php

}

add_shortcode( 'custom_post_slider', 'foobar_func' );

?>
<?php
if (isset($_POST["email"])) {

$redirect_url = $_POST['redirect_url'];

session_start();
if(isset($_POST['submit'])){
    $email = $_POST['email'];
    if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL) === false){
        // MailChimp API credentials
        $apiKey = 'c2f7d9f3280b13a4d1e6e501c4c478bb-us15';
        $listID = '766211a9e5';
        
        // MailChimp API URL
        $memberID = md5(strtolower($email));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
        
        // member information
        $json = json_encode([
            'email_address' => $email,
            'status'        => 'subscribed'
        ]);
        
        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // store the status message based on response code
        if ($httpCode == 200) {
            $_SESSION['msg'] = '<p style="color: #34A853">You have successfully subscribed to CodexWorld.</p>';
        } else {
            switch ($httpCode) {
                case 214:
                    $msg = 'You are already subscribed.';
                    break;
                default:
                    $msg = 'Some problem occurred, please try again.';
                    break;
            }
            $_SESSION['msg'] = '<p style="color: #EA4335">'.$msg.'</p>';
        }
    }else{
        $_SESSION['msg'] = '<p style="color: #EA4335">Please enter valid email address.</p>';
    }
}
// redirect to homepage

header('location:'.$redirect_url);
}


// add_action('woocommerce_thankyou', 'perfectaudience_order_info', 10, 1);
/* function perfectaudience_order_info( $order_id ) {
    global $woocommerce_order_data_for_perfectaudience;

    $order = wc_get_order($order_id);

    $woocommerce_order_data_for_perfectaudience = false;
    if ( $order ) {
        $items = $order->get_items();
        if ( is_array($items) && count($items) ) {
            $item = array_pop($items);
            $product_id = $item->get_product_id();
            $revenue = $order->get_total();

            $woocommerce_order_data_for_perfectaudience = array(
                'order_id'      => $order_id,
                'revenue'       => $revenue,
                'product_id'    => 'woocommerce_gpf_' . $product_id
            );
            add_action('wp_footer', 'perfectaudience_js_output_footer', 10, 0);
        }
    }
} */
/* function perfectaudience_js_output_footer() {
    global $woocommerce_order_data_for_perfectaudience;
    $js_data = json_encode($woocommerce_order_data_for_perfectaudience);
    ?>
    <script type="text/javascript">
        var order_data_for_perfectaudience = <?php echo ($js_data); ?>; 
    </script>
    <?php
} */

// Disable password strenght meter
function deregister_or_dequeue_scripts() {
    wp_dequeue_script('wc-password-strength-meter');
}

add_action('wp_print_scripts', 'deregister_or_dequeue_scripts', 20);


// disable ZIP code validation in WC checkout 
add_filter( 'woocommerce_checkout_fields', 'apd_no_postcode_validation' );
 
function apd_no_postcode_validation( $fields ){
    unset($fields['billing']['billing_postcode']['validate']);
    return $fields;
}
add_action('wp_logout','my_account_page_logout_redirect');
function my_account_page_logout_redirect(){
       wp_redirect( get_permalink(49) );
       exit;
}


// Alter description for Stripe payment method on checkout page

add_filter( 'wc_stripe_description', 'credit_card_logos_for_stripe_payment_method', 10, 2);

function credit_card_logos_for_stripe_payment_method( $description, $payment_method_id ) {
    $card_logos = ''
        . '<div class="card-logos">
<img class="card-logo" src="/wp-content/themes/apd/card-logos/Visa.svg">
<img class="card-logo" src="/wp-content/themes/apd/card-logos/MasterCard.svg">
<img class="card-logo" src="/wp-content/themes/apd/card-logos/Amex.svg">
<img class="card-logo" src="/wp-content/themes/apd/card-logos/Discovery.svg">
<img class="card-logo" src="/wp-content/themes/apd/card-logos/UnionPay.svg">
<img class="card-logo" src="/wp-content/themes/apd/card-logos/JCB.svg">
</div>';
    return $description . $card_logos;
}


// Add airdrop feature
require_once( get_template_directory() . '/inc/airdrops.php' );

// Add monthly coupons subscription 
include( get_template_directory() . '/inc/monthly-subscription.php' );

function apd_get_customer_orders( $user_id ) {
    
    $result = false; 
    
    global $wpdb;
    $table = $wpdb->prefix . 'apd_customer_orders';
    
    $sql = $wpdb->prepare( "SELECT `order_ids` FROM `" . $table . "` WHERE `user_id` = %d", $user_id );
    
    $db_result = $wpdb->get_row($sql, ARRAY_A);
    
    if ( $db_result ) {
        $result = explode( ',', $db_result['order_ids'] );
    }
    
    return $result;
}

function apd_add_customer_order_id( $user_id, $order_id ) {
    
    global $wpdb;
    $table = $wpdb->prefix . 'apd_customer_orders';
        
    $customer_order_ids = apd_get_customer_orders($user_id);
    
    // apd_log( 'apd_get_customer_orders ::: ' . $user_id . print_r($customer_order_ids,1) );
    
    if ( is_array( $customer_order_ids ) && ! in_array( $order_id, $customer_order_ids ) ) {
        
        $customer_order_ids[] = $order_id;
        
        $sql = $wpdb->prepare( "UPDATE `" . $table . "` SET `order_ids` = %s WHERE `user_id` = %d", implode( ',', $customer_order_ids ) , $user_id );
        
        // apd_log( 'apd_add_customer_order_id ::: ' . $user_id . ' ::: $order_id ' . $order_id . ':::' . $sql );

        $wpdb->query( $sql );
        
        return 2;
    }
    
    if ( $customer_order_ids === false ) {
        $sql = $wpdb->prepare( "INSERT INTO `" . $table . "` (user_id,order_ids) VALUES (%d,%s)", $user_id, $order_id );
        // apd_log( 'apd_add_customer_order_id ::: ' . $user_id . ' ::: $order_id ' . $order_id . ':::' . $sql );
        $wpdb->query( $sql );
        
        return 1;
    }
}


function action_woocommerce_order_status_changed( $order_id, $status_from, $status_to, $instance ) { 
    $statuses = array( 'completed', 'cancelled', 'refunded', 'failed');
        
        // apd_log( '$order_id<pre>' . $order_id . '</pre>---' . $status_from . '===>>>' . $status_to);
        
        if ( in_array( $status_to, $statuses ) || in_array( $status_from, $statuses ) ) {
            $user_id = get_current_user_id();
            // apd_log( " apd_add_customer_order_id( $user_id, $order_id ); " );
            
            apd_add_customer_order_id( $user_id, $order_id );
        }
} 
         
add_action( 'woocommerce_order_status_changed', 'action_woocommerce_order_status_changed', 10, 4 ); 



function apd_find_customers_without_ids( $limit ) {
    
    global $wpdb;
    $table_to = $wpdb->prefix . 'apd_customer_orders';
    
    $sql = "SELECT wp_users.ID "
  . " FROM `wp_users` "
    . " WHERE wp_users.ID NOT IN (SELECT `user_id` FROM `" . $table_to . "`) LIMIT " . $limit;
    
    $db_result = $wpdb->get_results($sql, ARRAY_A);
    
    $users = array();
                    
    if ( is_array($db_result) ) {
        foreach ( $db_result as $row ) {
            $users[] = $row['ID'];
        }
    }
    
    return $users;
}

function apd_find_customers_order_ids( $user_id ) {
    
    
    global $wpdb;
    //$table_to = $wpdb->prefix . 'apd_customer_orders';
    
    $sql = $wpdb->prepare( "SELECT wp_posts.ID "
  . " FROM wp_posts  INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) "
    . " WHERE wp_posts.post_type = 'shop_order' "
    . " AND ( wp_postmeta.meta_key = '_customer_user' AND wp_postmeta.meta_value = %d ) "
    . " GROUP BY wp_posts.ID",  $user_id );
    
    $db_result = $wpdb->get_results($sql, ARRAY_A);
    
    
    $orders = array();
    
    if ( is_array($db_result) ) {
        foreach ( $db_result as $row ) {
            $orders[] = $row['ID'];
        }
    }
    
    return $orders;
}


function apd_fill_customer_order_id( $user_id, $order_ids ) {
    
    global $wpdb;
    $table_to = $wpdb->prefix . 'apd_customer_orders';
    
    $sql = $wpdb->prepare( "INSERT INTO `" . $table_to . "` (user_id,order_ids) VALUES (%d,%s)", $user_id, implode( ',', $order_ids ) );
        
    $wpdb->query( $sql );
}


function apd_log( $data ) {

    $filename = pathinfo( __FILE__, PATHINFO_DIRNAME ) . DIRECTORY_SEPARATOR . 'log.txt';
    if ( isset( $_GET['aops_log_to_screen'] ) && $_GET['aops_log_to_screen'] == 1 ) {
        echo( 'log::<pre>' . print_r( $data, 1 ) . '</pre>' );
    } else {
        file_put_contents( $filename, date( "Y-m-d H:i:s" ) . " | " . print_r( $data, 1 ) . "\r\n\r\n", FILE_APPEND );
    }
}


/*
if ( isset($_GET['ttt'])) {
    $result = apd_find_customers_without_ids();
    
    $i = 0;
    foreach ( $result  as $row ) {
        $user_id = $row['ID'];
        
        $orders_ids = apd_find_customers_order_ids( $user_id );
        echo('<pre>' . print_r($orders_ids, 1) . '</pre>');
        
        apd_fill_customer_order_id( $user_id, $orders_ids );
        
        $i++;
        
        if ( $i > 80 ) break;
    }
    
    die();
}
*/

function apd_find_all_customers_order_ids( $user_ids ) {
    
    
    global $wpdb;
    //$table_to = $wpdb->prefix . 'apd_customer_orders';
    
    $sql = "SELECT ID, meta_value "
  . " FROM wp_apd_customer_orders_raw WHERE meta_value IN (" . implode( ',', $user_ids ) . ") ";
    
    $db_result = $wpdb->get_results($sql, ARRAY_A);
    
    $orders = array();
    
    if ( is_array($db_result) ) {
        foreach ( $db_result as $row ) {
            $order_id = $row['ID'];
            $user_id = $row['meta_value'];
            
            if ( isset( $orders[$user_id] ) ) {
                $orders[$user_id][] = $order_id;
            }
            else {
                $orders[$user_id] = array($order_id);
            }
        }
        
    }
    
    return $orders;
}


/**
 * Check if there are several copies of same product in the cart 
 * 
 * @return void
 */
function apd_disable_double_purchase() {

    $cart = WC()->instance()->cart;
    $cart_items = $cart->get_cart();
    
    $product_ids = array();
    
    // Loop through cart items
    foreach( $cart_items as $cart_item_key => $cart_item ) { // check if every product in cart is an unique
        if ( in_array( $cart_item['product_id'] , $product_ids ) ) { // this product is already in the cart
            $cart->set_quantity($cart_item_key, 0);
            
             
            $product_name = $cart_item['data']->get_name();
            echo('<div class="woocommerce-message">A duplicate item "' . $product_name . '" has been removed from your order</div>');
        
        }
        else {
            $product_ids[] = $cart_item['product_id'];
        }
    }
    
    if ( count($product_ids) == count(array_unique($product_ids)) ) { 
        // all OK
    }
    else {
        wp_die("Something went wrong. Error code #11 ");
    }
    
}


add_action( 'woocommerce_before_checkout_form_cart_notices', 'apd_disable_double_purchase' );


function apd_find_recent_missing_order_ids() {
    
    
    global $wpdb;
    //$table_to = $wpdb->prefix . 'apd_customer_orders';
    
    $sql = "SELECT wp_posts.ID, wp_postmeta.meta_value "
    . " FROM wp_posts  INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) "
    . " WHERE wp_posts.post_type = 'shop_order' AND `ID` > 1513464  " //  `post_date` > '2022-06-16 00:00:00' "
    . " AND ( wp_postmeta.meta_key = '_customer_user' )";
    
    $db_result = $wpdb->get_results($sql, ARRAY_A);
    
    //echo('$db_result<pre>' . print_r($db_result, 1) . '</pre>');
    if ( is_array($db_result) ) {
        foreach ( $db_result as $row ) {
            $order_id = $row['ID'];
            $user_id = $row['meta_value'];
            
            echo('ADD <pre>' . print_r($user_id, 1) . '</pre> --- $order_id ' . $order_id . '<br>');
            apd_add_customer_order_id( $user_id, $order_id );
        }
    }
}

if ( isset($_GET['ddd'])) {
    
    apd_find_recent_missing_order_ids();
    /*
    $user_ids = apd_find_customers_without_ids( 100 );
    

    $orders = apd_find_all_customers_order_ids( $user_ids );
    
    foreach ( $orders as $user_id => $order_ids ) {
        apd_fill_customer_order_id( $user_id, $order_ids );
    }
     * 
     */
    //echo('$orders<pre>' . print_r($orders, 1) . '</pre>');

    die();
}

/**
 * Monthly coupon validation
 * 
 * @return void
 */
function coupon_validation($valid, $coupon) {
    $coupon_date = $coupon->get_date_expires();
    if ( empty( $coupon_date ) ) {
        return $valid;
    }
    $expiration_year = $coupon_date->date_i18n('y');
    $expiration_month = $coupon_date->date_i18n('m');
    $current_year = date('y');
	$current_month = date('m');
	
	if ( $expiration_year !== $current_year || $expiration_month !== $current_month ) {
		$valid = false;
	}
	
	return $valid;

}


add_filter( 'woocommerce_coupon_is_valid', 'coupon_validation', 10, 2);

/**
 * Coupon validation error notification customization
 * 
 * @return void
 */
function coupon_validation_error($err, $err_code, $coupon) {
    $coupon_code = $coupon->get_code();
    $coupon_date = $coupon->get_date_expires();
    if ( empty( $coupon_date )) {
        return $err;
    }
    $expiration_year = $coupon_date->date_i18n('y');
    $expiration_month = $coupon_date->date_i18n('F');
	if ( intval($err_code) === WC_COUPON::E_WC_COUPON_INVALID_FILTERED ) {
		$err = __( "Coupon $coupon_code exceeds available usage. This is available in $expiration_month, 20$expiration_year", "woocommerce" );
	}
	return $err;

}

add_filter( 'woocommerce_coupon_error', 'coupon_validation_error', 10, 3);
