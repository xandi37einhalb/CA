<?php
	class CI_Widget_Contact extends WP_Widget {

		protected $defaults = array(
			'title'             => '',
			'subtitle'          => '',
			'text'              => '',
			'lat'               => 36,
			'lon'               => - 120,
			'zoom'              => 8,
			'balloon_text'      => '',
			'text_color'        => '',
			'background_color'  => '',
			'background_image'  => '',
			'background_repeat' => 'repeat',
			'overlay_color'     => '',
			'parallax'          => '',
			'parallax_speed'    => 4,
			'extra_padding'     => '',
		);

		function __construct() {
			$widget_ops  = array( 'description' => esc_html__( 'Display a map and a contact form.', 'roxima' ) );
			$control_ops = array();
			parent::__construct( 'ci-contact', $name = esc_html__( 'Theme - Contact', 'roxima' ), $widget_ops, $control_ops );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_custom_css' ) );

			// These are needed for the renaming of 'layover_color' to 'overlay_color'
			add_filter( 'widget_display_callback', array( $this, '_rename_old_overlay_field' ), 10, 2 );
			add_filter( 'widget_form_callback', array( $this, '_rename_old_overlay_field' ), 10, 2 );
		}

		// This is needed for the renaming of 'layover_color' to 'overlay_color'
		function _rename_old_overlay_field( $instance, $_this ) {
			$old_field = 'layover_color';
			$class     = get_class( $this );

			if ( get_class($_this) == $class && ! isset( $instance['overlay_color'] ) && isset( $instance[ $old_field ] ) ) {
				$instance['overlay_color'] = $instance[ $old_field ];
				unset( $instance[ $old_field ] );
			}

			return $instance;
		}

		function widget( $args, $instance ) {
			$instance = wp_parse_args( (array) $instance, $this->defaults );

			$id            = $args['id'];
			$before_widget = $args['before_widget'];
			$after_widget  = $args['after_widget'];

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$subtitle     = $instance['subtitle'];
			$text         = $instance['text'];
			$lat          = $instance['lat'];
			$lon          = $instance['lon'];
			$zoom         = $instance['zoom'];
			$balloon_text = $instance['balloon_text'];

			$overlay_color    = $instance['overlay_color'];
			$background_color = $instance['background_color'];
			$background_image = $instance['background_image'];
			$parallax         = $instance['parallax'] == 1 ? 'parallax' : '';
			$parallax_speed   = $instance['parallax'] == 1 ? sprintf( 'data-speed="%s"', esc_attr( $instance['parallax_speed'] / 10 ) ) : '';
			$parallax_image   = $instance['parallax'] == 1 && ! empty( $background_image ) ? sprintf( 'data-image-src="%s" data-parallax="scroll" data-bleed="3"', esc_url( $background_image ) ) : '';
			$extra_padding    = $instance['extra_padding'] == 1 ? 'wrap-extra-pad' : '';

			preg_match( '/class=(["\']).*?widget.*?\1/', $before_widget, $match );
			if ( ! empty( $match ) ) {
				$attr_class    = preg_replace( '/\bwidget\b/', 'widget widget-padded', $match[0], 1 );
				$before_widget = str_replace( $match[0], $attr_class, $before_widget );
			}

			echo $before_widget;

			if ( ! empty( $lat ) && ! empty( $lon ) ) {
				?><div id="ci_map<?php the_ID(); ?>" class="ci-map" data-lat="<?php echo esc_attr( $lat ); ?>" data-lng="<?php echo esc_attr( $lon ); ?>" data-zoom="<?php echo esc_attr( $zoom ); ?>" data-tooltip-txt="<?php echo esc_attr( $balloon_text ); ?>"></div><?php
			}

			?><div class="widget-wrap <?php echo esc_attr( $parallax . ' ' . $extra_padding ); ?>" <?php echo $parallax_speed; ?> <?php echo $parallax_image; ?>><?php

			if ( ! empty( $overlay_color ) ) {
				?><div class="widget-overlay" style="background-color: <?php echo esc_attr( $overlay_color ); ?>"></div><?php
			}

			if ( in_array( $id, roxima_get_fullwidth_sidebars() ) ) {
				?>
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
				<?php
			}

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			if ( $subtitle ) {
				?><p class="section-subtitle"><?php echo esc_html( $subtitle ); ?></p><?php
			}

			echo do_shortcode( shortcode_unautop( wpautop( $text ) ) );

			if ( in_array( $id, roxima_get_fullwidth_sidebars() ) ) {
				?>
						</div>
					</div>
				</div>
				<?php
			}

			?></div><?php

			?><a href="#" class="map-toggle"><?php esc_html_e( 'Karte zeigen', 'roxima' ); ?></a><?php

			echo $after_widget;

		} // widget

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']        = sanitize_text_field( $new_instance['title'] );
			$instance['subtitle']     = sanitize_text_field( $new_instance['subtitle'] );
			$instance['text']         = wp_kses_post( $new_instance['text'] );
			$instance['lat']          = floatval( $new_instance['lat'] );
			$instance['lon']          = floatval( $new_instance['lon'] );
			$instance['zoom']         = absint( $new_instance['zoom'] );
			$instance['balloon_text'] = wp_kses_post( $new_instance['balloon_text'] );

			$instance['text_color']        = roxima_sanitize_hex_color( $new_instance['text_color'] );
			$instance['background_color']  = roxima_sanitize_hex_color( $new_instance['background_color'] );
			$instance['background_image']  = esc_url_raw( $new_instance['background_image'] );
			$instance['background_repeat'] = in_array( $new_instance['background_repeat'], array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y' ) ) ? $new_instance['background_repeat'] : 'repeat';
			$instance['overlay_color']     = roxima_sanitize_rgba_color( $new_instance['overlay_color'] );
			$instance['parallax']          = roxima_sanitize_checkbox_ref( $new_instance['parallax'] );
			$instance['parallax_speed']    = absint( $new_instance['parallax_speed'] );
			$instance['extra_padding']     = roxima_sanitize_checkbox_ref( $new_instance['extra_padding'] );

			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, $this->defaults );

			$title        = $instance['title'];
			$subtitle     = $instance['subtitle'];
			$text         = $instance['text'];
			$lat          = $instance['lat'];
			$lon          = $instance['lon'];
			$zoom         = $instance['zoom'];
			$balloon_text = $instance['balloon_text'];

			$text_color        = $instance['text_color'];
			$background_color  = $instance['background_color'];
			$background_image  = $instance['background_image'];
			$background_repeat = $instance['background_repeat'];
			$overlay_color     = $instance['overlay_color'];
			$parallax          = $instance['parallax'];
			$parallax_speed    = $instance['parallax_speed'];
			$extra_padding     = $instance['extra_padding'];
			?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" /></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>" class="widefat" /></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text (contact form shortcode):', 'roxima' ); ?></label><textarea id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" class="widefat"><?php echo esc_textarea( $text ); ?></textarea></p>

			<p><?php echo wp_kses( __( "Open Google Maps. Right-click the place or area on the map. Select <em>What's here?</em>. A card appears at the bottom of the screen with more info. There, you will see two numbers like <code>37.950788, 23.691166</code>. Enter the first one in Latitude and the second in Longitude.", 'roxima' ), array( 'em' => array(), 'code' => array() ) ); ?></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'lat' ) ); ?>"><?php esc_html_e( 'Latitude:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'lat' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lat' ) ); ?>" type="text" value="<?php echo esc_attr( $lat ); ?>" class="widefat"/></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'lon' ) ); ?>"><?php esc_html_e( 'Longitude:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'lon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lon' ) ); ?>" type="text" value="<?php echo esc_attr( $lon ); ?>" class="widefat"/></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'zoom' ) ); ?>"><?php esc_html_e( 'Map Zoom (0-21):', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'zoom' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'zoom' ) ); ?>" type="number" min="0" max="21" step="1" value="<?php echo esc_attr( $zoom ); ?>" class="widefat"/></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'balloon_text' ) ); ?>"><?php esc_html_e( 'Balloon text (accepts HTML):', 'roxima' ); ?></label><textarea id="<?php echo esc_attr( $this->get_field_id( 'balloon_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'balloon_text' ) ); ?>" class="widefat"><?php echo esc_textarea( $balloon_text ); ?></textarea></p>


			<fieldset class="ci-collapsible">
				<legend><?php esc_html_e( 'Customize', 'roxima' ); ?> <i class="dashicons dashicons-arrow-down"></i></legend>
				<div class="elements">
					<p><label for="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>"><?php esc_html_e( 'Text Color:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text_color' ) ); ?>" type="text" value="<?php echo esc_attr( $text_color ); ?>" class="colorpckr widefat"/></p>
					<p><label for="<?php echo esc_attr( $this->get_field_id( 'background_color' ) ); ?>"><?php esc_html_e( 'Background Color:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'background_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'background_color' ) ); ?>" type="text" value="<?php echo esc_attr( $background_color ); ?>" class="colorpckr widefat"/></p>

					<p class="ci-collapsible-media"><label for="<?php echo esc_attr( $this->get_field_id( 'background_image' ) ); ?>"><?php esc_html_e( 'Background Image:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'background_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'background_image' ) ); ?>" type="text" value="<?php echo esc_attr( $background_image ); ?>" class="ci-uploaded-url widefat"/><a href="#" class="button ci-media-button"><?php esc_html_e( 'Select', 'roxima' ); ?></a></p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'background_repeat' ) ); ?>"><?php esc_html_e( 'Background Repeat:', 'roxima' ); ?></label>
						<select id="<?php echo esc_attr( $this->get_field_id( 'background_repeat' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'background_repeat' ) ); ?>">
							<option value="repeat" <?php selected( 'repeat', $background_repeat ); ?>><?php esc_html_e( 'Repeat', 'roxima' ); ?></option>
							<option value="repeat-x" <?php selected( 'repeat-x', $background_repeat ); ?>><?php esc_html_e( 'Repeat Horizontally', 'roxima' ); ?></option>
							<option value="repeat-y" <?php selected( 'repeat-y', $background_repeat ); ?>><?php esc_html_e( 'Repeat Vertically', 'roxima' ); ?></option>
							<option value="no-repeat" <?php selected( 'no-repeat', $background_repeat ); ?>><?php esc_html_e( 'No Repeat', 'roxima' ); ?></option>
						</select>
					</p>
					<p><label for="<?php echo esc_attr( $this->get_field_id( 'overlay_color' ) ); ?>"><?php esc_html_e( 'Overlay Color:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'overlay_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'overlay_color' ) ); ?>" type="text" value="<?php echo esc_attr( $overlay_color ); ?>" class="widefat alpha-color-picker" /></p>

					<p><label for="<?php echo esc_attr( $this->get_field_id( 'parallax' ) ); ?>"><input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'parallax' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'parallax' ) ); ?>" value="1" <?php checked( $parallax, 1 ); ?> /><?php esc_html_e( 'Parallax effect (requires a background image).', 'roxima' ); ?></label></p>
					<p><label for="<?php echo esc_attr( $this->get_field_id( 'parallax_speed' ) ); ?>"><?php esc_html_e( 'Parallax speed (1-10):', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'parallax_speed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'parallax_speed' ) ); ?>" type="number" min="1" max="10" step="1" value="<?php echo esc_attr( $parallax_speed ); ?>" class="widefat"/></p>

					<p><label for="<?php echo esc_attr( $this->get_field_id( 'extra_padding' ) ); ?>"><input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'extra_padding' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'extra_padding' ) ); ?>" value="1" <?php checked( $extra_padding, 1 ); ?> /><?php esc_html_e( 'Apply extra padding.', 'roxima' ); ?></label></p>
				</div>
			</fieldset>

			<?php
		} // form

		protected function sanitize_instance_services( $instance ) {
			if ( empty( $instance ) || ! is_array( $instance ) ) {
				return array();
			}

			$titles      = $instance['service_title'];
			$texts       = $instance['service_text'];
			$icons       = $instance['service_icon'];
			$icon_colors = $instance['service_icon_color'];

			$count = max(
				count( $titles ),
				count( $texts ),
				count( $icons ),
				count( $icon_colors )
			);

			$new_fields = array();

			$records_count = 0;

			for ( $i = 0; $i < $count; $i++ ) {
				if ( empty( $titles[ $i ] )
				     && empty( $texts[ $i ] )
				     && empty( $icons[ $i ] )
				     && empty( $icon_colors[ $i ] )
				) {
					continue;
				}

				$new_fields[ $records_count ]['title']      = sanitize_text_field( $titles[ $i ] );
				$new_fields[ $records_count ]['text']       = sanitize_text_field( $texts[ $i ] );
				$new_fields[ $records_count ]['icon']       = sanitize_key( $icons[ $i ] );
				$new_fields[ $records_count ]['icon_color'] = roxima_sanitize_hex_color( $icon_colors[ $i ] );

				$records_count++;
			}
			return $new_fields;
		}

		function enqueue_custom_css() {
			$settings = $this->get_settings();

			if ( empty( $settings ) ) {
				return;
			}

			foreach ( $settings as $instance_id => $instance ) {
				$id = $this->id_base . '-' . $instance_id;

				if ( ! is_active_widget( false, $id, $this->id_base ) ) {
					continue;
				}

				$instance = wp_parse_args( (array) $instance, $this->defaults );

				$sidebar_id      = false; // Holds the sidebar id that the widget is assigned to.
				$sidebar_widgets = wp_get_sidebars_widgets();
				if ( ! empty( $sidebar_widgets ) ) {
					foreach ( $sidebar_widgets as $sidebar => $widgets ) {
						// We need to check $widgets for emptiness due to https://core.trac.wordpress.org/ticket/14876
						if ( ! empty( $widgets ) && array_search( $id, $widgets ) !== false ) {
							$sidebar_id = $sidebar;
						}
					}
				}

				$text_color        = $instance['text_color'];
				$background_color  = $instance['background_color'];
				$background_image  = $instance['background_image'];
				$background_repeat = $instance['background_repeat'];

				$css         = '';
				$padding_css = '';

				if ( ! empty( $text_color ) ) {
					$css .= 'color: ' . $text_color . '; ';
				}
				if ( ! empty( $background_color ) ) {
					$css .= 'background-color: ' . $background_color . '; ';
				}
				if ( ! empty( $background_image ) ) {
					$css .= 'background-image: url(' . esc_url( $background_image ) . ');';
					$css .= 'background-repeat: ' . $background_repeat . ';';
				}

				if ( ! empty( $css ) || ! empty( $padding_css ) ) {
					$css = '#' . $id . ' .widget-wrap { ' . $css . $padding_css . ' } ' . PHP_EOL;
					wp_add_inline_style( 'roxima-style', $css );
				}

			}

		}

	} // class

	register_widget( 'CI_Widget_Contact' );
