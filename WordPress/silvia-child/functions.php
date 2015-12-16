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
	// Add your custom functions here.
}