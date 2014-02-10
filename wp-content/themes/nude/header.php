<?php
/**
 * The Header
 *
 * @package WordPress
 * @subpackage Nude
 */
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php wp_title( '|', true, 'right' ); ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php //Mobile Internet Explorer allows us to activate ClearType technology for smoothing fonts for easy reading ?>
		<meta http-equiv="cleartype" content="on">
		
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="<?php bloginfo('url'); ?>/assets/css/normalize.css">
        <link rel="stylesheet" href="<?php bloginfo('url'); ?>/assets/css/main.css">
        <script src="<?php bloginfo('url'); ?>/assets/js/vendor/modernizr-2.6.2.min.js"></script>
        <!--[if lt IE 9]>
			<script src="<?php bloginfo('url'); ?>/assets/js/vendor/mediaqueries.js"></script>
			<script src="<?php bloginfo('url'); ?>/assets/js/vendor/selectivizr-min.js"></script>
		<![endif]-->
        <?php wp_head(); ?>
    </head>

<body <?php body_class(); ?>>
<!--[if lt IE 9]>
	<p class="browsehappy">Det verkar som att du använder en <strong>gammal och utdaterad</strong> webbläsare. <a href="http://browsehappy.com/">Uppdatera eller byt</a> webbläsare för att få en bättre surfupplevelse.</p>
<![endif]-->

<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<div class="header-main">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

			<div class="search-toggle">
				<a href="#search-container" class="screen-reader-text"><?php _e( 'Search', 'nude' ); ?></a>
			</div>

			<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
				<a class="screen-reader-text skip-link" href="#content"><?php _e( 'Skip to content', 'nude' ); ?></a>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
			</nav>
		</div>

		<div id="search-container" class="search-box-wrapper hide">
			<div class="search-box">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header><!-- #masthead -->

	<div id="main" class="site-main">