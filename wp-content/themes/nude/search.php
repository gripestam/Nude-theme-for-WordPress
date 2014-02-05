<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Nude
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'nude' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

				<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'content', get_post_format() );

					endwhile;
					paging_nav_num();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_footer();
