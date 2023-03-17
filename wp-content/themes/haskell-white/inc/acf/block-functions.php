<?php
/**
 * Associate the possible block options with the appropriate section.
 *
 * @param array $block
 * @param array $container_args
 * @param array $content_args
 * @param bool  $module
 *
 * @return void
 */
function hwcpa_display_block_background_options( array $block, array $container_args = [], array $content_args = [], bool $module = false ) {
//	if ( empty( $id ) ) {
//		$post_id = is_home() ? get_option( 'page_for_posts', true ) : get_the_ID();
//	}

	/**
	 * Get block/module background options.
	 */
	if ( $module ) {
		// Module background options, if used in an ACF group block.
		$background_options = get_sub_field( 'background_options' );
	} else {
		// ACF block background options.
		$background_options = get_field( 'background_options' );
	}

	/**
	 * Get block/module settings.
	 */
	$container_attributes = get_template_acf_block_settings( $block, $module );

	/**
	 * Setup block/module container defaults.
	 */
	$container_defaults = [
		'container'       => 'section',
		'id'              => '',
		'class'           => 'acf-block position-relative overflow-hidden',
		'background_type' => $background_options['background_type'],
		'margin'          => $container_attributes['container_margin'], // Used to add space outside the block using margin: my-5 or mt-4 mb-3.
		'padding'         => $container_attributes['container_padding'], // Used to add space inside the block using padding: py-5 or pt-4 pb-3.
	];

	/**
	 * Parse block/module container args.
	 */
	$container_args = wp_parse_args( $container_args, $container_defaults );

	// Align class must be applied to the main wrapper to work properly.
	$container_args['class'] .= ' ' . $container_attributes['align'];

	// Full height class must be applied to the main wrapper to use matrix layout properly.
	if ( ! empty( $container_attributes['full_height'] ) ) {
		$container_args['class'] .= ' ' . $container_attributes['full_height'];
	}

	/**
	 * Add margin classes to the block/module main container.
	 * Overwrite margin classes by passing $container_args['margin'] in individual block's PHP.
	 */
	$container_args['class'] .= ' ' . $container_args['margin'];

	/**
	 * Add padding classes to the block/module main container.
	 * Overwrite margin classes by passing $container_args['margin'] in individual block's PHP.
	 */
	$container_args['class'] .= ' ' . $container_args['padding'];

	/**
	 * Setup content defaults: align_text, align_content, content_size.
	 */
	$content_defaults = [
		'class' => 'position-relative d-flex h-100 z-10',
	];

	// Parse block/module content args.
	$content_args = wp_parse_args( $content_args, $content_defaults );

	/**
	 * Join content classes for the block/module.
	 * Create class attribute allowing for custom 'container_size', 'align_text' and 'align_content' values.
	 */
	$content_class_name = join( ' ', [
		$content_args['class'],
		$container_attributes['align_text'],
		$container_attributes['align_content'],
		$container_attributes['container_size'],
	] );

	$background_video_markup = $background_image_markup = $background_overlay_markup = '';

	// Only try to get the rest of the settings if the background type is set to anything.
	if ( $container_args['background_type'] ) {
		if ( 'none' === $container_args['background_type'] ) {
			$container_args['class'] .= ' no-background';
		}

		if ( 'color' === $container_args['background_type'] && $background_options['background_color']['color_picker'] ) {
			$background_color        = $background_options['background_color']['color_picker'];
			$container_args['class'] .= ' has-background color-as-background has-' . esc_attr( $background_color ) . '-background-color';
		}

		if ( 'image' === $container_args['background_type'] ) {
			$background_image_id   = '';
			$background_image_size = 'full-width';

			if ( $background_options['use_featured_image'] && has_post_thumbnail() ) {
				$background_image_id = get_post_thumbnail_id();
			} elseif ( $background_options['background_image'] ) {
				$background_image_id = $background_options['background_image']['ID'];
			}

			// Make sure images stay in their containers - relative + overflow hidden.
			$container_args['class'] .= ' has-background image-as-background position-relative overflow-hidden';

			ob_start();
			$background_class = 'image-background d-block w-100 h-auto m-0 position-absolute top-0 bottom-0 start-0 end-0 object-center z-0';

			if ( $background_options['has_parallax'] ):
				$background_class .= ' bg-fixed bg-center bg-cover';
				$background_image_url = wp_get_attachment_image_url( $background_image_id, $background_image_size );
				?>
				<figure class="<?php echo esc_attr( $background_class ); ?>"
				        style="background-image:url(<?php echo $background_image_url; ?>);" aria-hidden="true"></figure>
			<?php else:
				?>
				<figure class="<?php echo esc_attr( $background_class ); ?>" aria-hidden="true">
					<?php echo wp_get_attachment_image( $background_image_id, $background_image_size, false, array( 'class' => 'w-100 h-100 object-cover' ) ); ?>
				</figure>
			<?php endif; ?>
			<?php
			$background_image_markup = ob_get_clean();
		}

		if ( 'video' === $container_args['background_type'] && ! empty( $background_options['background_video_mp4'] ) ) {
			$background_video = $background_options['background_video_mp4'];
			// Make sure videos stay in their containers - relative + overflow hidden.
			$container_args['class'] .= ' has-background video-as-background position-relative overflow-hidden';

			ob_start();
			?>
			<figure class="video-background d-block h-auto w-100 m-0 position-absolute top-0 bottom-0 start-0 end-0 object-top z-0">
				<video id="<?php echo esc_attr( $container_args['id'] ); ?>-video" autoplay muted playsinline loop
				       preload="none">
					<?php if ( $background_video['url'] ) : ?>
						<source src="<?php echo esc_url( $background_video['url'] ); ?>" type="video/mp4">
					<?php endif; ?>
				</video>
			</figure>
			<?php
			$background_video_markup = ob_get_clean();
		}

		if ( 'image' === $container_args['background_type'] || 'video' === $container_args['background_type'] ) {
			if ( $background_options['has_overlay']  == 1 && $background_options['overlay_type'] == 'color') {
				$overlay_class = 'position-absolute z-1 has-background-dim';
				$overlay_color = $background_options['overlay_color']['color_picker'];

				if ( '' !== $overlay_color ) {
					$overlay_class .= ' has-' . esc_attr( $overlay_color ) . '-background-color';
				}

				if ( ! empty( $background_options['overlay_opacity'] ) && is_numeric( $background_options['overlay_opacity'] ) ) {
					$overlay_class .= ' has-background-dim-' . esc_attr( $background_options['overlay_opacity'] );
				}

				ob_start();
				?>
				<span aria-hidden="true" class="<?php esc_attr_e( $overlay_class ); ?>"></span>
				<?php
				$background_overlay_markup = ob_get_clean();
			}

			if ( $background_options['has_overlay'] == 1 && $background_options['overlay_type'] == 'gradient') {
				$overlay_class = 'position-absolute z-1 has-background-dim';
				$gradient = $background_options['gradient_color'];

				if ( '' !== $gradient ) {
					$overlay_class .= ' has-' . esc_attr( $gradient ) . '-gradient-color';
				}

				if ( ! empty( $background_options['overlay_opacity'] ) && is_numeric( $background_options['overlay_opacity'] ) ) {
					$overlay_class .= ' has-background-dim-' . esc_attr( $background_options['overlay_opacity'] );
				}

				ob_start();
				?>
				<span aria-hidden="true" class="w-100 h-100 m-0 <?php esc_attr_e( $overlay_class ); ?>"></span>
				<?php
				$background_overlay_markup = ob_get_clean();
			}


			if ( $background_options['has_parallax'] ) {
				$container_args['class'] .= ' has-parallax';
			}
		}
	}

	// Print our block container with options.
	printf( '<%s id="%s" class="%s">', esc_html( $container_args['container'] ), esc_attr( $container_args['id'] ), esc_attr( $container_args['class'] ) );

	// If we have an overlay, echo our overlay markup inside the block container.
	if ( $background_overlay_markup ) {
		echo $background_overlay_markup; // WPCS XSS OK.
	}

	// If we have a background video, echo our background video markup inside the block container.
	if ( $background_video_markup ) {
		echo $background_video_markup; // WPCS XSS OK.
	}

	// If we have a background image, echo our background image markup inside the block container.
	if ( $background_image_markup ) {
		echo $background_image_markup; // WPCS XSS OK.
	}

	/**
	 * Print our block/module content container:
	 * align_text - text-(left | center | right) classes.
	 * align_content - justify-content-* and align-items-* classes.
	 * container_size - container or container-fluid class.
	 */
	printf( '<div class="inner-container %s">', esc_attr( $content_class_name ) );

	/**
	 * Print our block/module content with width classes:
	 * column_size - col-* classes
	 */
	printf( '<div class="%s">', esc_attr( $container_attributes['column_size'] . ' ' . $container_attributes['animation'] ) );
}

function hwcpa_close_block( string $container ) {
	if ( ! empty( $container ) && is_string( $container ) ) {
		printf( '</div>' ); // Close content with .col-* classes.
		printf( '</div>' ); // Close layout .inner-container.
		printf( '</%s>', esc_html( $container ) ); // Close main container.
	}
}

/**
 * Associate the block settings with the appropriate section.
 *
 * @param array $block Block settings.
 *
 * @return array $block_settings
 */
function get_template_acf_block_settings( $block, $module ) {
	// Bail if the $block is not provided.
	if ( empty( $block ) ) {
		return [];
	}

	// Setup defaults.
	$defaults = [
		'full_height'       => '',
		'align'             => 'alignfull',
		'align_text'        => 'text-start text-left',
		'align_content'     => 'align-items-start justify-content-start',
		'container_size'    => 'container',
		'container_margin'  => 'my-0',
		'container_padding' => 'py-3 py-md-4 py-lg-5',
		'column_size'     => 'col-12',
		'animation'         => '',
	];

	$block_settings = [];

	if ( ! empty( $block['full_height'] ) && $block['full_height'] ) {
		$block_settings['full_height'] = 'vh-100';
	}

	if ( ! empty( $block['align'] ) ) {
		$block_settings['align'] = 'align' . esc_attr( $block['align'] );
	} elseif ( empty( $block['align'] ) || '' === $block['align'] ) {
		$block_settings['align'] = 'alignnone';
	}

	if ( ! empty( $block['align_text'] ) ) {
		switch ( $block['align_text'] ) {
			case 'center':
				$block_settings['align_text'] = 'text-center';
				break;
			case 'right':
				$block_settings['align_text'] = 'text-right text-end';
				break;
			case 'left':
			default:
				$block_settings['align_text'] = 'text-left text-start';
				break;
		}
	}

	if ( ! empty( $block['align_content'] ) ) {
		switch ( $block['align_content'] ) {
			case 'top':
				$block_settings['align_content'] = 'self-start is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'center':
				$block_settings['align_content'] = 'self-center is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'bottom':
				$block_settings['align_content'] = 'self-end is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'top left':
				$block_settings['align_content'] = 'align-items-start justify-content-start is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'top center':
				$block_settings['align_content'] = 'align-items-start justify-content-center is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'top right':
				$block_settings['align_content'] = 'align-items-start justify-content-end is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'center left':
				$block_settings['align_content'] = 'align-items-center justify-content-start is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'center center':
				$block_settings['align_content'] = 'align-items-center justify-content-center is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'center right':
				$block_settings['align_content'] = 'align-items-center justify-content-end is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'bottom left':
				$block_settings['align_content'] = 'align-items-end justify-content-start is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'bottom center':
				$block_settings['align_content'] = 'align-items-end justify-content-center is-position-' . sanitize_title( $block['align_content'] );
				break;
			case 'bottom right':
				$block_settings['align_content'] = 'align-items-end justify-content-end is-position-' . sanitize_title( $block['align_content'] );
				break;
			default:
				$block_settings['align_content'] = 'align-items-start justify-content-start is-position-' . sanitize_title( $block['align_content'] );
				break;
		}
	}

	// Get block display options.
	if ( $module ) {
		$display_options = get_sub_field( 'display_options' );
	} else {
		$display_options = get_field( 'display_options' )['display_options'];
	}

	// Get animation class.
	if ( isset( $display_options['animation'] ) && ! empty( $display_options['animation'] ) ) {
		$block_settings['animation'] = hwcpa_get_animation_class( $display_options['animation'] );
	}

	// Set the container width.
	if ( isset( $display_options['container_size'] ) && ! empty( $display_options['container_size'] ) ) {
		$block_settings['container_size'] = esc_attr( $display_options['container_size'] );
	}

	// Set content width.
	if ( isset( $display_options['column_size'] ) && ! empty( $display_options['column_size'] ) ) {
		switch ( $display_options['column_size'] ) {
			case 'auto':
				$block_settings['column_size'] = 'col-12 col-sm-auto';
				break;
			case '4':
				$block_settings['column_size'] = 'col-12 col-md-4';
				break;
			case '5':
				$block_settings['column_size'] = 'col-12 col-md-5';
				break;
			case '6':
				$block_settings['column_size'] = 'col-12 col-md-6';
				break;
			case '7':
				$block_settings['column_size'] = 'col-12 col-md-7';
				break;
			case '8':
				$block_settings['column_size'] = 'col-12 col-md-8';
				break;
			case '9':
				$block_settings['column_size'] = 'col-12 col-md-9';
				break;
			case '10':
				$block_settings['column_size'] = 'col-12 col-md-10';
				break;
			case '11':
				$block_settings['column_size'] = 'col-12 col-md-11';
				break;
			case 'full':
			default:
				$block_settings['column_size'] = 'col-12';
				break;
		}
	}

	// Set top/bottom padding for the block.
	if ( isset( $display_options['padding_top'] ) && ! empty( $display_options['padding_top'] ) ) {
		switch ( $display_options['padding_top'] ) {
			case 'small':
				$block_settings['container_padding'] = 'padding-top-small';
				break;
			case 'medium':
				$block_settings['container_padding'] = 'padding-top-medium';
				break;
			case 'large':
				$block_settings['container_padding'] = 'padding-top-large';
				break;
			case 'none':
			default:
				$block_settings['container_padding'] = 'pt-0';
				break;
		}
	}

	if ( isset( $display_options['padding_bottom'] ) && ! empty( $display_options['padding_bottom'] ) ) {
		switch ( $display_options['padding_bottom'] ) {
			case 'small':
				$block_settings['container_padding'] .= ' padding-bottom-small';
				break;
			case 'medium':
				$block_settings['container_padding'] .= ' padding-bottom-medium';
				break;
			case 'large':
				$block_settings['container_padding'] .= ' padding-bottom-large';
				break;
			case 'none':
			default:
				$block_settings['container_padding'] .= ' pb-0';
				break;
		}
	}

	// Set top/bottom margin for the block.
	if ( isset( $display_options['margin_top'] ) && ! empty( $display_options['margin_top'] ) ) {
		switch ( $display_options['margin_top'] ) {
			case 'small':
				$block_settings['container_margin'] = 'margin-top-small';
				break;
			case 'medium':
				$block_settings['container_margin'] = 'margin-top-medium';
				break;
			case 'large':
				$block_settings['container_margin'] = 'margin-top-large';
				break;
			case 'none':
			default:
				$block_settings['container_margin'] = 'mt-0';
				break;
		}
	}

	if ( isset( $display_options['margin_bottom'] ) && ! empty( $display_options['margin_bottom'] ) ) {
		switch ( $display_options['margin_bottom'] ) {
			case 'small':
				$block_settings['container_margin'] .= ' margin-bottom-small';
				break;
			case 'medium':
				$block_settings['container_margin'] .= ' margin-bottom-medium';
				break;
			case 'large':
				$block_settings['container_margin'] .= ' margin-bottom-large';
				break;
			case 'none':
			default:
				$block_settings['container_margin'] .= ' mb-0';
				break;
		}
	}

	// Parse args.
	$block_settings = wp_parse_args( $block_settings, $defaults );

	return $block_settings;
}

/**
 *
 * Get the animate.css classes for an element.
 *
 * @param string $animation
 *
 * @return string $classes Animate.css classes for our element.
 */
function hwcpa_get_animation_class( string $animation ) {
	if ( empty( $animation ) ) {
		// Get block other options for our animation data.
		$animation = get_field( 'display_options' )['display_options']['animation'];
	}

	// Get out of here if we don't have other options.
	if ( empty( $animation ) ) {
		return '';
	}

	// Set up our animation class for the wrapping element.
	$classes = '';

	// If we have an animation set...
	if ( 'none' !== $animation ) {
		$classes = ' wow animate__animated animate__' . $animation;
	}

	return esc_attr( $classes );
}
