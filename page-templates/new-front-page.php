<?php
/**
 * Template Name: NEW Front Page Template
 */

get_header(); ?>

    <div id="primary" class="front-page_box-style site-content">
        <div id="content" role="main">

			<?php

			$bg_image = get_field( 'bg_image' );

			// Left box
			$big_deal_product           = get_field( 'big_deal_product' );
			$big_deal_product_old_price = get_field( 'bd_product_old_price' );
			$big_deal_product_new_price = get_field( 'bd_product_new_price' );
            $big_deal_product_day_end   = get_field( 'bd_product_day_end' );
            $big_deal_product_time_end  = get_field( 'bd_product_time_end' );
			$bd_percent                 = get_field( 'bd_percent' );

			$args    = array(
				'post_type'  => 'page',
				'meta_query' => array(
					array(
						'key'     => 'meta_product_id',
						'value'   => $big_deal_product->ID,
						'compare' => '=',
					)
				)
			);
			$query   = new WP_Query( $args );
			$bd_link = get_permalink( $query->posts[0]->ID );

			$bd_thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
			$bd_post_thumbnail_id = get_post_thumbnail_id( $big_deal_product->ID );
			$bd_full_size_image   = wp_get_attachment_image_src( $bd_post_thumbnail_id, $bd_thumbnail_size );

			$bd_company_name = get_field( 'lto_company_name', $big_deal_product->ID );
			$bd_post         = get_post( $big_deal_product->ID, ARRAY_A );


			// Right box
			$deal_product     = get_field( 'd_product' );
			$default_settings = "empty";
            $deal_product_day_end   = get_field( 'd_product_day_end' );
            $deal_product_time_end   = get_field( 'd_product_time_end' );
			if ( $deal_product != null ) {
				$deal_product_old_price = get_field( 'd_product_old_price' );
				$deal_product_new_price = get_field( 'd_product_new_price' );

				$d_percent              = get_field( 'd_percent' );

				$args_2  = array(
					'post_type'  => 'page',
					'meta_query' => array(
						array(
							'key'     => 'meta_product_id',
							'value'   => $deal_product->ID,
							'compare' => '=',
						)
					)
				);
				$query_2 = new WP_Query( $args_2 );
				$d_link  = get_permalink( $query_2->posts[0]->ID );

				$d_thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
				$d_post_thumbnail_id = get_post_thumbnail_id( $deal_product->ID );
				$d_full_size_image   = wp_get_attachment_image_src( $d_post_thumbnail_id, $d_thumbnail_size );

				$d_company_name = get_field( 'lto_company_name', $deal_product->ID );

				$d_post = get_post( $deal_product->ID, ARRAY_A );

				$default_settings = "not-empty";
			}

			?>

            <main style="background-image:url(<?php echo $bg_image; ?>);">


                <link href="<?php echo get_template_directory_uri(); ?>/css/box-style-front-page.css;" rel="stylesheet">

                <div class="deal-box-holder">

                    <!-- Left box -->
                    <a href="<?php echo $bd_link ?>">
                        <div class="box big-deal-box">
                            <div class="overlay">
                                <span class="siren-icon"></span>
                                <h1 class="box-title">The <strong>big</strong> deal</h1>
                                <div class="img-holder">
                                <span class="sticker">
                                    <span><?php echo $bd_percent ?>%</span>
                                    <span>OFF</span>
                                </span>
                                    <img src="<?php echo $bd_full_size_image[0] ?>" alt="" class="product-img">
                                </div>
                                <div class="prices">
                                    <span class="old-price"><?php echo $big_deal_product_old_price; ?></span>
                                    <span class="deal-price"><?php echo $big_deal_product_new_price; ?></span>
                                </div>
                                <div class="product-details">
                                    <span class="company-name"><?php echo $bd_company_name ?></span>
                                    <span class="product-name"><?php echo $bd_post['post_title']; ?></span>
                                </div>
                                <div class="countdown-holder">

									<?php echo do_shortcode( '[jcountdown timetext="' . $big_deal_product_day_end . ' '. $big_deal_product_time_end . '" 
                                    timezone="-6" 
                                    style="flip" 
                                    color="black" 
                                    width="0"
                                    textgroupspace="7" 
                                    textspace="0" 
                                    reflection="false" 
                                    reflectionopacity="11" 
                                    reflectionblur="0" 
                                    daytextnumber="2" 
                                    displayday="true" 
                                    displayhour="true" 
                                    displayminute="true" 
                                    displaysecond="true" 
                                    displaylabel="true" 
                                    onfinishredirecturl=""]' . $big_deal_product_day_end . ' ' . $big_deal_product_time_end . '[/jcountdown]' ); ?>
                                </div>
                                <div class="c-thermometr">
                                    <input type="hidden" id="big-deal-day-end"
                                           value="<?php echo $big_deal_product_day_end ?>">
                                    <span id="big-deal-counter" class="counter"></span>
                                    <span id="big-deal-pointer" class="pointer"></span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Right box -->
                    <a href="<?php echo $d_link ?>">
                        <div class="box deal-box <?php echo $default_settings ?>" data-sraca="sraca">
                            <div class="overlay">
                                <span class="siren-icon"></span>
                                <h1 class="box-title">The <strong>deal</strong></h1>
                                <div class="img-holder">
                                <span class="sticker">
                                <span><?php echo $d_percent ?>%</span>
                                    <span>OFF</span>
                                </span>
                                    <img src="<?php echo $d_full_size_image[0] ?>" alt="" class="product-img">
                                </div>
                                <div class="prices">
                                    <span class="old-price"><?php echo $deal_product_old_price ?></span>
                                    <span class="deal-price"><?php echo $deal_product_new_price ?></span>
                                </div>
                                <div class="product-details">
                                    <span class="company-name"><?php echo $d_company_name ?></span>
                                    <span class="product-name"><?php echo $d_post['post_title'] ?></span>
                                </div>
                                <div class="countdown-holder">

									<?php echo do_shortcode( '[jcountdown timetext="' . $deal_product_day_end . ' ' . $deal_product_time_end . '" 
                                    timezone="-6" 
                                    style="flip" 
                                    color="black" 
                                    width="0" 
                                    textgroupspace="7" 
                                    textspace="0" 
                                    reflection="false" 
                                    reflectionopacity="11" 
                                    reflectionblur="0" 
                                    daytextnumber="2" 
                                    displayday="true" 
                                    displayhour="true" 
                                    displayminute="true" 
                                    displaysecond="true" 
                                    displaylabel="true" 
                                    onfinishredirecturl=""]' . $deal_product_day_end . ' ' . $deal_product_time_end . '[/jcountdown]' ); ?>
                                </div>
                                <div class="c-thermometr">
                                    <input type="hidden" id="deal-day-end" value="<?php echo $deal_product_day_end ?>">
                                    <span id="deal-counter" class="counter"></span>
                                    <span id="deal-pointer" class="pointer"></span>
                                </div>
                            </div>
                        </div>
                        <script>
                            if (jQuery('.box.deal-box.empty').length) {
                                jQuery('.box.deal-box.empty')
                                    .parent()
                                    .click(function (e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                    })
                                    .css({
                                        'cursor': 'initial'
                                    });
                            }
                        </script>
                    </a>

                </div>

                <div class="shop-widget">

                    <a href="<?php echo get_permalink( get_page_by_path( 'shop' ) ) ?>">
                        <div class="overlay">
                            <div class="left-side">
                                <span>The shop</span>
                            </div>
                            <div class="right-side">
                                <div class="text-holder">
                                    <span>BUY MORE, EARN MORE, SAVE MORE!</span>
                                    <span>60+ AWARD - WINNING PRODUCTS</span>
                                </div>
                                <span class="link">shop now</span>
                            </div>
                        </div>
                    </a>

                </div>

                <script type="text/javascript"
                        src="<?php echo get_template_directory_uri(); ?>/js/box-style-front-page.js;"></script>

            </main>

        </div><!-- #content -->
    </div><!-- #primary -->

<?php get_footer(); ?>