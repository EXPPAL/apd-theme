<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>
<?php get_header('shop'); ?>

<?php require_once get_template_directory() . '/inc/component-hero-carusel.php'; ?>

    	<style type="text/css">
        /* Tech Css  */

    	body.page-id-46 {
    		background-color: black;
		}
       .page-id-46 .shop-carusel .carusel-wrapper .arrow-left, .page-id-46 .shop-carusel .carusel-wrapper .arrow-right {
            display: none !important;
        }

        .page-id-46 .viewport {
            padding: 0 140px !important;
        }

        .page-id-46 .owl-nav {
            position: absolute;
            top: 0;
            left: -7.5%;
            width: calc(100% + 15%);
            height: 100%;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -ms-flex-align: center;
            align-items: center;
            z-index: -1;
        }

        .page-id-46 button.owl-prev {
            background-image: url(https://audioplugin.deals/wp-content/themes/apd/images/shop/shop-carusel-arrow-left.png) !important;
            background-position: left center !important;
            background-repeat: no-repeat !important;
        }

        .page-id-46 button.owl-next {
            background-image: url(https://audioplugin.deals/wp-content/themes/apd/images/shop/shop-carusel-arrow-right.png) !important;
            background-position: right center !important;
            background-repeat: no-repeat !important;
        }

        .page-id-46 button.owl-prev span, .page-id-46 button.owl-next span {
            display: none;
        }

        .page-id-46 button.owl-prev, .page-id-46 button.owl-next {
            width: 7.5%;
            padding: 0 !important;
            height: 100%;
        }

        .page-id-46 button.owl-prev:hover, .page-id-46 button.owl-next:hover, .page-id-46 button.owl-next:hover,
        .page-id-46 button.owl-prev:focus, .page-id-46 button.owl-next:focus, .page-id-46 button.owl-next:focus,
        .page-id-46 button.owl-prev:active, .page-id-46 button.owl-next:active, .page-id-46 button.owl-next:active {
            background-color: transparent !important;
            outline: none !important;
            border: 0 !important;
            box-shadow: none;
        }

        .filter-menu-dropdown, .filter-menu-bottom {
            z-index: 3;
        }

        @media only screen and (max-width: 1370px) {
            .page-id-46 .carusel-wrapper .viewport {
                padding: 0 80px !important;
            }

            .page-id-46 .owl-nav {
                left: -4.5%;
                width: calc(100% + 7%);
            }
        }

        @media only screen and (max-width: 1199px) {
            .page-id-46 .carusel-wrapper .viewport {
                padding: 0 50px !important;
            }

        }

        @media only screen and (max-width: 767px) {
            body.page-id-46 .carusel-wrapper .viewport {
                padding: 0 20px !important;
            }
        }
                                  
.val-btn {
    margin-bottom: 15px;
}

.val-btn a {
    background-color: red !important;
    color: #fff !important;
    font-size: 22px !important;
    font-weight: 500 !important;
    font-family: 'Montserrat', sans-serif !important;
    padding: 20px 60px !important;
}

.val-btn a:hover {
    background-color: #2A883A !important;
}
ul.no_bullet {
list-style-type: none;
padding: 0;
margin: 0;
}
li.cinco {
	background: url('https://audioplugin.deals/wp-content/uploads/2022/05/apd-is-5.png') no-repeat left top;;
	height: 54px;
   padding-left: 44px;
   padding-top: 3px;
}

@media (max-width: 1200px) {
    .old-price {
        font-size: 32px;
    }
    
    .download-for-free-btn {
        display: block !important;
    }
    
    .download-for-free-btn a {
        display: block !important;
        padding: 15px 30px !important;
        font-size: 16px !important;
    }
     .val-btn {
        display: block !important;
    }
    
    .val-btn a {
        display: block !important;
        padding: 15px 30px !important;
        font-size: 16px !important;
    }
    
}

    </style>

<?php require_once get_template_directory() . '/inc/top-dropdown.php'; ?>

<?php $exclude_products = carbon_get_theme_option('_shop_carousels_product_exclusions');
$exclude_product_ids = wp_list_pluck($exclude_products, 'id'); ?>

                                  <div class="vc_row wpb_row vc_row-fluid my-section vc_custom_1641980418454 vc_row-has-fill vc_column-gap-10" style="    background-color: black;"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner"><div class="wpb_wrapper"><div class="vc_row wpb_row vc_inner vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner"><div class="wpb_wrapper">
	<div class="wpb_text_column wpb_content_element  vc_custom_1644328638008">
		<div class="wpb_wrapper">
		<div style="text-align: center; color: WHITE; background-color: black;display:none;"><span style="font-size: 2.5em; font-weight: bold;"><strong><i class="fa-solid fa-spider-black-widow"></i> OCTOBER HALLOWEEN MADNESS <i class="fa-solid fa-spider-black-widow"></i></strong></span></div>
<p style="font-size: 1.2em; font-weight: bold; text-transform: uppercase; text-align: center; margin-bottom: 30px;margin-top:30px;display:none;">Treat yourself to 400+ freakishly good Halloween deals in the shop.<br> Thats right, all products in the shop are at Un-BOO-lievable base price. <br>
No Rewards Money needed!</p>
		<p style="font-size: 2.5em; font-weight: bold; text-transform: uppercase; text-align: center; margin-bottom:40px;margin-top:40px;display:none;">Get 15% OFF Storewide!<br />Use code <span style="color:#2883c9;">APD-BACK2SCHOOL22</span> at checkout.</p>
<div style="text-align: center; color: #d83e1f; margin-top:20px; margin-bottom:20px;"><span style="font-size: 2.5em; font-weight: bold; text-transform: uppercase;">TOP RECOMMENDATIONS<br />
IN THE SHOP!</span></div>

			

		</div>
	</div>
</div></div></div></div><div class="vc_row wpb_row vc_inner vc_row-fluid my-section-inner vc_custom_1644317253308 vc_row-has-fill vc_row-o-content-middle vc_row-flex" style="text-align: center;"><div class="wpb_column vc_column_container vc_col-sm-4" style="margin-bottom:20px;"><div class="vc_column-inner"><div class="wpb_wrapper">
	<div class="wpb_text_column wpb_content_element ">
		<div class="wpb_wrapper">
			<h3 style="font-weight: bold;">MOST POPULAR</h3>
<span style="font-size: 16px; font-weight: bold;"><a href="https://audioplugin.deals/unstrung-by-fallout-music-group/" target="_blank" rel="noopener"><span style="color: #2773c9;">UNSTRUNG by Fallout Music Group</span></a><br />
<a href="https://audioplugin.deals/arsenal-by-sampletraxx/" target="_blank" rel="noopener"><span style="color: #2773c9;">ARSENAL by Sampletraxx</span></a><br />
<a href="https://audioplugin.deals/deep-blue-by-dark-intervals/" target="_blank" rel="noopener"><span style="color: #2773c9;">Deep Blue by Dark Intervals</span></a><br />
<a href="https://audioplugin.deals/boom-library-cinematic-horror-designed/" target="_blank" rel="noopener"><span style="color: #2773c9;">Cinematic Horror Designed by BOOM Library</span></a><br />
<a href="https://audioplugin.deals/soundiron-horror-collection/" target="_blank" rel="noopener"><span style="color: #2773c9;">Soundiron Horror Collection</span></a><br />
<a href="https://audioplugin.deals/shop/all-products" target="_blank" rel="noopener"><span style="font-size: 16px; font-weight: bold; color: #2773c9;">SEE MORE!</span></a></span>

		</div>
	</div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-4" style="margin-bottom:20px;"><div class="vc_column-inner"><div class="wpb_wrapper">
	<div class="wpb_text_column wpb_content_element ">
		<div class="wpb_wrapper">
			<h3 style="font-weight: bold;">BARGAIN BUNDLES</h3>
<span style="font-size: 16px; font-weight: bold;"><a href="https://audioplugin.deals/magnetism-vol-1-2-bundle-by-sonora-cinematic/" target="_blank" rel="noopener"><span style="color: #2773c9;">Magnetism Vol 1 & 2 Bundle by Sonora Cinematic</span></a><br />
<a href="https://audioplugin.deals/orchestral-woodwinds-world-flutes-bundle-by-gothic-instruments/" target="_blank" rel="noopener"><span style="color: #2773c9;">Orchestral Woodwinds & World Flutes Bundle by Gothic Instruments</span></a><br />
<a href="https://audioplugin.deals/rare-pianos-bundle-by-realsamples/" target="_blank" rel="noopener"><span style="color: #2773c9;">Rare Pianos Bundle by Realsamples</span></a><br />
<a href="https://audioplugin.deals/eduardo-tarilonte-voices-bundle/" target="_blank" rel="noopener"><span style="color: #2773c9;">Eduardo Tarilonte's Voices Bundle</span></a><br />
<a href="https://audioplugin.deals/cinematic-bundle-by-dark-intervals/" target="_blank" rel="noopener"><span style="color: #2773c9;">Dark Interval's Cinematic Bundle</span></a><br />
<a href="https://audioplugin.deals/search/?search=bundle" target="_blank" rel="noopener"><span style="font-size: 16px; font-weight: bold; color: #2773c9;">SEE MORE!</span></a></span>

		</div>
	</div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-4"><div class="vc_column-inner"><div class="wpb_wrapper">
	<div class="wpb_text_column wpb_content_element ">
		<div class="wpb_wrapper">
			<h3 style="font-weight: bold;">TOP DEVELOPERS</h3>
<span style="font-size: 16px; font-weight: bold;">
<a href="https://audioplugin.deals/developer/best-service/" target="_blank" rel="noopener"><span style="color: #2773c9;">BEST SERVICE</span></a></span><br />
<a href="https://audioplugin.deals/developer/waldorf/" target="_blank" rel="noopener"><span style="color: #2773c9;font-weight: bold;">WALDORF</span></a><br />
<a href="https://audioplugin.deals/developer/frozen-plain/" target="_blank" rel="noopener"><span style="color: #2773c9;font-weight: bold;">FROZEN PLAIN</span></a><br />
<a href="https://audioplugin.deals/developer/sample-logic/" target="_blank" rel="noopener"><span style="color: #2773c9;font-weight: bold;">SAMPLE LOGIC</span></a><br />
<a href="https://audioplugin.deals/developer/rigid-audio/" target="_blank" rel="noopener"><span style="color: #2773c9;font-weight: bold;">RIGID AUDIO</span></a><br />
<a href="https://audioplugin.deals/shop/all-products" target="_blank" rel="noopener"><span style="font-size: 16px; font-weight: bold; color: #2773c9;">SEE MORE!</span></a>

		</div>
	</div>
</div></div></div></div><div class="vc_row wpb_row vc_inner vc_row-fluid vc_custom_1644317238880"><div class="wpb_column vc_column_container vc_col-sm-4"><div class="vc_column-inner"><div class="wpb_wrapper"><div class="vc_empty_space" style="height: 12px"><span class="vc_empty_space_inner"></span></div></div></div></div><div class="wpb_column vc_column_container vc_col-sm-4"><div class="vc_column-inner"  style="padding-right:0px;display:none;"><div class="wpb_wrapper" style="text-align:center"><div style="margin: 40px;font-size: 26px;">PROMO ENDS IN...</div><img src="https://s.mmgo.io/t/Cb32" alt="back to school promo ends soon" style="" /><p style="font-weight:bold;text-transform:uppercase;font-size:1.5em;margin-top:20px;">Click button below to quickly find best deals in the shop!</p><div class="vc_btn3-container  val-btn vc_btn3-center"><a style="margin-top: 20px;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-modern vc_btn3-color-default" href="https://audioplugin.deals/apd-shop-cheat-sheet-for-black-friday-2022/" title="APD SHOP CHEAT SHEET!">CHECK OUT APD SHOP CHEAT SHEET!</a></div></div></div></div><div class="wpb_column vc_column_container vc_col-sm-4"><div class="vc_column-inner"><div class="wpb_wrapper"><div class="vc_empty_space" style="height: 12px"><span class="vc_empty_space_inner"></span></div></div></div></div></div></div></div></div></div>
                                  
    <section class="shop-carusel">
        <div class="top-ribbon">
            <h2><a href="/new-arrivals/">New Arrivals</a>
                <!-- a href="<?php echo get_permalink(get_page_by_path("new-arrivals")) ?>">new arrivals</a --></h2>
        </div>
        <div class="carusel-wrapper">
            <div class="arrow-left"></div>
            <div class="viewport">
                <?php
                //$term     = get_term_by( 'name', 'Gift Certificate', 'product_cat' );
                $params = array(
                    'posts_per_page' => 20,
                    'order' => 'DESC',
                    'post_type' => 'product',
                    'post__not_in' => $exclude_product_ids,
                    'meta_query' => array(
                        array(
                            'key' => '_product_type_single',
                            'value' => 'yes',
                            'compare' => '=',
                        ),
                    ),
                );
                $wc_query = new WP_Query($params);
                ?>
                <ul class="Blue-Light-Specialsowl1 owl-carousel owl-theme">
                    <?php if ($wc_query->have_posts()) : // (3) ?>
                        <?php while ($wc_query->have_posts()) : // (4)
                            global $product;
                            $wc_query->the_post(); // (4.1)
                            $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
                            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
                            /*$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );*/
                            $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, array(150, 210));
                            $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
                            $link = '';
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
                            ?>
                            <li class="item <?php echo APD_Rating_System::is_top_rated($product->get_id()) ? 'top-rated' : ''; ?>">
                                <?php
                                if ($product->get_sale_price()) {
                                    echo '<span class="sticker-procentage">SALE</span>';
                                }
                                ?>
                                <a href="<?php echo $link; ?>">
                                    <img width="150" height="210" src="<?php echo $full_size_image[0]; ?>" alt="">
                                </a>
                                <div class="product-details-popup">
                                    <header>
                                        <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                        <div class="meta" style="margin-bottom: 15px;">
                                            <?php APD_Rating_System::product_rating_one_line_html($product->get_id()); ?>
                                        </div>
                                    </header>

                                    <article>
                                        <p><?php the_excerpt(); ?></p>
                                    </article>

                                    <footer>
                                        <span class="title-price">current price</span>
                                        <span class="value-price">$<?php echo ($product->get_sale_price()) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                        <span class="value-base-price">base price <em>$<?php echo get_post_meta(get_the_ID(), '_minumum_price', true); ?></em></span>
                                        <div class="action-holder">
                                            <a class="btn-more-detail" href="<?php echo $link; ?>">More details</a>
                                        </div>
                                        <div class="action-holder">
                                            <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                        </div>
                                    </footer>
                                </div><!-- END .product-details-popup -->
                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); // (5) ?>
                    <?php else: ?>
                        <p>
                            <?php _e('No Products'); // (6) ?>
                        </p>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="arrow-right"></div>
        </div>
    </section>
    <section class="shop-carusel">
        <div class="top-ribbon">
            <h2 data-icon="bluelight">
                <?php // $term = get_term_by( 'name', 'Blue Light Specials', 'product_cat' ); ?>
                <!-- a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a -->
                <a href="/blue-light-specials/">Blue Light Specials</a>
            </h2>
        </div>
        <div class="carusel-wrapper">
            <div class="arrow-left"></div>
            <div class="viewport">
                <?php
                $params = array(
                    'posts_per_page' => 20,
                    'post_type' => 'product',
                    'post__not_in' => $exclude_product_ids,
                    'orderby' => array(
                        'order_clause' => 'DESC',
                        //'order_clause2' => 'DESC',
                    ),
                    'meta_query' => array(
                        'relation' => 'AND',
                        'order_clause' => array( // Simple products type
                            'key' => '_custom_percentage_price',
                            'value' => 1,
                            'compare' => '>',
                            'type' => 'numeric'
                        ),
                        /*
                        'order_clause2' => array( // Simple products type
                            'key'           => '_regular_price',
                            'value'         => 1,
                            'compare'       => '>',
                            'type'          => 'numeric'
                        ),
                        */
                        array(
                            'key' => '_product_type_single',
                            'value' => 'yes',
                            'compare' => '=',
                        )
                    ),
                );
                $wc_query = new WP_Query($params);

                ?>
                <ul class="Blue-Light-Specialsowl owl-carousel owl-theme">
                    <?php if ($wc_query->have_posts()) : // (3) ?>
                        <?php while ($wc_query->have_posts()) : // (4)
                            global $product;
                            $wc_query->the_post(); // (4.1)
                            $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
                            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
                            $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, array(150, 210));
                            $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
                            $link = '';
                            $args = array(
                                'post_type' => 'page',
                                'meta_query' => array(
                                    array(
                                        'key' => 'meta_product_id',
                                        'value' => $product->get_id(),
                                        'compare' => '=',
                                    ),
                                )
                            );
                            $query = new WP_Query($args);
                            $link = get_permalink($query->posts[0]->ID);
                            ?>
                            <li class="item <?php echo APD_Rating_System::is_top_rated($product->get_id()) ? 'top-rated' : ''; ?>">
                                <?php
                                if ($product->get_sale_price()) {
                                    echo '<span class="sticker-procentage">SALE</span>';
                                }
                                ?>
                                <a href="<?php echo $link; ?>">
                                    <img width="150" height="210" src="<?php echo $full_size_image[0]; ?>" alt="">
                                </a>
                                <div class="product-details-popup">
                                    <header>
                                        <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                        <div class="meta" style="margin-bottom: 15px;">
                                            <?php APD_Rating_System::product_rating_one_line_html($product->get_id()); ?>
                                        </div>
                                    </header>

                                    <article>
                                        <p><?php the_excerpt(); ?></p>
                                    </article>

                                    <footer>
                                        <span class="title-price">current price</span>
                                        <span class="value-price">$<?php echo ($product->get_sale_price()) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                        <span class="value-base-price">base price <em>$<?php echo get_post_meta(get_the_ID(), '_minumum_price', true); ?></em></span>
                                        <div class="action-holder">
                                            <a class="btn-more-detail" href="<?php echo $link; ?>">More details</a>
                                        </div>
                                        <div class="action-holder">
                                            <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                        </div>
                                    </footer>
                                </div><!-- END .product-details-popup -->
                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); // (5) ?>
                    <?php else: ?>
                        <p>
                            <?php _e('No Products'); // (6) ?>
                        </p>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="arrow-right"></div>
        </div>
    </section>

<?php
// APD_Rating_System::recalculate_rating_list();
$top_rated_list = APD_Rating_System::get_rating_list();
if (is_array($top_rated_list) && !empty($top_rated_list)): ?>
    <section class="shop-carusel">
        <div class="top-ribbon">
            <h2><a name="top-rated">Top Rated</a></h2>
        </div>
        <div class="carusel-wrapper">
            <div class="arrow-left"></div>
            <div class="viewport">
                <?php
                // var_dump($top_rated_list);
                $params = array(
                    'post_type' => 'product',
                    'post__in' => array_diff($top_rated_list, $exclude_product_ids),
                    'posts_per_page' => 20,
                    'order' => 'ASC',
                    'orderby' => 'post__in',
//                    'meta_query' => array(
//                        array(
//                            'key' => '_product_type_single',
//                            'value' => 'yes',
//                            'compare' => '=',
//                        )
//                    )
                );
                $wc_query = new WP_Query($params);
                ?>
                <ul class="Blue-Light-Specialsowl1 owl-carousel owl-theme">
                    <?php if ($wc_query->have_posts()) : // (3) ?>
                        <?php while ($wc_query->have_posts()) : // (4)
                            global $product;
                            $wc_query->the_post(); // (4.1)
                            $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
                            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
                            /*$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );*/
                            $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, array(150, 210));
                            $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
                            $link = '';
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
                            ?>
                            <li class="item">
                                <?php if ($product->get_sale_price()) {
                                    echo '<span class="sticker-procentage">SALE</span>';
                                } ?>
                                <a href="<?php echo $link; ?>">
                                    <img width="150" height="210" src="<?php echo $full_size_image[0]; ?>" alt="">
                                </a>
                                <div class="product-details-popup">
                                    <header>
                                        <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                        <div class="meta" style="margin-bottom: 15px;">
                                            <?php APD_Rating_System::product_rating_one_line_html($product->get_id()); ?>
                                        </div>
                                    </header>
                                    <article>
                                        <p><?php the_excerpt(); ?></p>
                                    </article>

                                    <footer>
                                        <span class="title-price">current price</span>
                                        <span class="value-price">$<?php echo ($product->get_sale_price()) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                        <span class="value-base-price">base price <em>$<?php echo get_post_meta(get_the_ID(), '_minumum_price', true); ?></em></span>
                                        <div class="action-holder">
                                            <a class="btn-more-detail" href="<?php echo $link; ?>">More details</a>
                                        </div>
                                        <div class="action-holder">
                                            <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                        </div>
                                    </footer>
                                </div><!-- END .product-details-popup -->
                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); // (5) ?>
                    <?php else: ?>
                        <p>
                            <?php _e('No Products'); // (6) ?>
                        </p>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="arrow-right"></div>
        </div>
    </section>
<?php endif; ?>

    <section class="shop-carusel">
        <div class="top-ribbon">
            <h2><a href="/trending/">trending now</a>
                <!-- a href="<?php echo get_permalink(get_page_by_path("trending")) ?>">trending now</a --></h2>
        </div>
        <div class="carusel-wrapper">
            <div class="arrow-left"></div>
            <div class="viewport">
                <?php
                //$term     = get_term_by( 'name', 'Gift Certificate', 'product_cat' );
                $params = array(
                    'posts_per_page' => 20,
                    'post_type' => 'product',
                    'post__not_in' => $exclude_product_ids,
                    'meta_key' => 'total_sales',
                    'orderby' => 'meta_value_num',
                    'meta_query' => array(
                        array(
                            'key' => '_product_type_single',
                            'value' => 'yes',
                            'compare' => '=',
                        )
                    )
                );
                $wc_query = new WP_Query($params);

                ?>
                <ul class="Blue-Light-Specialsowl2 owl-carousel owl-theme">
                    <?php if ($wc_query->have_posts()) : // (3) ?>
                        <?php while ($wc_query->have_posts()) : // (4)
                            global $product;

                            $wc_query->the_post(); // (4.1)
                            if (get_post_meta(get_the_ID(), '_product_type_single', true) != 'yes') {
                                continue;
                            }
                            $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
                            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
                            /*$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );*/
                            $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, array(150, 210));
                            $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
                            $link = '';
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
                            ?>
                            <li class="item <?php echo APD_Rating_System::is_top_rated($product->get_id()) ? 'top-rated' : ''; ?>">
                                <?php
                                if ($product->get_sale_price()) {
                                    echo '<span class="sticker-procentage">SALE</span>';
                                }
                                ?>
                                <a href="">
                                    <img width="150" height="210" src="<?php echo $full_size_image[0]; ?>" alt="">
                                </a>
                                <div class="product-details-popup">
                                    <header>
                                        <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                        <div class="meta" style="margin-bottom: 15px;">
                                            <?php APD_Rating_System::product_rating_one_line_html($product->get_id()); ?>
                                        </div>
                                    </header>

                                    <article>
                                        <p><?php the_excerpt(); ?></p>
                                    </article>

                                    <footer>
                                        <span class="title-price">current price</span>
                                        <span class="value-price">$<?php echo ($product->get_sale_price()) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                        <span class="value-base-price">base price <em>$<?php echo get_post_meta(get_the_ID(), '_minumum_price', true); ?></em></span>
                                        <div class="action-holder">
                                            <a class="btn-more-detail" href="<?php echo $link; ?>">More details</a>
                                        </div>

                                        <div class="action-holder">
                                            <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                        </div>

                                    </footer>
                                </div><!-- END .product-details-popup -->
                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); // (5) ?>
                    <?php else: ?>
                        <p>
                            <?php _e('No Products'); // (6) ?>
                        </p>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="arrow-right"></div>
        </div>
    </section>
    <section class="shop-carusel">
        <div class="top-ribbon">
            <h2 style="border-bottom:0px!important;">
                <?php $term = get_term_by('name', 'Gift Certificate', 'product_cat'); ?>
                <!-- a href="<?php // echo get_term_link( $term ); ?>"><?php // echo $term->name; ?></a --> Gift
                Certificates
            </h2>
        </div>
        <div class="carusel-wrapper">
            <?php
            $params = array(
                'posts_per_page' => -1,
                'post_type' => 'product',
                'post__not_in' => $exclude_product_ids,
                'meta_query' => array(
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
                ),
                'stock' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms' => $term->term_id,
                        'operator' => 'IN'
                    ),
                ),
                'orderby' => 'rand',
            );
            $wc_query = new WP_Query($params);

            ?>
            <?php if ($wc_query->have_posts()) : // (3) ?>
                <div class="arrow-left"></div>
                <div class="viewport">

                    <ul class="Blue-Light-Specialsowl4 owl-carousel owl-theme">

                        <?php while ($wc_query->have_posts()) : // (4)
                            global $product;

                            $wc_query->the_post(); // (4.1)
                            $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
                            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
                            /*$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );*/
                            $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, array(150, 210));
                            $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
                            $link = '';
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
                            ?>
                            <li class="item <?php echo APD_Rating_System::is_top_rated($product->get_id()) ? 'top-rated' : ''; ?>">
                                <?php
                                if ($product->get_sale_price()) {
                                    echo '<span class="sticker-procentage">SALE</span>';
                                }
                                ?>
                                <a href="<?php echo get_permalink($link); ?>">
                                    <img width="150" height="210" src="<?php echo $full_size_image[0]; ?>" alt="">
                                </a>
                                <div class="product-details-popup">
                                    <header>
                                        <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                        <div class="meta" style="margin-bottom: 15px;">
                                            <?php APD_Rating_System::product_rating_one_line_html($product->get_id()); ?>
                                        </div>
                                    </header>

                                    <article>
                                        <p><?php the_excerpt(); ?></p>
                                    </article>

                                    <footer>
                                        <span class="title-price">current price</span>
                                        <span class="value-price">$<?php echo ($product->get_sale_price()) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                        <span class="value-base-price">base price <em>$<?php echo get_post_meta(get_the_ID(), '_minumum_price', true); ?></em></span>
                                        <div class="action-holder">
                                            <a class="btn-more-detail" href="<?php echo $link; ?>">More details</a>
                                        </div>
                                        <div class="action-holder">
                                            <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                        </div>
                                    </footer>
                                </div><!-- END .product-details-popup -->
                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); // (5) ?>

                    </ul>
                </div>

                <div class="arrow-right"></div>
            <?php else: ?>
                <p>
                    <?php _e('No Products'); // (6) ?>
                </p>
            <?php endif; ?>
        </div>
    </section>
<?php // require_once get_template_directory() . '/inc/bottom-menu.php'; ?>
    <div class='product-overlay' style="z-index:9999!important;"></div>

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