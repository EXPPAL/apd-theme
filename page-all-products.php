<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
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
            <a href="<?php echo get_permalink( get_page_by_path( 'shop' ) ); ?>"><img
                        src="<?php echo get_template_directory_uri(); ?>/images/shop/logo_horizontal.png"
                        alt="logo"></a>
        </div>
        <div class="filters" style="display:none;">
            <button aria-label="open filter" data-action="open-filter">browse</button>
        </div>
		<?php require_once get_template_directory() . '/inc/top-dropdown.php'; ?>
		<?php /*require_once get_template_directory() . '/inc/search-box.php'; */?>
    </section>
    <section class="shop-grid">
        <div class="top-ribbon">
            <h2>All products</h2>
			<?php
			$orderby  = array( 'sort' => 'rand' );
			$meta_key = '';
			if ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'price' ) {
				$meta_key = '_price';
				$orderby  = array( 'sort' => 'ASC', 'meta_value_num' => 'ASC' );
			}
			if ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'popularity' ) {
				$meta_key = 'total_sales';
				$orderby  = array( 'sort' => 'ASC', 'meta_value_num' => 'DESC' );
			}
			if ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'price-desc' ) {
				$meta_key = '_price';
				$orderby  = array( 'sort' => 'ASC', 'meta_value_num' => 'DESC' );
			}
			$paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$params   = array(
				'paged'      => $paged,
				'post_type'  => 'product',
				'stock'      => 1,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => '_product_type_single',
						'value'   => 'yes',
						'compare' => '=',
					),
				),
				'meta_key'   => $meta_key,
				'orderby'    => $orderby,
			);
			$wc_query = new WP_Query( $params );
			?>
            <div class="pagination-holder"><?php
				$big   = 999999999; // need an unlikely integer
				$links = paginate_links( array(
					'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'  => '?paged=%#%',
					'current' => max( 1, get_query_var( 'paged' ) ),
					'total'   => $wc_query->max_num_pages,
				) );
				echo $links;
				?></div>
            <div class="filter-holder">
                <span class="filter-label">Sort By</span>
                <ul class="sort-by">
                    <li><a href="?orderby=popularity">Sort by popularity</a></li>
                    <li><a href="?orderby=date">Sort by newness</a></li>
                    <li><a href="?orderby=price">Sort by price: low to high</a></li>
                    <li><a href="?orderby=price-desc">Sort by price: high to low</a></li>
                </ul>
            </div>
        </div><!-- end .top-ribbon -->
        <div class="grid-wrapper">
			<?php if ( $wc_query->have_posts() ): ?>
                <ul>
					<?php while ( $wc_query->have_posts() ) : $wc_query->the_post(); ?>
						<?php
						global $product;
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
                        <li class="grid-item">
							<?php
							if ( $product->get_sale_price() ) {
								echo '<span class="sticker-procentage">SALE</span>';
							}
							?>
                            <a href="<?php echo $link; ?>"><img src="<?php echo $full_size_image[0]; ?>" alt=""></a>
                            <div class="item-content">
                                <h1><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h1>
								<?php the_excerpt(); ?>
                                <div class="item-details <?php echo ( ! $product->get_sale_price() ) ? 'one-price' : '' ?>">
                                    <div class="retail-price">
										<?php if ( $product->get_sale_price() ): ?>
                                            <span class="label">retail price</span>
                                            <span class="price"><?php echo ( $product->get_sale_price() ) ? $product->get_regular_price() : ''; ?></span>
										<?php endif; ?>
                                    </div><!-- end .retail-price -->
                                    <div class="rewards-holder" style="<?php if($product->get_id() == 62336 || $product->get_id() == 62321 || $product->get_id() == 166187 || $product->get_id() == 108275 || $product->get_id() == 62314 || $product->get_id() == 106371 || $product->get_id() == 95087 || $product->get_id() == 62312 || $product->get_id() == 62308): ?> display:none; <?php endif; ?>">
                                        <span class="label">Rewards</span>
										<?php if ( is_user_logged_in() ): ?>
                                            <div class="info">
                                                <span class="amount">$<?php echo ( get_user_meta( get_current_user_id(), '_reward_points', true ) ) ? get_user_meta( get_current_user_id(), '_reward_points', true ) : number_format(0,2,'.',''); ?></span>
                                                <span class="label">reward</span>
                                                <span class="icon">?</span>
                                            </div>
                                            <button>use rewards</button>
                                            <div class="popup-reward-input">
                                                <span>You have <mark
                                                            class="rewardbox"><?php echo ( get_user_meta( get_current_user_id(), '_reward_points', true ) ) ? get_user_meta( get_current_user_id(), '_reward_points', true ) : '0.00'; ?></mark> reward</span>
                                                <span>How much of your rewards would you like to use?</span>
	                                            <?php
	                                            $reward_points = get_user_meta( get_current_user_id(), '_reward_points', true);
	                                            $base_price = get_post_meta( get_the_ID(), '_minumum_price', true );
	                                            $current_price = ( $product->get_sale_price() ) ? $product->get_sale_price() : $product->get_regular_price();
	                                            if($reward_points >= $current_price - $base_price){
		                                            $can_use = $current_price - $base_price;
	                                            }elseif($current_price - $base_price > $reward_points){
		                                            $can_use = $reward_points;
	                                            } elseif($reward_points==0){
	                                                $can_use = '0.00';
                                                }
	                                            ?>
                                                <input type="text" name="use_reward" class="use_reward" value="<?php echo $can_use; ?>">
                                                <button class="set-reward">use</button>
                                            </div>
										<?php else: ?>
                                            <div class="info no-login">
                                                <span class="label"><a
                                                            href="<?php echo get_permalink( get_page_by_path( 'my-account' ) ); ?>">Login</a></span>
                                                <span class="sub-label">to see your rewards</span>
                                                <span class="icon">?</span>
                                            </div>
										<?php endif; ?>
                                        <!-- Popup with help text -->
                                        <div class="popup-help-info">
                                            <p>For every dollar you spend on any Deal or in the Shop, past, present or future, we'll give you back 10% of that in store rewards credit that will be saved in your Rewards Wallet.</p>
                                        </div>
                                    </div><!-- end .rewards-holder -->
                                    <div class="action-holder">
                                        <span class="label">current price</span>
                                        <span class="price-value"><?php echo ( $product->get_sale_price() ) ? $product->get_sale_price() : $product->get_regular_price(); ?></span>
                                        <span class="sublabel">base price<mark
                                                    class="base_price"><?php echo get_post_meta( get_the_ID(), '_minumum_price', true ); ?></mark></span>
                                        <form class="cart" method="post" enctype='multipart/form-data'>
                                            <input type="hidden" name="quantity" value="1"/>
                                            <input type="hidden" name="add-to-cart"
                                                   value="<?php echo $product->get_id(); ?>"/>
                                            <input type="hidden" name="use_rewards" class="use_rewards" value="0">
                                            <input type="hidden" name="original_price" class="original_price"
                                                   value="<?php echo ( $product->get_sale_price() ) ? $product->get_sale_price() : $product->get_regular_price(); ?>">
                                            <input type="hidden" name="original_rewards" class="original_rewards"
                                                   value="<?php echo ( is_user_logged_in() ) ? get_user_meta( get_current_user_id(), '_reward_points', true ) : 0; ?>">
                                            <button>add to cart</button>
                                        </form>

                                    </div>
                                </div>
								<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
                                <a href="<?php echo $link; ?>" class="product-info-button"></a>
                            </div>
                        </li>

					<?php endwhile; // end of the loop. ?>


                </ul>
			<?php else: ?>
                <p>
					<?php _e( 'No Products' ); // (6) ?>
                </p>
			<?php endif; ?>
        </div>
        <div class="top-ribbon bottom">
            <div class="pagination-holder"><?php
				echo $links;
				?></div>
        </div><!-- end .top-ribbon -->
    </section>
<?php // require_once get_template_directory() . '/inc/bottom-menu.php'; ?>
 <div class="hot-deals-footer" style="margin-top:30px;">
        <?php
        $id = 256167;
        $post = get_post($id);
        $content = apply_filters('the_content', $post->post_content);
        echo $content;
        ?>

    </div>
<?php get_footer( 'shop' ); ?>