<?php
	class CI_Widget_Pricing_Table extends WP_Widget {

		protected $defaults = array(
			'title'             => '',
			'subtitle'          => '',
			'mindestteilnehmer' => '',
			'options'           => array(),
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
			$widget_ops  = array( 'description' => esc_html__( 'Pricing table comparison.', 'roxima' ) );
			$control_ops = array();
			parent::__construct( 'ci-pricing-table', $name = esc_html__( 'Theme - Pricing Table', 'roxima' ), $widget_ops, $control_ops );

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
			$mindestteilnehmer = $instance['mindestteilnehmer'];
			$options  = $instance['options'];

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

			$column_classes    = roxima_get_columns_classes( roxima_best_number_of_columns( count( $options ) ) );
			$animation_classes = $instance['effects_enabled'] == 1 ? 'wow fadeInDown' : '';

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
				?><p class="section-subtitle"><?php echo esc_html( $subtitle ); ?><?php
			}
			
			if ( $mindestteilnehmer ) {
			    ?><?php echo '<br><br>Mindestteilnehmerzahl: ' . esc_html( $mindestteilnehmer ) . ' Personen'; ?></p><?php
			}

			?><div class="row table-pricing"><?php

			$wow_delay = 0;

			foreach ( $options as $option ) {
				$featured_class = $option['featured'] == 1 ? 'item-pricing-featured' : '';
				?>
				<div class="<?php echo esc_attr( $column_classes . ' ' . $animation_classes ); ?>" data-wow-delay="<?php echo esc_attr( $wow_delay ); ?>s" data-wow-duration="0.6s">
					<div class="item-pricing <?php echo esc_attr( $featured_class ); ?>">
				<?php

				if ( ! empty( $option['title'] ) ) {
					?><h3 class="item-title"><?php echo esc_html( $option['title'] ); ?></h3><?php
				}

				if ( ! empty( $option['subtitle'] ) ) {
					?><p class="item-subtitle el-underline"><?php echo esc_html( $option['subtitle'] ); ?></p><?php
				}

				if ( ! empty( $option['price'] ) ) {
					?><p class="item-price"><?php
						echo esc_html( $option['price'] );

						if ( ! empty( $option['suffix'] ) ) {
							?> <span class="recurring"><?php echo esc_html( $option['suffix'] ); ?></span><?php
						}
					?></p><?php
				}

				?><ul class="item-features"><?php
					for ( $feature_no = 1; $feature_no <= 8; $feature_no ++ ) {
						$feature = $option["feature_{$feature_no}"];
						if ( ! empty( $feature ) ) {
							$class_attr = '';
							if( roxima_substr_left( $feature, 1 ) == '-' ) {
								$class_attr = 'class="no"';
								$feature    = mb_substr( $feature, 1 );
							}
							?><li <?php echo $class_attr; ?>><?php echo esc_html( $feature ); ?></li><?php
						}
					}
				?></ul><?php


				if ( ! empty( $option['button_text'] ) && ! empty( $option['button_url'] ) ) {
					?>
					<div class="item-pricing-action">
						<a href="<?php echo esc_url( $option['button_url'] ); ?>" class="btn"><?php echo esc_html( $option['button_text'] ); ?></a>
					</div>
					<?php
				}

				?>
					</div>
				</div>
				<?php

				$wow_delay += apply_filters( 'roxima_widget_pricing_table_wow_delay', 0.05 );
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
			$instance['mindestteilnehmer'] = $new_instance['mindestteilnehmer'];
			$instance['options'] = $this->sanitize_instance_options( $new_instance );

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
			$mindestteilnehmer = $instance['mindestteilnehmer'];
			$options  = $instance['options'];

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
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'mindestteilnehmer' ) ); ?>"><?php esc_html_e( 'Mindestteilnehmer:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'mindestteilnehmer' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mindestteilnehmer' ) ); ?>" type="text" value="<?php echo esc_attr( $mindestteilnehmer ); ?>" class="widefat" /></p>

			<p><?php esc_html_e( 'Add as many items as you want by pressing the "Add Item" button. Remove any item by selecting "Remove me".', 'roxima' ); ?></p>
			<fieldset class="ci-repeating-fields">
				<div class="inner">
					<?php
						if ( ! empty( $options ) ) {
							$count = count( $options );
							for( $i = 0; $i < $count; $i++ ) {
								?>
								<div class="post-field">
									<label class="post-field-item"><?php esc_html_e( 'Title:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_title' ) . '[]' ); ?>" value="<?php echo esc_attr( $options[ $i ]['title'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Subtitle:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_subtitle' ) . '[]' ); ?>" value="<?php echo esc_attr( $options[ $i ]['subtitle'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Price (include currency symbol):', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_price' ) . '[]' ); ?>" value="<?php echo esc_attr( $options[ $i ]['price'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php echo wp_kses( __( 'Price suffix (e.g. <em>/ year</em>):', 'roxima' ), array( 'em' => array() ) ); ?>
										<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_suffix' ) . '[]' ); ?>" value="<?php echo esc_attr( $options[ $i ]['suffix'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Is featured:', 'roxima' ); ?>
										<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'option_featured' ) . '[]' ); ?>">
											<option value="0" <?php selected( '0', $options[ $i ]['featured'] ); ?>><?php esc_html_e( 'No', 'roxima' ); ?></option>
											<option value="1" <?php selected( '1', $options[ $i ]['featured'] ); ?>><?php esc_html_e( 'Yes', 'roxima' ); ?></option>
										</select>
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Button text:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_button_text' ) . '[]' ); ?>" value="<?php echo esc_attr( $options[ $i ]['button_text'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Button URL:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_button_url' ) . '[]' ); ?>" value="<?php echo esc_url( $options[ $i ]['button_url'] ); ?>" class="widefat" />
									</label>

									<p><?php echo wp_kses( __( 'Prepend a feature with a hyphen <code>-</code> to denote that the feature is <strong>not</strong> included.', 'roxima' ), array( 'code' => array(), 'strong' => array() ) ); ?></p>
									<?php for( $feature_no = 1; $feature_no <= 8; $feature_no++ ): ?>
										<?php /* translators: #%1$d is the symbol # (that denotes a number in series) followed by a number, e.g. #1 */ ?>
										<label class="post-field-item"><?php echo sprintf( esc_html__( 'Feature #%1$d:', 'roxima' ), $feature_no ); ?>
											<input type="text" name="<?php echo esc_attr( $this->get_field_name( "option_feature_{$feature_no}" ) . '[]' ); ?>" value="<?php echo esc_attr( $options[ $i ]["feature_{$feature_no}"] ); ?>" class="widefat" />
										</label>
									<?php endfor; ?>

									<p class="ci-repeating-remove-action"><a href="#" class="button ci-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php esc_html_e( 'Remove me', 'roxima' ); ?></a></p>
								</div>
								<?php
							}
						}
					?>
					<?php
					//
					// Add an empty and hidden set for jQuery
					//
					?>
					<div class="post-field field-prototype" style="display: none;">
						<label class="post-field-item"><?php esc_html_e( 'Title:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_title' ) . '[]' ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Subtitle:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_subtitle' ) . '[]' ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Price (include currency symbol):', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_price' ) . '[]' ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php echo wp_kses( __( 'Price suffix (e.g. <em>/ year</em>):', 'roxima' ), array( 'em' => array() ) ); ?>
							<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_suffix' ) . '[]' ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Is featured:', 'roxima' ); ?>
							<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'option_featured' ) . '[]' ); ?>">
								<option value="0"><?php esc_html_e( 'No', 'roxima' ); ?></option>
								<option value="1"><?php esc_html_e( 'Yes', 'roxima' ); ?></option>
							</select>
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Button text:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_button_text' ) . '[]' ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Button URL:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'option_button_url' ) . '[]' ); ?>" value="" class="widefat" />
						</label>

						<p><?php echo wp_kses( __( 'Prepend a feature with a hyphen <code>-</code> to denote that the feature is <strong>not</strong> included.', 'roxima' ), array( 'code' => array(), 'strong' => array() ) ); ?></p>
						<?php for( $feature_no = 1; $feature_no <= 8; $feature_no++ ): ?>
							<?php /* translators: #%1$d is the symbol # (that denotes a number in series) followed by a number, e.g. #1 */ ?>
							<label class="post-field-item"><?php echo sprintf( esc_html__( 'Feature #%1$d:', 'roxima' ), $feature_no ); ?>
								<input type="text" name="<?php echo esc_attr( $this->get_field_name( "option_feature_{$feature_no}" ) . '[]' ); ?>" value="" class="widefat" />
							</label>
						<?php endfor; ?>

						<p class="ci-repeating-remove-action"><a href="#" class="button ci-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php esc_html_e( 'Remove me', 'roxima' ); ?></a></p>
					</div>
				</div>
				<a href="#" class="ci-repeating-add-field button"><i class="dashicons dashicons-plus-alt"></i><?php esc_html_e( 'Add Item', 'roxima' ); ?></a>
			</fieldset>

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

		protected function sanitize_instance_options( $instance ) {

			if ( empty( $instance ) || ! is_array( $instance ) ) {
				return array();
			}

			$titles       = $instance['option_title'];
			$subtitles    = $instance['option_subtitle'];
			$prices       = $instance['option_price'];
			$suffixes     = $instance['option_suffix'];
			$featured     = $instance['option_featured'];
			$button_texts = $instance['option_button_text'];
			$button_urls  = $instance['option_button_url'];
			$features_1   = $instance['option_feature_1'];
			$features_2   = $instance['option_feature_2'];
			$features_3   = $instance['option_feature_3'];
			$features_4   = $instance['option_feature_4'];
			$features_5   = $instance['option_feature_5'];
			$features_6   = $instance['option_feature_6'];
			$features_7   = $instance['option_feature_7'];
			$features_8   = $instance['option_feature_8'];

			$count = max(
				count( $titles ),
				count( $subtitles ),
				count( $prices ),
				count( $suffixes ),
				count( $featured ),
				count( $button_texts ),
				count( $button_urls ),
				count( $features_1 ),
				count( $features_2 ),
				count( $features_3 ),
				count( $features_4 ),
				count( $features_5 ),
				count( $features_6 ),
				count( $features_7 ),
				count( $features_8 )
			);

			$new_fields = array();

			$records_count = 0;

			for ( $i = 0; $i < $count; $i++ ) {
				if ( empty( $titles[ $i ] )
				     && empty( $subtitles[ $i ] )
				     && empty( $prices[ $i ] )
				     && empty( $button_texts[ $i ] )
				     && empty( $button_urls[ $i ] )
				     && empty( $features_1[ $i ] )
				     && empty( $features_2[ $i ] )
				     && empty( $features_3[ $i ] )
				     && empty( $features_4[ $i ] )
				     && empty( $features_5[ $i ] )
				     && empty( $features_6[ $i ] )
				     && empty( $features_7[ $i ] )
				     && empty( $features_8[ $i ] )
				) {
					continue;
				}

				$new_fields[ $records_count ]['title']       = sanitize_text_field( $titles[ $i ] );
				$new_fields[ $records_count ]['subtitle']    = sanitize_text_field( $subtitles[ $i ] );
				$new_fields[ $records_count ]['price']       = sanitize_text_field( $prices[ $i ] );
				$new_fields[ $records_count ]['suffix']      = sanitize_text_field( $suffixes[ $i ] );
				$new_fields[ $records_count ]['featured']    = intval( $featured[ $i ] ) == 1 ? 1 : 0;
				$new_fields[ $records_count ]['button_text'] = sanitize_text_field( $button_texts[ $i ] );
				$new_fields[ $records_count ]['button_url']  = esc_url_raw( $button_urls[ $i ] );

				$new_fields[ $records_count ]['feature_1'] = sanitize_text_field( $features_1[ $i ] );
				$new_fields[ $records_count ]['feature_2'] = sanitize_text_field( $features_2[ $i ] );
				$new_fields[ $records_count ]['feature_3'] = sanitize_text_field( $features_3[ $i ] );
				$new_fields[ $records_count ]['feature_4'] = sanitize_text_field( $features_4[ $i ] );
				$new_fields[ $records_count ]['feature_5'] = sanitize_text_field( $features_5[ $i ] );
				$new_fields[ $records_count ]['feature_6'] = sanitize_text_field( $features_6[ $i ] );
				$new_fields[ $records_count ]['feature_7'] = sanitize_text_field( $features_7[ $i ] );
				$new_fields[ $records_count ]['feature_8'] = sanitize_text_field( $features_8[ $i ] );

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

	register_widget( 'CI_Widget_Pricing_Table' );
