<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Nude
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>
		<?php
			if ( is_single() ) : 
		?>
		<div class="entry-meta">
			<?php the_category(); ?>
			<?php the_time('Y-m-d H:i:s') ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'nude' ) );
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>
	
	<?php if ( is_single() ) :
		the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' );
	endif; ?>
</article><!-- #post-## -->
