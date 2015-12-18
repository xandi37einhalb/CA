<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php hybrid_attr( 'body' ); ?>>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71380414-1', 'auto');
  ga('send', 'pageview');

</script>
<div id="page" class="hfeed site">

	<div class="search-area">
		<div class="wide-container">
			<?php get_search_form(); // Loads the searchform.php template. ?>
		</div>
	</div>

	<header id="masthead" class="site-header" <?php hybrid_attr( 'header' ); ?>>

		<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>

		<div class="site-branding">
			<div class="wide-container">
				<?php silvia_site_branding(); ?>
			</div>
		</div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">
		<div class="<?php echo esc_attr( silvia_main_container() ); ?>">
