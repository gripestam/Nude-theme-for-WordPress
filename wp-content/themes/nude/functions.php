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
	
	// ACF Options page
	/*if( function_exists('acf_add_options_page') ) {
	
		acf_add_options_page(array(
			'page_title' 	=> 'Theme General Settings',
			'menu_title'	=> 'Theme Settings',
			'menu_slug' 	=> 'theme-general-settings',
			'capability'	=> 'edit_posts',
			'redirect'		=> false
		));
		
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Theme Header Settings',
			'menu_title'	=> 'Header',
			'parent_slug'	=> 'theme-general-settings',
		));
		
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Theme Footer Settings',
			'menu_title'	=> 'Footer',
			'parent_slug'	=> 'theme-general-settings',
		));
	
	}*/
	
	// Add image size
	//add_image_size( 'medium-square', 600, 600, true ); // Adds a 600x600 image hard-crop from center
	
	// De-register inline gallery styles
	//add_filter( 'use_default_gallery_style', '__return_false' );
	
}
endif; // nude_setup

add_action( 'after_setup_theme', 'nude_setup' );




// Make it possible to choose the new image sizes from WordPress Admin
add_filter( 'image_size_names_choose', 'site_image_sizes' );
function site_image_sizes( $sizes ) {
   	return array_merge( $sizes, array(
        'medium-square' => __( 'Medium square', 'nude' ),
	) );
}






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




/*
	Favicons to the max!
*/ 
if ( ! function_exists( 'site_header_meta' ) ) :
	
	function site_header_meta() { ?>
		<!-- get easily from realfavicongenerator.net -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="apple-touch-icon" sizes="57x57" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php get_option('url'); ?>/assets/images/favicon/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="<?php get_option('url'); ?>/assets/images/favicon/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php get_option('url'); ?>/assets/images/favicon/favicon-194x194.png" sizes="194x194">
		<link rel="icon" type="image/png" href="<?php get_option('url'); ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="<?php get_option('url'); ?>/assets/images/favicon/android-chrome-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="<?php get_option('url'); ?>/assets/images/favicon/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="<?php get_option('url'); ?>/assets/images/favicon/manifest.json">
		<link rel="mask-icon" href="<?php get_option('url'); ?>/assets/images/favicon/safari-pinned-tab.svg" color="#004a83">
		<link rel="shortcut icon" href="<?php get_option('url'); ?>/assets/images/favicon/favicon.ico">
		<meta name="msapplication-TileColor" content="#004a83">
		<meta name="msapplication-TileImage" content="<?php get_option('url'); ?>/assets/images/favicon/mstile-144x144.png">
		<meta name="msapplication-config" content="<?php get_option('url'); ?>/assets/images/favicon/browserconfig.xml">
		<meta name="theme-color" content="#004a83">
	
		<?php /*<link href="<?php get_option('url'); ?>/assets/images/ios/icon@144x144.png" sizes="144x144" rel="apple-touch-icon-precomposed">
		<link href="<?php get_option('url'); ?>/assets/images/ios/start@320x460.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">
		<link href="<?php get_option('url'); ?>/assets/images/ios/start@640x920.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="<?php get_option('url'); ?>/assets/images/ios/start@640x1096.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="<?php get_option('url'); ?>/assets/images/ios/start@768x1004.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">
		<link href="<?php get_option('url'); ?>/assets/images/ios/start@748x1024.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">
		<link href="<?php get_option('url'); ?>/assets/images/ios/start@1536x2008.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="<?php get_option('url'); ?>/assets/images/ios/start@1496x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image"> */?>
		<?php
	}
	endif;
add_action('wp_head', 'site_header_meta');









// Description: Replace and/or remove accents and other special characters in filenames on upload
add_filter( 'sanitize_file_name', 'extended_sanitize_file_name', 10, 2 );
function extended_sanitize_file_name( $filename ) {
	$sanitized_filename = remove_accents( $filename );
	return $sanitized_filename;
}




if( ! function_exists('fix_no_editor_on_posts_page'))
{
	/**
	 * Add the wp-editor back into WordPress after it was removed in 4.2.2.
	 *
	 * @param $post
	 * @return void
	 */
	function fix_no_editor_on_posts_page($post)
	{
		if($post->ID != get_option('page_for_posts'))
			return;

		remove_action('edit_form_after_title', '_wp_posts_page_notice');
		add_post_type_support('page', 'editor');
	}
	add_action('edit_form_after_title', 'fix_no_editor_on_posts_page', 0);
}











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