<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

    <div class="ab_inner_content wpb_column vc_column_container vc_col-sm-12 div404">
        <div class="vc_column-inner ">
            <div class="wpb_wrapper">
                <div class="vc_row wpb_row vc_inner vc_row-fluid container">
                    <h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'twentytwelve' ); ?></h1>

                    <div class="entry-content">
                        <p><?php _e( 'It seems we can’t find what you’re looking for so at least take this banana.', 'twentytwelve' ); ?></p>
                        <img src="https://audioplugin.deals/wp-content/uploads/2016/12/wherediyoucomefrom.jpg"/>
                    </div><!-- .entry-content -->

                </div>


            </div><!-- #post-0 -->

        </div><!-- #content -->
    </div><!-- #primary -->
    <div class="clear"></div>
<?php get_footer(); ?>