<?php
/**
 * The template for displaying Archive pages
 *
 * @package WordPress
 * @subpackage Nude
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'nude' ), get_the_date() );

						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'nude' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'nude' ) ) );

						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'nude' ), get_the_date( _x( 'Y', 'yearly archives date format', 'nude' ) ) );

						else :
							_e( 'Archives', 'nude' );

						endif;
					?>
				</h1>
			</header><!-- .page-header -->

			<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'content', get_post_format() );

					endwhile;
					// Previous/next page navigation.
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