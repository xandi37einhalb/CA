<?php
	class CI_Widget_Latest_Posts_CA extends WP_Widget {

		protected $defaults = array(
			'title'             => '',
			'subtitle'          => '',
			'category'          => '',
			'random'            => '',
			'count'             => 3,
			'effects_enabled'   => '',
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
			$widget_ops  = array( 'description' => esc_html__( 'Latest or random post, optionally from a specific category.', 'roxima' ) );
			$control_ops = array();
			parent::__construct( 'ci-latest-posts', $name = esc_html__( 'Theme - Latest Posts', 'roxima' ), $widget_ops, $control_ops );

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

			$subtitle = $instance['subtitle'];
			$category = $instance['category'];
			$random   = $instance['random'];
			$count    = $instance['count'];

			if ( 0 == $count ) {
				return;
			}

			$query_args = array(
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => $count
			);

			if ( 1 == $random ) {
				$query_args['orderby'] = 'rand';
				unset( $query_args['order'] );
			}

			if( ! empty( $category ) && $category > 0 ) {
				$query_args['cat'] = intval( $category );
			}

			$q = new WP_Query( $query_args );


			$overlay_color    = $instance['overlay_color'];
			$background_color = $instance['background_color'];
			$background_image = $instance['background_image'];
			$parallax         = $instance['parallax'] == 1 ? 'parallax' : '';
			$parallax_speed   = $instance['parallax'] == 1 ? sprintf( 'data-speed="%s"', esc_attr( $instance['parallax_speed'] / 10 ) ) : '';
			$parallax_image   = $instance['parallax'] == 1 && ! empty( $background_image ) ? sprintf( 'data-image-src="%s" data-parallax="scroll" data-bleed="3"', esc_url( $background_image ) ) : '';
			$extra_padding    = $instance['extra_padding'] == 1 ? 'wrap-extra-pad' : '';

			if ( ! empty( $background_color ) || ! empty( $background_image ) ) {
				preg_match( '/class=(["\']).*?widget.*?\1/', $before_widget, $match );
				if ( ! empty( $match ) ) {
					$attr_class    = preg_replace( '/\bwidget\b/', 'widget widget-padded', $match[0], 1 );
					$before_widget = str_replace( $match[0], $attr_class, $before_widget );
				}
			}

			$column_classes    = roxima_get_columns_classes( roxima_best_number_of_columns( $q->post_count ) );
			$animation_classes = $instance['effects_enabled'] == 1 ? 'wow zoomInDown' : '';

			echo $before_widget;

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

			?><div class="row item-list"><?php

			$wow_delay = 0;

			if ( $q->have_posts() ) {
				while ( $q->have_posts() ) {
					$q->the_post();
					?>
					<div class="<?php echo esc_attr( $column_classes . ' ' . $animation_classes ); ?>" data-wow-delay="<?php echo esc_attr( $wow_delay ); ?>s">
						<?php get_template_part( 'itemCA', get_post_type() ); ?>
					</div>
					<?php

					$wow_delay += apply_filters( 'roxima_widget_latest_posts_wow_delay', 0.08 );
				}
				wp_reset_postdata();
			}

			?></div><?php

			if ( in_array( $id, roxima_get_fullwidth_sidebars() ) ) {
				?>
						</div>
					</div>
				</div>
				<?php
			}

			?></div><?php

			echo $after_widget;

		} // widget

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']    = sanitize_text_field( $new_instance['title'] );
			$instance['subtitle'] = sanitize_text_field( $new_instance['subtitle'] );
			$instance['category'] = roxima_sanitize_intval_or_empty( $new_instance['category'] );
			$instance['random']   = roxima_sanitize_checkbox( $new_instance['random'] );
			$instance['count']    = absint( $new_instance['count'] ) > 0 ? absint( $new_instance['count'] ) : 3;

			$instance['effects_enabled']   = roxima_sanitize_checkbox_ref( $new_instance['effects_enabled'] );
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

			$title    = $instance['title'];
			$subtitle = $instance['subtitle'];
			$category = $instance['category'];
			$random   = $instance['random'];
			$count    = $instance['count'];

			$effects_enabled   = $instance['effects_enabled'];
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

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category to display the latest posts from (optional):', 'roxima' ); ?></label>
			<?php wp_dropdown_categories( array(
				'taxonomy'          => 'category',
				'show_option_all'   => '',
				'show_option_none'  => ' ',
				'option_none_value' => '',
				'show_count'        => 1,
				'echo'              => 1,
				'selected'          => $category,
				'hierarchical'      => 1,
				'name'              => $this->get_field_name( 'category' ),
				'id'                => $this->get_field_id( 'category' ),
				'class'             => 'postform widefat',
			) ); ?>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'random' ) ); ?>"><input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'random' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'random' ) ); ?>" value="1" <?php checked( $random, 1 ); ?> /><?php esc_html_e( 'Show random posts.', 'roxima' ); ?></label></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $count ); ?>" class="widefat"/></p>

			<fieldset class="ci-collapsible">
				<legend><?php esc_html_e( 'Customize', 'roxima' ); ?> <i class="dashicons dashicons-arrow-down"></i></legend>
				<div class="elements">
					<p><label for="<?php echo esc_attr( $this->get_field_id( 'effects_enabled' ) ); ?>"><input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'effects_enabled' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'effects_enabled' ) ); ?>" value="1" <?php checked( $effects_enabled, 1 ); ?> /><?php esc_html_e( 'Enable animation effect.', 'roxima' ); ?></label></p>

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

				

			}

		}

	} // class

	register_widget( 'CI_Widget_Latest_Posts_CA' );
