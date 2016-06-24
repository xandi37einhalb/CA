<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
	$sticky_class = '';
	if ( get_theme_mod( 'header_sticky' ) ) {
		$sticky_class = 'sticky';
	}
?>
<div id="page">
	<header class="header <?php echo esc_attr( $sticky_class ); ?>">

		<div class="container">

			<div class="row">
				<div class="col-xs-12">

					<?php if ( get_theme_mod( 'header_tagline', 1 ) ): ?>
					<div class="rowTagline">	<p class="site-tagline"><?php bloginfo( 'description' ); ?></p></div>
					<?php endif; ?>

					<div class="head-wrap">


						<div class="logo-wrap">
							<h1 class="site-logo">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
									<?php if ( get_theme_mod( 'logo' ) ): ?>
										<img
										     src="<?php echo esc_url( get_theme_mod( 'logo' ) ); ?>"
										     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/>
									<?php else: ?>
										<?php bloginfo( 'name' ); ?>
									<?php endif; ?>
								</a>
							</h1>


						</div>

						<nav class="nav">
							<?php wp_nav_menu( array(
								'theme_location' => 'main_menu',
								'container'      => '',
								'menu_id'        => '',
								'menu_class'     => 'navigation'
							) ); ?>


								<a href="#mobilemenu" class="mobile-trigger"><div id="mobile-trigger-before" class="mobile-trigger-before"></div><i class="fa fa-angle-down"></i></a>
						</nav>

						<!-- #nav -->





						<div id="mobilemenu"></div>
					</div>

				</div>
			</div>
		</div>
	</header>

	<?php
		$main_class = '';
		if ( is_page_template( 'template-frontpage.php' ) ) {
			$main_class = 'main-home';
		}
	?>

	<main class="main <?php echo esc_attr( $main_class ); ?>">
