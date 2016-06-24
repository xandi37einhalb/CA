<?php
/**
 * Theme functions file
 *
 * Contains all of the Theme's setup functions, custom functions,
 * custom hooks and Theme settings.
 *
 * @since     1.0
 * @author    Your Name
 * @copyright Copyright (c) 2015, Your Name
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
add_action( 'customize_register', 'roxima_ca_customize_register', 101 );

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

function roxima_ca_customize_register( $wpc ) {
	$wpc->add_setting( 'footer_text', array(
		'default'           => sprintf( '<a href="%s">%s</a> &ndash; %s',
			esc_attr( home_url( '/' ) ),
			get_bloginfo( 'name' ),
			get_bloginfo( 'description' )
		),
		'sanitize_callback' => 'roxima_ca_sanitize_footer_text',
	) );
	$wpc->add_control( 'footer_text', array(
		'type'        => 'text',
		'section'     => 'footer',
		'label'       => esc_html__( 'Footer text', 'roxima' ),
		'description' => esc_html__( 'Allowed tags: a (href|class|target), img (src|class), span (class), i (class), b, em, strong.', 'roxima' ),
	) );
}

function roxima_ca_sanitize_footer_text( $text ) {
	$allowed_html = array(
		'a'      => array(
			'href'  => array(),
			'class' => array(),
			'target' => array(),
		),
		'img'    => array(
			'src'   => array(),
			'class' => array(),
		),
		'span'   => array(
			'class' => array(),
		),
		'i'      => array(
			'class' => array(),
		),
		'b'      => array(),
		'em'     => array(),
		'strong' => array(),
	);

	return wp_kses( $text, $allowed_html );
}

add_action( 'widgets_init', 'roxima_ca_load_widgets', 11 );
function roxima_ca_load_widgets() {
	require get_stylesheet_directory() . '/inc/widgets/contact.php';
	require get_stylesheet_directory() . '/inc/widgets/slider.php';
	require get_stylesheet_directory() . '/inc/widgets/team.php';
    require get_stylesheet_directory() . '/inc/widgets/latest-posts.php';
    require get_stylesheet_directory() . '/inc/widgets/pricing-table.php';
}

add_action( 'wp_enqueue_scripts', 'roxima_ca_enqueue_scripts' );
function roxima_ca_enqueue_scripts() {

	/*
	 * Styles
	 */
	$theme = wp_get_theme();

	$font_url = '';
	/* translators: If there are characters in your language that are not supported by Montserrat and Lato, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat and Lato fonts: on or off', 'roxima-child' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Hind:400,700,600,500,300|Merriweather:400,700|Raleway:400,300|Vesper+Libre:400,500' ), '//fonts.googleapis.com/css' );
	}
	wp_register_style( 'roxima-google-font', esc_url( $font_url ) );

	wp_register_style( 'roxima-base', get_template_directory_uri() . '/css/base.css', array(), $theme->get( 'Version' ) );
	wp_register_style( 'flexslider', get_template_directory_uri() . '/css/flexslider.css', array(), '2.5.0' );
	wp_register_style( 'mmenu', get_template_directory_uri() . '/css/mmenu.css', array(), '5.2.0' );
	wp_register_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.5.0' );
	wp_register_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific.css', array(), '1.0.0' );
	wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.min.css', array(), '3.4.0' );

	wp_register_style( 'roxima-style', get_template_directory_uri() . '/style.css', array(
		'roxima-google-font',
		'roxima-base',
		'flexslider',
		'mmenu',
		'font-awesome',
		'magnific-popup',
		'animate',
	), $theme->get( 'Version' ) );

	if ( is_child_theme() ) {
		wp_register_style( 'roxima-style-child', get_stylesheet_directory_uri() . '/style.css', array(
			'roxima-style',
		), $theme->get( 'Version' ) );
	}


	/*
	 * Scripts
	 */
	wp_register_script( 'roxima-google-maps', roxima_get_google_maps_api_url(), array(), null, false );

	wp_register_script( 'superfish', get_template_directory_uri() . '/js/superfish.js', array( 'jquery' ), '1.7.5', true );
	wp_register_script( 'mmenu', get_template_directory_uri() . '/js/jquery.mmenu.min.all.js', array( 'jquery' ), '5.2.0', true );
	wp_register_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array( 'jquery' ), '2.5.0', true );
	wp_register_script( 'fitVids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '1.1', true );
	wp_register_script( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.js', array( 'jquery' ), '1.0.0', true );
	wp_register_script( 'waypoints', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array( 'jquery' ), '4.0.0', true );
	wp_register_script( 'waypoints-sticky', get_template_directory_uri() . '/js/sticky.min.js', array( 'jquery' ), '4.0.0', true );
	wp_register_script( 'wow', get_template_directory_uri() . '/js/wow.min.js', array( 'jquery' ), '1.1.2', true );
	wp_register_script( 'parallax', get_template_directory_uri() . '/js/parallax.min.js', array( 'jquery' ), '1.3.1', true );
	wp_register_script( 'matchHeight', get_template_directory_uri() . '/js/jquery.matchHeight.js', array( 'jquery' ), '1.0.0', true );

	wp_register_script( 'roxima-front-scripts', get_template_directory_uri() . '/js/scripts.js', array(
		'jquery',
		'superfish',
		'mmenu',
		'flexslider',
		'fitVids',
		'magnific-popup',
		'waypoints',
		'waypoints-sticky',
		'wow',
		'parallax',
		'matchHeight'
	), $theme->get( 'Version' ), true );


	/*
	 * Enqueue
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( get_theme_mod( 'google_maps_api_enable', 1 ) ) {
		wp_enqueue_script( 'roxima-google-maps' );
	}

	wp_enqueue_style( 'roxima-style' );
	if ( is_child_theme() ) {
		wp_enqueue_style( 'roxima-style-child' );
	}

	wp_enqueue_script( 'roxima-front-scripts' );


}


?>
