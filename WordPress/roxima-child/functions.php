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
}

?>