<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$page_name    = get_query_var( 'pagename' );
$is_shop_page = get_post_meta( get_the_ID(), 'is_shop_page', true );
if ( $is_shop_page == 'yes' || $page_name == 'wishlist' ) {
	get_header( 'shop' );
} else {
	get_header();
} ?>
<?php $header_image = get_post_meta( get_the_ID(), 'header_image', true );

?>


<?php while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
<?php endwhile; // end of the loop. ?>


<?php if ( $is_shop_page == 'yes' || $page_name == 'wishlist' ) {
	get_footer( 'shop' );
} else {
	get_footer();
}