<?php get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main" <?php hybrid_attr( 'content' ); ?>>

			<?php if ( have_posts() ) : ?>
			<?php $type = get_post_type(); ?>
					<header class="page-header <?php if ($type == 'news') print "newslist"; ?>">
					<?php
						if ($type == 'news') {
							print '<h1 class="page-title">News</h1>';
						}
						else {
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="taxonomy-description">', '</div>' );
						}
					?>
				</header><!-- .page-header -->

<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();
					if ($type == 'news') {

						get_template_part( 'content-news', 'list' );
					}
					else {
						get_template_part( 'content', get_post_format() );
					}
					endwhile;
					get_template_part( 'loop', 'nav' ); // Loads the loop-nav.php template
?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>