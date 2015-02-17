<?php
if ( ! function_exists( 'nude_setup' ) ) :
function nude_setup() {

	load_theme_textdomain( 'nude', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( 'editor-style.css' );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Two wp_nav_menu().
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'nude' ),
		'secondary' => __( 'Secondary menu', 'nude' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
	) );
	
	// Change the ACF:Options Page titles
	if( function_exists('acf_set_options_page_title') )
	{
	    acf_set_options_page_title( __('Konfigurera', 'nude') );
	}
	if( function_exists('acf_set_options_page_menu') )
	{
	    acf_set_options_page_menu( __('Konfigurera', 'nude') );
	}

}
endif; // nude_setup

add_action( 'after_setup_theme', 'nude_setup' );


function nude_scripts() {
	// An example
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}	

}
add_action( 'wp_enqueue_scripts', 'nude_scripts' );


function nude_body_classes( $classes ) {

	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = 'list-view';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	return $classes;
}
add_filter( 'body_class', 'nude_body_classes' );


function nude_post_classes( $classes ) {
	if ( ! post_password_required() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'nude_post_classes' );


function nude_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'nude' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'nude_wp_title', 10, 2 );



// Remove junk from head
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);


// Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {	
	return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'add_opengraph_doctype'); 


// Adds a favicon
function nude_favicon() {
	 echo '<link rel="Shortcut Icon" type="image/x-icon" href="'. get_option('siteurl') .'/assets/images/favicon.png" />';
}
add_action('wp_head', 'nude_favicon');


// Wrap oEmbeds with an embed-conatiner to make it responsive
function responsive_video_wrapping($html, $url, $attr) {
	$html = '<div class="embed-container">' . $html . '</div>';
	return $html;
}
add_filter( 'embed_oembed_html', 'responsive_video_wrapping', 10, 3);


/*
 *	##### EXTRA SETTINGS #####
 *
 */

// Wrap oEmbeds with an embed-conatiner to make it responsive
function responsive_video_wrapping($html, $url, $attr) {
	$html = '<div class="embed-container">' . $html . '</div>';
	return $html;
}
add_filter( 'embed_oembed_html', 'responsive_video_wrapping', 10, 3);



if ( ! function_exists( 'paging_nav_num' ) ) :
	/**
	 * Displays navigation to next/previous set of posts with numbers.
	 *
	 */
	function paging_nav_num () {
	    global $wp_query;
	    $big = 999999999; // need an unlikely integer
	    $pages = paginate_links( array(
	        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	        'format' => '?paged=%#%',
	        'current' => max( 1, get_query_var('paged') ),
	        'total' => $wp_query->max_num_pages,
	        'prev_next' => false,
	        'type'  => 'array'
	    ) );
	    if( is_array( $pages ) ) {
	        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
	        echo '<div class="pagination"><ul class="page-numbers">';
	        //echo '<li><span>'. $paged . ' av ' . $wp_query->max_num_pages .'</span></li>';
	        foreach ( $pages as $page ) {
	                echo "<li>$page</li>";
	        }
	       echo '</ul></div>';
	    }
	}
endif;


function dequeue_jquery_migrate( &$scripts){

	if(!is_admin()){
		$scripts->remove( 'jquery');
		$scripts->remove( 'jquery-core');
	}

}
add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate', 10 );



// Add Open Graph Meta Info
function insert_og_in_head() {
	global $post;

		echo '<meta property="og:locale" content="sv_SE" />';
		
		if ( is_front_page() || is_search() || is_404() ) {
			echo '<meta property="og:title" content="' . get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' ) . '"/>';
			echo '<meta property="og:description" content="' . get_bloginfo( 'description' ) . '"/>';
			echo '<meta name="description" content="' . get_bloginfo( 'description' ) . '"/>';
			echo '<meta property="og:type" content="website"/>';
		}
		elseif ( is_singular('post') ) {
			echo '<meta property="og:title" content="' . get_the_title() . ' | ' . get_bloginfo( 'name' ) . '"/>';
			echo '<meta property="og:description" content="' . substr(strip_tags($post->post_content), 0, 500) . '"/>';
			echo '<meta name="description" content="' . substr(strip_tags($post->post_content), 0, 500) . '"/>';
			echo '<meta property="og:type" content="article"/>';
		}
		else {
			echo '<meta property="og:title" content="' . get_the_title() . ' | ' . get_bloginfo( 'name' ) . '"/>';
			if (empty($post->post_excerpt)) {
				echo '<meta property="og:description" content="' . substr(strip_tags($post->post_content), 0, 500) . '"/>';
				echo '<meta name="description" content="' . substr(strip_tags($post->post_content), 0, 500) . '"/>';
			} else {
				echo '<meta property="og:description" content="' . $post->post_excerpt . '"/>';
				echo '<meta name="description" content="' . $post->post_excerpt . '"/>';
			}		
			echo '<meta property="og:type" content="website"/>';
		}
        
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        
        echo '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '"/>';
	if(!isset($post) || !has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
		$default_image = get_option('siteurl') . '/assets/images/logo.png'; //replace this with a default image on your server or an image in your media library
		echo '<meta property="og:image" content="' . $default_image . '"/>';
	}
	else{
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
	}
	echo "\n";
}
add_action('wp_head', 'insert_og_in_head', 5);



/**
	### Pick-n-choose your iOS meta

*/ 
if ( ! function_exists( 'nude_iosmeta' ) ) :
function nude_iosmeta() {
	/*
		Lines in order of appearance
		1. iOS standalone web app
		2. iOS status bar appearance, options: black, black-translucent
		3. iOS icon, just one size because ios will scale it, remove "-precomposed" if you want ios to add effects
		<!-- iOS startup images -->
		4. iPhone
		5. iPhone (Retina)
		6. iPhone 5
		7. iPad Portrait
		8. iPad Landscape
		9. iPad (Retina) Portrait
		10. iPad (Retina) Landscape
	*/
	?>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link href="<?php get_option('url'); ?>/assets/images/ios/icon@144x144.png" sizes="144x144" rel="apple-touch-icon-precomposed">
	<link href="<?php get_option('url'); ?>/assets/images/ios/start@320x460.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">
	<link href="<?php get_option('url'); ?>/assets/images/ios/start@640x920.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
	<link href="<?php get_option('url'); ?>/assets/images/ios/start@640x1096.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
	<link href="<?php get_option('url'); ?>/assets/images/ios/start@768x1004.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">
	<link href="<?php get_option('url'); ?>/assets/images/ios/start@748x1024.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">
	<link href="<?php get_option('url'); ?>/assets/images/ios/start@1536x2008.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
	<link href="<?php get_option('url'); ?>/assets/images/ios/start@1496x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
	<?php
}
endif;
add_action('wp_head', 'nude_iosmeta');








/**
 * http://www.jasonbobich.com/wordpress/hiding-the-wordpress-admin-panel-to-your-subscribers/
 * Disable admin bar on the frontend of your website
 * for subscribers.
 *//*
function nude_disable_admin_bar() { 
	if( ! current_user_can('edit_posts') )
		add_filter('show_admin_bar', '__return_false');	
}
add_action( 'after_setup_theme', 'nude_disable_admin_bar' );
 
/**
 * Redirect back to homepage and not allow access to 
 * WP admin for Subscribers.
 *//*
function nude_redirect_admin(){
	if ( ! current_user_can( 'edit_posts' ) ){
		wp_redirect( site_url() );
		exit;		
	}
}
add_action( 'admin_init', 'nude_redirect_admin' );
*/









//if ( ! function_exists( 'twentyfourteen_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return void
 */
/*function twentyfourteen_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $GLOBALS['wp_query']->max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&larr; Previous', 'twentyfourteen' ),
		'next_text' => __( 'Next &rarr;', 'twentyfourteen' ),
	) );

	if ( $links ) :

	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'twentyfourteen' ); ?></h1>
		<div class="pagination loop-pagination">
			<?php echo $links; ?>
		</div><!-- .pagination -->
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;*/