<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<?php get_header( 'shop' ); ?>
<section class="hero-banner">
    <div class="mask"></div>
    <button class="btn-open-video"></button>
    <section class="video-popup">
        <div class="wrapper-video">
            <div class="action-holder">
                <button data-action="open-short-video">Play short video (0:53)</button>
                <button data-action="open-long-video">Play long video (4:06)</button>
            </div>
            <div class="viewport short-video">
                <iframe class="video" src="https://www.youtube.com/embed/TMeLrvmGiVQ" frameborder="0" allowfullscreen></iframe>
            </div><!-- end .viewport -->
            <div class="viewport long-video">
                <iframe class="video" src="https://www.youtube.com/embed/AJPll1n-o_k" frameborder="0" allowfullscreen></iframe>
            </div><!-- end .viewport -->
        </div>
    </section>
</section><!-- end .hero-banner -->
<section class="subhero-ribbon">
    <div class="logo-holder">
        <a href="<?php echo get_permalink( get_page_by_path( 'shop' ) ); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/images/shop/logo_horizontal.png" alt="logo"></a>
    </div>
    <div class="filters">
        <button aria-label="open filter" data-action="open-filter">browse</button>
    </div>
	<?php require_once get_template_directory() . '/inc/top-dropdown.php'; ?>
	<?php /*require_once get_template_directory() . '/inc/search-box.php'; */?>
</section>
<section class="shop-carusel">
    <div class="top-ribbon">
        <h2 data-icon="bluelight">
			<?php $term = get_term_by( 'name', 'Blue Light Specials', 'product_cat' ); ?>
            <a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a>
        </h2>
    </div>
    <div class="carusel-wrapper">
        <div class="arrow-left"></div>
        <div class="viewport">
			<?php
			$params   = array(
				'posts_per_page' => - 1,
				'post_type'      => 'product',
				'stock'          => 1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_cat',
						'terms'    => $term->term_id,
						'operator' => 'IN'
					),
				),
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => '_product_type_single',
						'value'   => 'yes',
						'compare' => '=',
					),
					'sort'     => array(
						'key'     => '_blue_light_sort',
						'compare' => 'EXISTS',
					),
				),
				'orderby'        => array( 'sort' => 'ASC', 'date' => 'DESC' ),

			);
			$wc_query = new WP_Query( $params );

			?>
            <ul>
				<?php if ( $wc_query->have_posts() ) : // (3) ?>
					<?php while ( $wc_query->have_posts() ) : // (4)
						global $product;
						$wc_query->the_post(); // (4.1)
						$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
						$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
						$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
						$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
						$link              = '';
						$args              = array(
							'post_type'  => 'page',
							'meta_query' => array(
								array(
									'key'     => 'meta_product_id',
									'value'   => $product->get_id(),
									'compare' => '=',
								)
							)
						);
						$query             = new WP_Query( $args );
						$link              = get_permalink( $query->posts[0]->ID );
						?>
                        <li class="item-box">
	                        <?php
	                        if ( $product->get_sale_price() ) {
		                        echo '<span class="sticker-procentage">SALE</span>';
	                        }
	                        ?>
                            <a href="<?php echo $link; ?>"><img width="310" height="434"
                                                                src="<?php echo $full_size_image[0]; ?>"
                                                                alt=""></a>
                            <div class="product-details-popup">
                                <header>
                                    <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                </header>

                                <article>
                                    <p><?php the_excerpt(); ?></p>
                                </article>

                                <footer>
                                    <span class="title-price">current price</span>
                                    <span class="value-price">$<?php echo ( $product->get_sale_price() ) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                    <span class="value-base-price">base price <em>$<?php echo get_post_meta( get_the_ID(), '_minumum_price', true ); ?></em></span>
                                    <div class="action-holder">
                                        <a class="btn-more-detail" href="<?php echo $link; ?>">More
                                            details</a>
                                    </div>
                                    <div class="action-holder">
										<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
                                    </div>
                                </footer>
                            </div><!-- END .product-details-popup -->
                        </li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); // (5) ?>
				<?php else: ?>
                    <p>
						<?php _e( 'No Products' ); // (6) ?>
                    </p>
				<?php endif; ?>
            </ul>
        </div>
        <div class="arrow-right"></div>
    </div>
</section>
<section class="shop-carusel">
    <div class="top-ribbon">
        <h2><a href="<?php echo get_permalink( get_page_by_path( "new-arrivals" ) ) ?>">new arrivals</a></h2>
    </div>
    <div class="carusel-wrapper">
        <div class="arrow-left"></div>
        <div class="viewport">
			<?php
			$term     = get_term_by( 'name', 'Gift Certificate', 'product_cat' );
			$params   = array(
				'posts_per_page' => - 1,
				'post_type'      => 'product',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => '_product_type_single',
						'value'   => 'yes',
						'compare' => '=',
					),
					'sort'     => array(
						'key'     => '_new_arrivals_sort',
						'compare' => 'EXISTS',
					),
				),
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_cat',
						'terms'    => $term->term_id,
						'operator' => 'NOT IN'
					),
				),
				'stock'          => 1,
				'orderby'        => array( 'sort' => 'ASC', 'date' => 'DESC' ),

			);
			$wc_query = new WP_Query( $params );
			?>
            <ul>
				<?php if ( $wc_query->have_posts() ) : // (3) ?>
					<?php while ( $wc_query->have_posts() ) : // (4)
						global $product;
						$wc_query->the_post(); // (4.1)
						$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
						$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
						$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
						$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
						$link              = '';
						$args              = array(
							'post_type'  => 'page',
							'meta_query' => array(
								array(
									'key'     => 'meta_product_id',
									'value'   => $product->get_id(),
									'compare' => '=',
								)
							)
						);
						$query             = new WP_Query( $args );
						$link              = get_permalink( $query->posts[0]->ID );
						?>
                        <li class="item-box">
	                        <?php
	                        if ( $product->get_sale_price() ) {
		                        echo '<span class="sticker-procentage">SALE</span>';
	                        }
	                        ?>
                            <a href="<?php echo $link; ?>"><img width="310" height="434"
                                                                src="<?php echo $full_size_image[0]; ?>"
                                                                alt=""></a>
                            <div class="product-details-popup">
                                <header>
                                    <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                </header>

                                <article>
                                    <p><?php the_excerpt(); ?></p>
                                </article>

                                <footer>
                                    <span class="title-price">current price</span>
                                    <span class="value-price">$<?php echo ( $product->get_sale_price() ) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                    <span class="value-base-price">base price <em>$<?php echo get_post_meta( get_the_ID(), '_minumum_price', true ); ?></em></span>
                                    <div class="action-holder">
                                        <a class="btn-more-detail" href="<?php echo $link; ?>">More
                                            details</a>
                                    </div>
                                    <div class="action-holder">
										<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
                                    </div>
                                </footer>
                            </div><!-- END .product-details-popup -->
                        </li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); // (5) ?>
				<?php else: ?>
                    <p>
						<?php _e( 'No Products' ); // (6) ?>
                    </p>
				<?php endif; ?>
            </ul>
        </div>
        <div class="arrow-right"></div>
    </div>
</section>
<section class="shop-carusel">
    <div class="top-ribbon">
        <h2><a href="<?php echo get_permalink( get_page_by_path( "trending" ) ) ?>">trending now</a></h2>
    </div>
    <div class="carusel-wrapper">
        <div class="arrow-left"></div>
        <div class="viewport">
			<?php
			$term     = get_term_by( 'name', 'Gift Certificate', 'product_cat' );
			$params   = array(
				'posts_per_page' => - 1,
				'post_type'      => 'product',
				'stock'          => 1,
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => '_product_type_single',
						'value'   => 'yes',
						'compare' => '=',
					),
					'sort'     => array(
						'key'     => '_trending_sort',
						'compare' => 'EXISTS',
					),
				),
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_cat',
						'terms'    => $term->term_id,
						'operator' => 'NOT IN'
					),
				),
				'meta_key'       => 'total_sales',
				'orderby'        => array( 'sort' => 'ASC', 'meta_value_num' => 'DESC' ),
			);
			$wc_query = new WP_Query( $params );

			?>
            <ul>
				<?php if ( $wc_query->have_posts() ) : // (3) ?>
					<?php while ( $wc_query->have_posts() ) : // (4)
						global $product;

						$wc_query->the_post(); // (4.1)
						if ( get_post_meta( get_the_ID(), '_product_type_single', true ) != 'yes' ) {
							continue;
						}
						$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
						$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
						$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
						$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
						$link              = '';
						$args              = array(
							'post_type'  => 'page',
							'meta_query' => array(
								array(
									'key'     => 'meta_product_id',
									'value'   => $product->get_id(),
									'compare' => '=',
								)
							)
						);
						$query             = new WP_Query( $args );
						$link              = get_permalink( $query->posts[0]->ID );
						?>
                        <li class="item-box">
	                        <?php
	                        if ( $product->get_sale_price() ) {
		                        echo '<span class="sticker-procentage">SALE</span>';
	                        }
	                        ?>
                            <a href=""><img width="310" height="434" src="<?php echo $full_size_image[0]; ?>"
                                            alt=""></a>
                            <div class="product-details-popup">
                                <header>
                                    <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                </header>

                                <article>
                                    <p><?php the_excerpt(); ?></p>
                                </article>

                                <footer>
                                    <span class="title-price">current price</span>
                                    <span class="value-price">$<?php echo ( $product->get_sale_price() ) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                    <span class="value-base-price">base price <em>$<?php echo get_post_meta( get_the_ID(), '_minumum_price', true ); ?></em></span>
                                    <div class="action-holder">
                                        <a class="btn-more-detail" href="<?php echo $link; ?>">More
                                            details</a>
                                    </div>

                                    <div class="action-holder">
										<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
                                    </div>

                                </footer>
                            </div><!-- END .product-details-popup -->
                        </li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); // (5) ?>
				<?php else: ?>
                    <p>
						<?php _e( 'No Products' ); // (6) ?>
                    </p>
				<?php endif; ?>
            </ul>
        </div>
        <div class="arrow-right"></div>
    </div>
</section>
<section class="shop-carusel">
    <div class="top-ribbon">
        <h2>
			<?php $term = get_term_by( 'name', 'Gift Certificate', 'product_cat' ); ?>
            <a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a>
        </h2>
    </div>
    <div class="carusel-wrapper">
		<?php
		$params   = array(
			'posts_per_page' => - 1,
			'post_type'      => 'product',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_product_type_single',
					'value'   => 'yes',
					'compare' => '=',
				),
				'sort'     => array(
					'key'     => '_gift_certificates_sort',
					'compare' => 'EXISTS',
				),
			),
			'stock'          => 1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'terms'    => $term->term_id,
					'operator' => 'IN'
				),
			),
			'orderby'        => array( 'sort' => 'ASC' )
		);
		$wc_query = new WP_Query( $params );

		?>
		<?php if ( $wc_query->have_posts() ) : // (3) ?>
            <div class="arrow-left"></div>
            <div class="viewport">

                <ul>

					<?php while ( $wc_query->have_posts() ) : // (4)
						global $product;

						$wc_query->the_post(); // (4.1)
						$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
						$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
						$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
						$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
						$link              = '';
						$args              = array(
							'post_type'  => 'page',
							'meta_query' => array(
								array(
									'key'     => 'meta_product_id',
									'value'   => $product->get_id(),
									'compare' => '=',
								)
							)
						);
						$query             = new WP_Query( $args );
						$link              = get_permalink( $query->posts[0]->ID );
						?>
                        <li class="item-box">
	                        <?php
	                        if ( $product->get_sale_price() ) {
		                        echo '<span class="sticker-procentage">SALE</span>';
	                        }
	                        ?>
                            <a href="<?php echo get_permalink( $link ); ?>"><img width="310" height="434"
                                                                                 src="<?php echo $full_size_image[0]; ?>"
                                                                                 alt=""></a>
                            <div class="product-details-popup">
                                <header>
                                    <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
                                </header>

                                <article>
                                    <p><?php the_excerpt(); ?></p>
                                </article>

                                <footer>
                                    <span class="title-price">current price</span>
                                    <span class="value-price">$<?php echo ( $product->get_sale_price() ) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                    <span class="value-base-price">base price <em>$<?php echo get_post_meta( get_the_ID(), '_minumum_price', true ); ?></em></span>
                                    <div class="action-holder">
                                        <a class="btn-more-detail" href="<?php echo $link; ?>">More
                                            details</a>
                                    </div>
                                    <div class="action-holder">
										<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
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
				<?php _e( 'No Products' ); // (6) ?>
            </p>
		<?php endif; ?>
    </div>
</section>
<?php require_once get_template_directory() . '/inc/bottom-menu.php'; ?>
<div class='product-overlay'></div>
 <div class="hot-deals-footer" style="margin-top:30px;">
        <?php
        $id = 256167;
        $post = get_post($id);
        $content = apply_filters('the_content', $post->post_content);
        echo $content;
        ?>

    </div>
<?php get_footer( 'shop' ); ?>