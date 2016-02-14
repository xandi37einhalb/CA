<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcodes Class
 *
 * Handles shortcodes functionality of plugin
 *
 * @package Plugin Sample Shortcode
 * @since 1.0.1
 */
class StessaOnda_Recent_Blogposts {
	
	function __construct() {
		
	}
	
	/**
	 * shortcode simple tutorial
	 * 
	 * @package Stessa Onda recent blogposts
	 * @since 1.0.0
	 */
	function stessaonda_recent_blogposts( $atts ) {
		setlocale(LC_ALL, 'de_CH.UTF-8');
		$atts	= shortcode_atts( array(
									'jsonurl'	=> 'jsonurl'
								), $atts );
	
		$result = file_get_contents($atts['jsonurl']);
		$json_object = json_decode($result, true);
		$return = '<div class="row assorted">';
		foreach ($json_object as $blog_object) {
			try {
    			$date = new DateTime($blog_object['date']);
			} catch (Exception $e) {
				$date = new DateTime();
			}
			$thumb_url = false;
			foreach ($blog_object['_links'] as $key => $val) {
				if (substr($key, -13) == 'featuredmedia') {
					if (isset($val[0])) {
						if (isset($val[0]['href'])) {
							$result = file_get_contents($val[0]['href']);
							$thumb_object = json_decode($result, true);
							if (isset($thumb_object['media_details']['sizes']['medium'])) {
								$thumb_url = $thumb_object['media_details']['sizes']['medium']['source_url'];
							}
						}
					}
				}
			}
			$return .= '<div class="four columns">';
			$return .= '<p class="bloglink_date">';
			$return .= strftime('%d. %B', $date->format('U'));
			$return .= '</p>';
			if ($thumb_url) {
				$return .= '<a href="' . $blog_object['link'] . '" target="_blank">';
				$return .= '<img src="' . $thumb_url . '" . alt="' . $title . '" title="' . $title . '" />';
				$return .= '</a>';
			}
			$return .= '<h3 class="post_title blog_title">';
			$return .= '<a href="' . $blog_object['link'] . '" target="_blank">';
			$title = '[kein Titel]';
			if (isset($blog_object['title']['rendered'])) {
				$title = $blog_object['title']['rendered'];
			}
			$return .= $title;
			$return .= '</a>';
			$return .= '</h3>';
			$return .= '</div>'; // class="row assorted"
			#print_r($blog_object);
		}
		$return .= '</div>'; // class="row assorted"
		return $return;
		#return print_r($json_object, 1);
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding proper hoocks for the shortcodes.
	 *
	 * @package Stessa Onda recent blogposts
	 * @since 1.0.1
	 */
	public function add_hooks() {
		
		//add sample shortcode
		add_shortcode( 'so_recentblog', array( $this, 'stessaonda_recent_blogposts' ) );
	}
}