<?php
	class CI_Widget_Team_CA extends WP_Widget {

		protected $defaults = array(
			'title'             => '',
			'subtitle'          => '',
			'members'           => array(),
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
			$widget_ops  = array( 'description' => esc_html__( 'Team showcase with social icons.', 'roxima' ) );
			$control_ops = array();
			parent::__construct( 'ci-team', $name = esc_html__( 'Theme - Team', 'roxima' ), $widget_ops, $control_ops );

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
			$members  = $instance['members'];

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

			$column_classes    = roxima_get_columns_classes( roxima_best_number_of_columns( count( $members ) ) );
			$animation_classes = $instance['effects_enabled'] == 1 ? 'wow zoomIn' : '';

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

			foreach ( $members as $member ) {
				?>
				<div class="<?php echo esc_attr( $column_classes . ' ' . $animation_classes ); ?>" data-wow-delay="<?php echo esc_attr( $wow_delay ); ?>s">
					<div class="item item-team item-align-center">
				<?php

				$image_url = roxima_get_image_src( $member['image_id'], 'roxima_square_small' );
				if ( ! empty( $image_url ) ) {
					$attachment = wp_prepare_attachment_for_js( $member['image_id'] );
					?>
					<figure class="item-thumb item-thumb-round">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $attachment['alt'] ); ?>">
					</figure>
					<?php
				}

				if ( ! empty( $member['name'] ) ) {
					?><p class="item-title"><?php echo esc_html( $member['name'] ); ?></p><?php
				}

				if ( ! empty( $member['role'] ) ) {
					?><p class="item-subtitle el-underline"><?php echo esc_html( $member['role'] ); ?></p><?php
				}

				?><div class="item-socials"><?php
					for ( $icon_no = 1; $icon_no <= 4; $icon_no ++ ) {
						if ( ! empty( $member["icon_{$icon_no}"] ) && ! empty( $member["icon_{$icon_no}_url"] ) ) {
							?><a class="social-icon" href="<?php echo esc_url( $member["icon_{$icon_no}_url"] ); ?>" target="_blank"><i class="fa <?php echo esc_attr( $member["icon_{$icon_no}"] ); ?>"></i></a><?php
						}
					}
				?></div><?php


				?><div class="item-team-details mfp-hide"><?php

					$image_url = roxima_get_image_src( $member['image_id'], 'roxima_square_small' );
					if ( ! empty( $image_url ) ) {
						$attachment = wp_prepare_attachment_for_js( $member['image_id'] );
						?>
						<figure class="item-thumb item-thumb-round">
							<img src="<?php echo esc_url( $image_url ); ?>" width="120" height="120" alt="<?php echo esc_attr( $attachment['alt'] ); ?>">
						</figure>
						<?php
					}

					if ( ! empty( $member['name'] ) ) {
						?><p class="item-title"><?php echo esc_html( $member['name'] ); ?></p><?php
					}

					if ( ! empty( $member['role'] ) ) {
						?><p class="item-subtitle"><?php echo esc_html( $member['role'] ); ?></p><?php
					}

					?><div class="item-socials"><?php
						for ( $icon_no = 1; $icon_no <= 4; $icon_no ++ ) {
							if ( ! empty( $member["icon_{$icon_no}"] ) && ! empty( $member["icon_{$icon_no}_url"] ) ) {
								?><a class="social-icon" href="<?php echo esc_url( $member["icon_{$icon_no}_url"] ); ?>" target="_blank"><i class="fa <?php echo esc_attr( $member["icon_{$icon_no}"] ); ?>"></i></a><?php
							}
						}
					?></div><?php

					if ( ! empty( $member['text'] ) ) {
						echo wpautop( $member['text'] );
					}

				?></div><?php


				?>
					</div>
				</div>
				<?php

				$wow_delay += apply_filters( 'roxima_widget_team_wow_delay', 0.07 );
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
			$instance['members']  = $this->sanitize_instance_members( $new_instance );

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
			$members  = $instance['members'];

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

			<p><?php esc_html_e( 'Add as many items as you want by pressing the "Add Item" button. Remove any item by selecting "Remove me".', 'roxima' ); ?></p>
			<fieldset class="ci-repeating-fields">
				<div class="inner">
					<?php
						if ( ! empty( $members ) ) {
							$count = count( $members );
							for( $i = 0; $i < $count; $i++ ) {
								?>
								<div class="post-field">
									<label class="post-field-item"><?php esc_html_e( 'Name:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'member_name' ) . '[]' ); ?>" value="<?php echo esc_attr( $members[ $i ]['name'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Role:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'member_role' ) . '[]' ); ?>" value="<?php echo esc_attr( $members[ $i ]['role'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Text (in lightbox):', 'roxima' ); ?>
										<textarea name="<?php echo esc_attr( $this->get_field_name( 'member_text' ) . '[]' ); ?>" class="widefat"><?php echo esc_textarea( $members[ $i ]['text'] ); ?></textarea>
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Image:', 'roxima' ); ?>
										<div class="ci-upload-preview">
											<div class="upload-preview">
												<?php if ( ! empty( $members[ $i ]['image_id'] ) ): ?>
													<?php
														$image_url = roxima_get_image_src( $members[ $i ]['image_id'], 'roxima_featgal_small_thumb' );
														echo sprintf( '<img src="%s" /><a href="#" class="close media-modal-icon" title="%s"></a>',
															esc_url( $image_url ),
															esc_attr__( 'Remove image', 'roxima' )
														);
													?>
												<?php endif; ?>
											</div>
											<input type="hidden" class="ci-uploaded-id" name="<?php echo esc_attr( $this->get_field_name( 'member_image_id' ) . '[]' ); ?>" value="<?php echo esc_attr( $members[ $i ]['image_id'] ); ?>" />
											<input type="button" class="button ci-media-button" value="<?php esc_attr_e( 'Select Image', 'roxima' ); ?>" />
										</div>
									</label>

									<?php for( $icon_no = 1; $icon_no <= 4; $icon_no++ ): ?>
										<?php /* translators: #%1$d is the symbol # (that denotes a number in series) followed by a number, e.g. #1 */ ?>
										<label class="post-field-item"><?php echo wp_kses( sprintf( __( 'Social #%1$d Icon (e.g. <code>fa-facebook</code> <a href="%2$s" target="_blank">Reference</a>):', 'roxima' ), $icon_no, 'https://fortawesome.github.io/Font-Awesome/icons/#brand' ), array( 'code' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ); ?>
											<input type="text" name="<?php echo esc_attr( $this->get_field_name( "member_icon_{$icon_no}" ) . '[]' ); ?>" value="<?php echo esc_attr( $members[ $i ]["icon_{$icon_no}"] ); ?>" class="widefat" />
										</label>
										<?php /* translators: #%1$d is the symbol # (that denotes a number in series) followed by a number, e.g. #1 */ ?>
										<label class="post-field-item"><?php echo sprintf( esc_html__( 'Social #%1$d URL:', 'roxima' ), $icon_no ); ?>
											<input type="text" name="<?php echo esc_attr( $this->get_field_name( "member_icon_{$icon_no}_url" ) . '[]' ); ?>" value="<?php echo esc_attr( $members[ $i ]["icon_{$icon_no}_url"] ); ?>" class="widefat" />
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
						<label class="post-field-item"><?php esc_html_e( 'Name:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'member_name' ) . '[]' ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Role:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'member_role' ) . '[]' ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Text (in lightbox):', 'roxima' ); ?>
							<textarea name="<?php echo esc_attr( $this->get_field_name( 'member_text' ) . '[]' ); ?>" class="widefat"></textarea>
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Image:', 'roxima' ); ?>
							<div class="ci-upload-preview">
								<div class="upload-preview"></div>
								<input type="hidden" class="ci-uploaded-id" name="<?php echo esc_attr( $this->get_field_name( 'member_image_id' ) . '[]' ); ?>" value="" />
								<input type="button" class="button ci-media-button" value="<?php esc_attr_e( 'Select Image', 'roxima' ); ?>" />
							</div>
						</label>

						<?php for ( $icon_no = 1; $icon_no <= 4; $icon_no ++ ): ?>
							<?php /* translators: #%1$d is the symbol # (that denotes a number in series) followed by a number, e.g. #1 */ ?>
							<label class="post-field-item"><?php echo wp_kses( sprintf( __( 'Social #%1$d Icon (e.g. <code>fa-facebook</code> <a href="%2$s" target="_blank">Reference</a>):', 'roxima' ), $icon_no, 'https://fortawesome.github.io/Font-Awesome/icons/#brand' ), array( 'code' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ); ?>
								<input type="text" name="<?php echo esc_attr( $this->get_field_name( "member_icon_{$icon_no}" ) . '[]' ); ?>" value="" class="widefat" />
							</label>
							<?php /* translators: #%1$d is the symbol # (that denotes a number in series) followed by a number, e.g. #1 */ ?>
							<label class="post-field-item"><?php echo sprintf( esc_html__( 'Social #%1$d URL:', 'roxima' ), $icon_no ); ?>
								<input type="text" name="<?php echo esc_attr( $this->get_field_name( "member_icon_{$icon_no}_url" ) . '[]' ); ?>" value="" class="widefat" />
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

		protected function sanitize_instance_members( $instance ) {
			if ( empty( $instance ) || ! is_array( $instance ) ) {
				return array();
			}

			$names       = $instance['member_name'];
			$roles       = $instance['member_role'];
			$texts       = $instance['member_text'];
			$image_ids   = $instance['member_image_id'];
			$icons_1     = $instance['member_icon_1'];
			$icons_2     = $instance['member_icon_2'];
			$icons_3     = $instance['member_icon_3'];
			$icons_4     = $instance['member_icon_4'];
			$icon_1_urls = $instance['member_icon_1_url'];
			$icon_2_urls = $instance['member_icon_2_url'];
			$icon_3_urls = $instance['member_icon_3_url'];
			$icon_4_urls = $instance['member_icon_4_url'];

			$count = max(
				count( $names ),
				count( $roles ),
				count( $texts ),
				count( $image_ids ),
				count( $icons_1 ),
				count( $icons_2 ),
				count( $icons_3 ),
				count( $icons_4 ),
				count( $icon_1_urls ),
				count( $icon_2_urls ),
				count( $icon_3_urls ),
				count( $icon_4_urls )
			);

			$new_fields = array();

			$records_count = 0;

			for ( $i = 0; $i < $count; $i++ ) {
				if ( empty( $names[ $i ] )
				     && empty( $roles[ $i ] )
				     && empty( $texts[ $i ] )
				     && empty( $image_ids[ $i ] )
				) {
					continue;
				}

				$new_fields[ $records_count ]['name']       = sanitize_text_field( $names[ $i ] );
				$new_fields[ $records_count ]['role']       = sanitize_text_field( $roles[ $i ] );
				$new_fields[ $records_count ]['text']       = wp_kses_post( $texts[ $i ] );
				$new_fields[ $records_count ]['image_id']   = roxima_sanitize_intval_or_empty( $image_ids[ $i ] );
				$new_fields[ $records_count ]['icon_1']     = sanitize_key( $icons_1[ $i ] );
				$new_fields[ $records_count ]['icon_1_url'] = esc_url_raw( $icon_1_urls[ $i ] );
				$new_fields[ $records_count ]['icon_2']     = sanitize_key( $icons_2[ $i ] );
				$new_fields[ $records_count ]['icon_2_url'] = esc_url_raw( $icon_2_urls[ $i ] );
				$new_fields[ $records_count ]['icon_3']     = sanitize_key( $icons_3[ $i ] );
				$new_fields[ $records_count ]['icon_3_url'] = esc_url_raw( $icon_3_urls[ $i ] );
				$new_fields[ $records_count ]['icon_4']     = sanitize_key( $icons_4[ $i ] );
				$new_fields[ $records_count ]['icon_4_url'] = esc_url_raw( $icon_4_urls[ $i ] );

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

	register_widget( 'CI_Widget_Team_CA' );
