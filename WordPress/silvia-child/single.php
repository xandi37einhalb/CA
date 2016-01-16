<?php get_header(); ?>
<?php
	$categoryIdWorkshops = 41;
	$content_class = 'content-area';
	$type = get_post_type();
	if ($type == 'news') $content_class = 'content-area-with-sidebar';
	if (has_category( $categoryIdWorkshops, $post )) $content_class = 'content-area-with-sidebar';
?>
	<?php if ( has_excerpt() ) : ?>
		<header class="page-header">
			<?php the_excerpt(); ?>
		</header><!-- .page-header -->
	<?php endif; ?>

	<div id="primary" class="<?php print $content_class; ?>">
		<main id="main" class="site-main" role="main" <?php hybrid_attr( 'content' ); ?>>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'single' ); ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php
	if ($type == 'news') {
		get_sidebar('news');
	}
	if (has_category( $categoryIdWorkshops, $post )) {
		get_sidebar('workshops');
	}
?>
<?php get_footer(); ?>