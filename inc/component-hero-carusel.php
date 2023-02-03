<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<section class="c-hero-carusel">
    <ul>
        <?php 
            $args = array( 'post_type' => 'apd_hero_banner', 'posts_per_page' => 10 );
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post();
            $image = get_field('image');
            $linlk_to_page = get_field('link_to_product_page'); ?>
			<li>
                <a href="<?php echo $linlk_to_page ?>">
                    <img src="<?php echo $image ?>">
                </a>
                </li>

            <?php endwhile;
        ?>
    </ul>
    <div class="control-holder"></div> 
</section>