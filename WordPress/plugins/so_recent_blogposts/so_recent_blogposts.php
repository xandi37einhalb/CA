<?php
/**
 * Plugin Name: Stessa Onda recent blogposts
 * Plugin URI: https://www.stessaonda.net.org/
 * Description: Display recent blogposts from other WP installation via API
 * Version: 1.0.0
 * Author: Lukas Kamber
 * Author URI: https://stessaonda.net.org
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 * 
 * @package Plugin Stessa Onda recent blogposts
 * @since 1.0.0
 */

global $wpdb;

if( !defined( 'WP_SORB_DIR' ) ) {
	define( 'WP_SORB_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WP_SORB_URL' ) ) {
	define( 'WP_SORB_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'WP_SORB_BASENAME') ) {
	define( 'WP_SORB_BASENAME', 'so_recent_blogposts' ); // plugin base name
}
if( !defined( 'WP_SORB_ADMIN' ) ) {
	define( 'WP_SORB_ADMIN', WP_SORB_DIR . '/includes/admin' ); // plugin admin dir
}

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Plugin Stessa Onda recent blogposts
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wp_sorb_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package Stessa Onda recent blogposts
 * @since 1.0.0
 */
function wp_sorb_install() {
	global $wpdb;	
}

//plugin loaded action
add_action( 'plugins_loaded', 'wp_sorb_plugins_loaded' );

/**
 * Plugin Loaded Action
 * 
 * @package Plugin Stessa Onda recent blogposts
 * @since 1.0.0
 */
function wp_sorb_plugins_loaded() {
	
	/**
	 * Load Text Domain
	 * 
	 * This gets the plugin ready for translation.
	 * 
	 * @package Stessa Onda recent blogposts
	 * @since 1.0.0
	 */
	load_plugin_textdomain( 'wpsorb', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	
	register_deactivation_hook( __FILE__, 'wp_sorb_uninstall');
	
	/**
	 * Deactivation Hook
	 * 
	 * Register plugin deactivation hook.
	 * 
	 * @package Stessa Onda recent blogposts
	 * @since 1.0.0
	 */
	function wp_sorb_uninstall() {
		
		global $wpdb;
	}
	
	//Global variables
	global $wp_sorb_shortcode;
	
	//Shortcode related functionality
	require_once( WP_SORB_DIR . '/includes/class-so-recent-blogposts.php' );
	$wp_sorb_shortcode	= new StessaOnda_Recent_Blogposts();
	$wp_sorb_shortcode->add_hooks();
	
}