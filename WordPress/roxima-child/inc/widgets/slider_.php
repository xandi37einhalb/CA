<?php
	class CI_Widget_Slider_CA extends WP_Widget {

		protected $defaults = array(
			'slideshow'       => 1,
			'slideshow_speed' => 3000,
			'slides'          => array(),
			'text_color'      => '',
			'overlay_color'   => '',
		);

		function __construct() {
			$widget_ops  = array( 'description' => esc_html__( 'Image and text slideshow.', 'roxima' ) );
			$control_ops = array();
			parent::__construct( 'ci-slideshow', $name = esc_html__( 'Theme - Slider', 'roxima' ), $widget_ops, $control_ops );

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
		// This is needed for break-word ruth zemmer
		function textWrap($text) {
		    $new_text = '';
		    $text_1 = explode('>',$text);
		    $sizeof = sizeof($text_1);
		    for ($i=0; $i<$sizeof; ++$i) {
		        $text_2 = explode('<',$text_1[$i]);
		        if (!empty($text_2[0])) {
		            $new_text .= preg_replace('#([^\n\r .]{10})#i', '\\1  ', $text_2[0]);
		        }
		        if (!empty($text_2[1])) {
		            $new_text .= '<' . $text_2[1] . '>';
		        }
		    }
		    return $new_text;
		}

		function widget( $args, $instance ) {
			$instance = wp_parse_args( (array) $instance, $this->defaults );
			$before_widget = $args['before_widget'];
			$after_widget  = $args['after_widget'];

			$slideshow       = $instance['slideshow'] == 1 ? 1 : 0;
			$slideshow_speed = $instance['slideshow_speed'];
			$slides          = $instance['slides'];

			$text_color    = $instance['text_color'];
			$text_style    = ! empty( $text_color ) ? sprintf( 'color: %s;', $text_color ) : '';
			$overlay_color = $instance['overlay_color'];
			$overlay_style = ! empty( $overlay_color ) ? sprintf( 'background-color: %s;', $overlay_color ) : '';

			echo $before_widget;
			?>
			<div class="widget-wrap" style="<?php echo esc_attr( $text_style ); ?>">
				<div class="main-slider ci-slider loading"
					data-slideshow="<?php echo esc_attr( $slideshow ); ?>"
					data-slideshowspeed="<?php echo esc_attr( $slideshow_speed ); ?>"
					data-animation="fade"
					data-animationspeed="600">

					<ul class="slides">
						<?php foreach( $slides as $slide ): ?>
							<?php
								/** Mod L. Kamber, lukas@cycling-adventures.org **/
								$titles = array();
								if ( ! empty( $slide['title'] ) ) {
									$titles = explode("|", $slide['title']);
								}
								/** Ende Mod L. Kamber, lukas@cycling-adventures.org **/
								$image_url        = roxima_get_image_src( $slide['image_id'], 'roxima_slider' );
								$background_style = ! empty( $image_url ) ? sprintf( 'background-image: url(%s);', esc_url( $image_url ) ) : '';
							?>
							<li style="<?php echo esc_attr( $background_style ); ?>">
								<div class="widget-overlay" style="<?php echo esc_attr( $overlay_style ); ?>"></div>

								<div class="hero-content text-center">
									<div class="container">
										<div class="row">
											<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
												<?php /** Mod L. Kamber, lukas@cycling-adventures.org **/ ?>
												<?php $n = 1; ?>
												<?php foreach ($titles as $title): ?>
													<?php if ( ! empty( $title ) ): ?>
														<p class="section-title section-title-<?php echo $n++; ?>"><?php echo esc_html( $title ); ?></p>
													<?php endif; ?>
												<?php /** Ende Mod L. Kamber, lukas@cycling-adventures.org **/ ?>
												<?php endforeach; ?>
												<?php if ( ! empty( $slide['subtitle'] ) ): ?>

													<p class="section-subtitle"><?php echo str_replace(" | ","<br />", esc_html( $slide['subtitle'] )); ?></p>
												<?php endif; ?>

												<?php if ( ! empty( $slide['button_text'] ) && ! empty( $slide['button_url'] ) ): ?>
													<a href="<?php echo esc_url( $slide['button_url'] ); ?>" class="btn"><?php echo esc_html( $slide['button_text'] ); ?></a>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<?php

			echo $after_widget;
		} // widget

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['slideshow']       = roxima_sanitize_checkbox_ref( $new_instance['slideshow'] );
			$instance['slideshow_speed'] = absint( $new_instance['slideshow_speed'] );
			$instance['slides']          = $this->sanitize_instance_slides( $new_instance );

			$instance['text_color']    = roxima_sanitize_hex_color( $new_instance['text_color'] );
			$instance['overlay_color'] = roxima_sanitize_rgba_color( $new_instance['overlay_color'] );

			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, $this->defaults );

			$slideshow       = $instance['slideshow'];
			$slideshow_speed = $instance['slideshow_speed'];
			$slides          = $instance['slides'];

			$text_color    = $instance['text_color'];
			$overlay_color = $instance['overlay_color'];

			$slide_image_name    = $this->get_field_name( 'slide_image' ) . '[]';
			$slide_title_name    = $this->get_field_name( 'slide_title' ) . '[]';
			$slide_subtitle_name = $this->get_field_name( 'slide_subtitle' ) . '[]';
			$slide_btn_text_name = $this->get_field_name( 'slide_button_text' ) . '[]';
			$slide_btn_link_name = $this->get_field_name( 'slide_button_url' ) . '[]';
			?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'slideshow' ) ); ?>"><input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'slideshow' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'slideshow' ) ); ?>" value="1" <?php checked( $slideshow, 1 ); ?> /><?php esc_html_e( 'Enable slideshow (auto-play).', 'roxima' ); ?></label></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'slideshow_speed' ) ); ?>"><?php esc_html_e( 'Pause between slides (milliseconds):', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'slideshow_speed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slideshow_speed' ) ); ?>" type="number" min="0" step="100" value="<?php echo esc_attr( $slideshow_speed ); ?>" class="widefat" /></p>

			<p><?php esc_html_e( 'Add as many items as you want by pressing the "Add Item" button. Remove any item by selecting "Remove me".', 'roxima' ); ?></p>
			<fieldset class="ci-repeating-fields">
				<div class="inner">
					<?php
						if ( ! empty( $slides ) ) {
							$count = count( $slides );
							for( $i = 0; $i < $count; $i++ ) {
								?>
								<div class="post-field">
									<label class="post-field-item"><?php esc_html_e( 'Slide Image:', 'roxima' ); ?>
										<div class="ci-upload-preview">
											<div class="upload-preview">
												<?php if ( ! empty( $slides[$i]['image_id'] ) ): ?>
													<?php
														$image_url = roxima_get_image_src( $slides[$i]['image_id'], 'roxima_featgal_small_thumb' );
														echo sprintf( '<img src="%s" /><a href="#" class="close media-modal-icon" title="%s"></a>',
															esc_url( $image_url ),
															esc_attr__( 'Remove image', 'roxima' )
														);
													?>
												<?php endif; ?>
											</div>
											<input type="hidden" class="ci-uploaded-id" name="<?php echo esc_attr( $slide_image_name ); ?>" value="<?php echo esc_attr( $slides[$i]['image_id'] ); ?>" />
											<input type="button" class="button ci-media-button" value="<?php esc_attr_e( 'Select Image', 'roxima' ); ?>" />
										</div>
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Title:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $slide_title_name ); ?>" value="<?php echo esc_attr( $slides[$i]['title'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Subtitle:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $slide_subtitle_name ); ?>" value="<?php echo esc_attr( $slides[$i]['subtitle'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Button text:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $slide_btn_text_name ); ?>" value="<?php echo esc_attr( $slides[$i]['button_text'] ); ?>" class="widefat" />
									</label>

									<label class="post-field-item"><?php esc_html_e( 'Button URL:', 'roxima' ); ?>
										<input type="text" name="<?php echo esc_attr( $slide_btn_link_name ); ?>" value="<?php echo esc_url( $slides[$i]['button_url'] ); ?>" class="widefat" />
									</label>
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
						<label class="post-field-item"><?php esc_html_e( 'Slide Image:', 'roxima' ); ?>
							<div class="ci-upload-preview">
								<div class="upload-preview"></div>
								<input type="hidden" class="ci-uploaded-id" name="<?php echo esc_attr( $slide_image_name ); ?>" value="" />
								<input type="button" class="button ci-media-button" value="<?php esc_attr_e( 'Select Image', 'roxima' ); ?>" />
							</div>
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Title:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $slide_title_name ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Subtitle:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $slide_subtitle_name ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Button text:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $slide_btn_text_name ); ?>" value="" class="widefat" />
						</label>

						<label class="post-field-item"><?php esc_html_e( 'Button URL:', 'roxima' ); ?>
							<input type="text" name="<?php echo esc_attr( $slide_btn_link_name ); ?>" value="" class="widefat" />
						</label>
						<p class="ci-repeating-remove-action"><a href="#" class="button ci-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php esc_html_e( 'Remove me', 'roxima' ); ?></a></p>
					</div>
				</div>
				<a href="#" class="ci-repeating-add-field button"><i class="dashicons dashicons-plus-alt"></i><?php esc_html_e( 'Add Item', 'roxima' ); ?></a>
			</fieldset>

			<fieldset class="ci-collapsible">
				<legend><?php esc_html_e( 'Customize', 'roxima' ); ?> <i class="dashicons dashicons-arrow-down"></i></legend>
				<div class="elements">
					<p><label for="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>"><?php esc_html_e( 'Text Color:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text_color' ) ); ?>" type="text" value="<?php echo esc_attr( $text_color ); ?>" class="colorpckr widefat"/></p>
					<p><label for="<?php echo esc_attr( $this->get_field_id( 'overlay_color' ) ); ?>"><?php esc_html_e( 'Overlay Color:', 'roxima' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'overlay_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'overlay_color' ) ); ?>" type="text" value="<?php echo esc_attr( $overlay_color ); ?>" class="widefat alpha-color-picker" /></p>
				</div>
			</fieldset>

			<?php
		} // form

		protected function sanitize_instance_slides( $instance ) {
			if ( empty( $instance ) || ! is_array( $instance ) ) {
				return array();
			}

			$images    = $instance['slide_image'];
			$titles    = $instance['slide_title'];
			$subtitles = $instance['slide_subtitle'];
			$btn_texts = $instance['slide_button_text'];
			$btn_urls  = $instance['slide_button_url'];

			$count = max(
				count( $images ),
				count( $titles ),
				count( $subtitles ),
				count( $btn_texts ),
				count( $btn_urls )
			);

			$new_fields = array();

			$records_count = 0;

			for ( $i = 0; $i < $count; $i++ ) {
				if ( empty( $images[ $i ] )
				     && empty( $titles[ $i ] )
				     && empty( $subtitles[ $i ] )
				     && empty( $btn_texts[ $i ] )
				     && empty( $btn_urls[ $i ] )
				) {
					continue;
				}

				$new_fields[ $records_count ]['image_id']    = roxima_sanitize_intval_or_empty( $images[ $i ] );
				$new_fields[ $records_count ]['title']       = sanitize_text_field( $titles[ $i ] );
				$new_fields[ $records_count ]['subtitle']    = sanitize_text_field( $subtitles[ $i ] );
				$new_fields[ $records_count ]['button_text'] = sanitize_text_field( $btn_texts[ $i ] );
				$new_fields[ $records_count ]['button_url']  = esc_url_raw( $btn_urls[ $i ] );

				$records_count++;
			}
			return $new_fields;
		}

	} // class

	register_widget( 'CI_Widget_Slider_CA' );
