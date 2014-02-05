<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Nude
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'content' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_footer();