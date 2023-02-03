<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<section class="filter-menu-dropdown">
    <div class="filter-wrapper">
    <button class="btn-close">Close</button>
        <ul class="filter-shop">
            <span class="header">THE SHOP</span>
			<?php $term = get_term_by( 'name', 'Blue Light Specials', 'product_cat' ); ?>
            <li><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a></li>
            <li><a href="<?php echo get_permalink( get_page_by_path( "new-arrivals" ) ) ?>">New Arrivals</a></li>
            <li><a href="<?php echo get_permalink( get_page_by_path( "trending" ) ) ?>">Trending Now</a></li>
			<?php $term = get_term_by( 'name', 'Gift Certificate', 'product_cat' ); ?>
            <li><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('all-products')); ?>">All Products</a></li>
        </ul>
        <ul class="filter-sortby">
            <span class="header">Sort by</span>
            <li>
                <span class="has_submenu">Instrument Type</span>
                <ul class="sub-list">
                    <li class="header">Instrument Categories</li>
				    <?php
				    $terms = get_terms( array( 'taxonomy' => 'instrument_type', 'hide_empty' => true, ) );
				    foreach ( $terms as $term ):?>
                        <li>
                            <a href="<?php echo get_term_link( $term ) ?>"><?php echo $term->name; ?></a>
                        </li>
				    <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <span class="has_submenu">Format</span>
                <ul class="sub-list">
                    <li class="header">Format Categories</li>
				    <?php
				    $terms = get_terms( array( 'taxonomy' => 'format_type', 'hide_empty' => true, ) );
				    foreach ( $terms as $term ):?>
                        <li>
                            <a href="<?php echo get_term_link( $term ) ?>"><?php echo $term->name; ?></a>
                        </li>
				    <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <span class="has_submenu">Developers</span>
                <ul class="sub-list">
                    <li class="header">Developers Categories</li>
				    <?php
				    $terms = get_terms( array( 'taxonomy' => 'developer', 'hide_empty' => true, ) );
				    foreach ( $terms as $term ):?>
                        <li>
                            <a href="<?php echo get_term_link( $term ) ?>"><?php echo $term->name; ?></a>
                        </li>
				    <?php endforeach; ?>
                </ul>
            </li>
		    <?php $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, ) ); ?>
		    <?php foreach ( $terms as $term ): ?>
			    <?php if ( $term->name == 'Blue Light Specials' || $term->name == 'Gift Certificate' ) {
				    continue;
			    } ?>
                <li>
                    <span><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a></span>
                </li>
		    <?php endforeach; ?>
        </ul>
    </div><!-- end .filter-wrapper -->
</section><!-- end .filter-menu-dropdown -->
