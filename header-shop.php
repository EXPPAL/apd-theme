

    <!DOCTYPE html>
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <meta name="viewport" content="width=device-width">

    <link rel="apple-touch-icon" href="" sizes="120x120">
    <!--Bootstrap Core JavaScript-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
    <!--Placeholder-->
    <script src="<?php echo get_template_directory_uri(); ?>/js/placeholders.min.js"></script>

    <!-- Stylesheets -->
    <link href='//fonts.googleapis.com/css?family=Work+Sans' rel='stylesheet' type='text/css'>
    <!-- Custom CSS -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/modern-business.css" rel="stylesheet">
    <?php $is_shop_page = get_post_meta( get_the_ID(), 'is_shop_page', true );
    if ( $is_shop_page == 'yes' ) {
        ?>
        <link rel="stylesheet"
              href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css">
        <?php
    } ?>
    <link href="<?php echo get_template_directory_uri(); ?>/css/style.css?ver=2.1.15" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri(); ?>/css/responsive.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri(); ?>/css/header_dropdown.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?php echo get_template_directory_uri(); ?>/font-awesome-4.1.0/css/font-awesome.min.css"
          rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->
    <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/shop-main.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/karam.css">
	
    <style type="text/css">
        .main-menu-nav__wrapper .container {
    max-width: 1200px;
}
    </style>
    <?php wp_head(); ?>
</head>

<header>
    <div class="header-top">
        <div class="wrapper">
            
            <nav>
                <?php wp_nav_menu( array(
                    'menu'       => 'Account Menu',
                    'container'  => false,
                    'items_wrap' => '<ul>%3$s</ul>'
                ) ); ?>
            </nav>
            <div class="social-holder">
                <?php wp_nav_menu( array(
                    'menu'       => 'Social Links',
                    'container'  => false,
                    'items_wrap' => '<ul>%3$s</ul>'
                ) ); ?>
            </div>
        </div>
    </div>
    <!-- end .header-top -->


 <div class="mainheader">
            <div class="container clearfix">
                <div class="col-sm-12">
                    <div class="logo" style="text-align: center;">
                        <?php if(is_front_page()):?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img
                                    src="<?php echo get_template_directory_uri(); ?>/images/shop/logo_shop.png" alt="" class=""></a>
                        <?php else:?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img
                                       src="<?php echo get_template_directory_uri(); ?>/images/shop/logo_shop.png" alt="" class=""></a>
                        <?php endif;?>
                    </div><!--logo-->
                </div><!--col-sm-5-->
            </div>

            
        </div><!--mainheader-->

        <div class="main-menu-nav__wrapper">
            <div class="container clearfix">
                <div class="col-sm-12">
                    <div class="menu">
                        <a class="toggleMenu" href="#">Menu</a>



                        <?php if( function_exists( 'ubermenu' )): ?>

                          <?php ubermenu( 'main' , array(
                            'menu' => 'Main',
                            'container'  => false,
                          ) ); ?>

                        <?php else: ?>

                        <?php wp_nav_menu( array(
                            'menu'       => 'Main',
                            'container'  => false,
                            'items_wrap' => '<ul class="nav1">%3$s</ul>'
                        ) ); ?>

                        <?php endif; ?>


                    </div><!--menu-->
                </div><!--col-sm-12-->
            </div>
            <?php if ( ! isset($_GET['search']) ) : /* allow search box only on non-search pages */ ?>
              <div class="mainpage-search-holder">
                  <form role="search" method="GET" action="<?php echo get_permalink( get_page_by_path( 'search' ) ) ?>">
                  <input type="text" value="" name="search" class="mp-search-input" placeholder="Search for products...">
                  <button type="submit" class="mp-search-btn"><i class="yith-wcwl-icon fa fa-search"></i></button>
                  </form>
              </div>
            <?php endif; ?>
        </div>



    <?php //require_once get_template_directory() . '/inc/deal-data.php'; ?>
</header>
<body <?php body_class(); ?> >
<?php $is_shop_page = get_post_meta( get_the_ID(), 'is_shop_page', true ); ?>
<?php $pagename = get_query_var( 'pagename' ); ?>
<main id="The Shop" <?php echo ( $pagename == 'wallet' ) ? 'class="Rewards"' : ''; ?> <?php echo ( $is_shop_page == 'yes' ) ? 'class="custom-product-page"' : ''; ?>>