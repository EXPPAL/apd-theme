<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11"/>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
    <!--Bootstrap Core JavaScript-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
    <!--Placeholder-->
    <script src="<?php echo get_template_directory_uri(); ?>/js/placeholders.min.js"></script>
    <!--Menu-->
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' type='text/css'
          href='https://static.ctctcdn.com/h/contacts-embedded-signup-assets/1.0.2/css/signup-form.css'>
    <!-- Custom CSS -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/modern-business.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri(); ?>/css/style.css?ver=2.1.13" rel="stylesheet">
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
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="mainwrapper">
    <div class="header" id="header">
        <div class="top_header sticky-navigation-top">
            <div class="container">
                <div class="accounts">
					<?php wp_nav_menu( array(
						'menu'       => 'Account Menu',
						'container'  => false,
						'items_wrap' => '<ul>%3$s</ul>'
					) ); ?>
                </div><!--accounts-->

                <div class="social">
					<?php wp_nav_menu( array(
						'menu'       => 'Social Links',
						'container'  => false,
						'items_wrap' => '<ul>%3$s</ul>'
					) ); ?>
                </div><!--social-->
            </div><!--container-->
        </div><!--top_header-->

        <div class="mainheader">
            <div class="container clearfix">
                <div class="col-sm-12">
                    <div class="logo" style="text-align: center;">
                        <?php if(is_front_page()):?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img
                                    src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="" class="logoimg"></a>
                        <?php else:?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img
                                        src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="" class=""></a>
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
            <div class="mainpage-search-holder">
                <?php
                if ( is_page() && get_queried_object()->post_name == 'search' ) {
                    if ( isset( $_GET['search'] ) ) {
                        $search = $_GET['search'];
                    } else {
                        $search = '';
                    }
                }
                ?>
                <form role="search" method="GET" action="<?php echo get_permalink( get_page_by_path( 'search' ) ) ?>">
                <input type="text" value="<?php echo($search); ?>" name="search" class="mp-search-input" placeholder="Search for products...">
                <button type="submit" class="mp-search-btn"><i class="yith-wcwl-icon fa fa-search"></i></button>
                </form>
            </div>
		</div>

        <?php //require_once get_template_directory() . '/inc/deal-data.php'; ?>
    </div><br style="clear:both"><!--header-->