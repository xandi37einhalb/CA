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

/* Adds the child theme setup function to the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'silvia_child_theme_setup', 11 );

/**
 * Setup function. All child themes should run their setup within this function. The idea is to add/remove 
 * filters and actions after the parent theme has been set up. This function provides you that opportunity.
 *
 * @since 1.0
 */
function silvia_child_theme_setup() {
	function silvia_newssidebar_init() {

		register_sidebar(
			array(
				'name'          => __( 'News Sidebar', 'silvia' ),
				'id'            => 'newstechnik',
				'description'   => __( 'Technik News Sidebar auf rechter Seite', 'silvia' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

	}
	add_action( 'widgets_init', 'silvia_newssidebar_init' );
}