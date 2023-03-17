<?php
/**
 * Render block background options.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Render a module.
 *
 * @author BopDesign
 *
 * @param array  $background_options Array of Background Options.
 *
 * @return string|void
 */
function print_background_options( $background_options ) {
	if ( empty( $background_options ) ) {
		return '';
	}

	/**
	 * Setup background defaults.
	 */
	$background_defaults = [
		'class' => 'acf-block position-relative overflow-hidden',
	];

	$background_video_markup = $background_image_markup = $background_overlay_markup = $background_pattern_markup = '';

	// Only try to get the rest of the settings if the background type is set to anything.
	if ( $background_options['background_options']['background_type'] ) {

		if ( 'image' === $background_options['background_options']['background_type'] ) {

			$background_image_id   = '';
			$background_image_size = 'full-width';

			if ( $background_options['background_options']['background_image'] ) {
				$background_image_id = $background_options['background_options']['background_image']['ID'];
			}

			// Make sure images stay in their containers - relative + overflow hidden.
			$background_defaults['class'] .= ' has-background image-as-background  overflow-hidden';

			ob_start();
			$background_class = 'image-background d-block w-100 h-100 m-0 position-absolute top-0 bottom-0 start-0 end-0 object-center z-0';

			if ( $background_options['background_options']['has_parallax'] ):
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

		if ( 'video' === $background_options['background_options']['background_type'] && ! empty( $background_options['background_options']['background_oembed_video'] ) ) {
			$background_video = $background_options['background_options']['background_oembed_video'];
			// Use preg_match to find iframe src.
            preg_match('/src="(.+?)"/', $background_video, $matches);
            $src = $matches[1];
            // Add extra parameters to src and replace HTML.
            $params = array(
				'controls'  => 0,
				'muted'        => 1,
				'hd'        => 1,
				'autoplay' => 1,
				'loop' => 1,
            );
            $new_src = add_query_arg($params, $src);
            $iframe = str_replace($src, $new_src, $background_video);

            // Add extra attributes to iframe HTML.
            $attributes = 'frameborder="0"';
            $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

			// Make sure videos stay in their containers - relative + overflow hidden.
			$container_args['class'] .= ' has-background video-as-background  overflow-hidden';

			ob_start();
			?>
			<figure class="video-background d-block h-auto w-100 h-100 object-cover m-0 position-absolute top-0 bottom-0 start-0 end-0 object-top z-0">
				<?php echo $iframe; ?>
			</figure>
			<?php
			$background_video_markup = ob_get_clean();
		}

		if ( ( 'image' === $background_options['background_options']['background_type'] || 'video' === $background_options['background_options']['background_type'] ) ) {
			if( $background_options['background_options']['has_overlay'] == 1 && $background_options['background_options']['overlay_type'] == 'color'){
				$overlay_class = 'position-absolute z-1 has-background-dim';
				$overlay_color = $background_options['background_options']['overlay_color']['color_picker'];

				if ( '' !== $overlay_color ) {
					$overlay_class .= ' has-' . esc_attr( $overlay_color ) . '-background-color';
				}

				if ( ! empty( $background_options['background_options']['overlay_opacity'] ) && is_numeric( $background_options['overlay_opacity'] ) ) {
					$overlay_class .= ' has-background-dim-' . esc_attr( $background_options['background_options']['overlay_opacity'] );
				}

				ob_start();
				?>
				<span aria-hidden="true" class="<?php esc_attr_e( $overlay_class ); ?>"></span>
				<?php
				$background_overlay_markup = ob_get_clean();
			}
			if ( $background_options['background_options']['has_overlay'] == 1 && $background_options['background_options']['overlay_type'] == 'gradient') {
				$overlay_class = 'position-absolute z-1 has-background-dim';
				$gradient = $background_options['background_options']['gradient_color'];

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
		}
		if ( $background_options['background_options']['has_pattern'] == 1) {
			if ( $background_options['background_options']['pattern_image']){
				ob_start();
				$pattern_image = $background_options['background_options']['pattern_image']; 
				if(!empty($pattern_image) && !empty($pattern_image['url']))?>
				<figure class="has-pattern-show"><img src="<?php echo $pattern_image['url']; ?>"></figure><?php 
				$background_pattern_markup = ob_get_clean();
			} 
			
		}
	}

	// If we have a background image, echo our background image markup inside the block container.
	if ( $background_image_markup ) {
		echo $background_image_markup; // WPCS XSS OK.
	}

	// If we have a background video, echo our background video markup inside the block container.
	if ( $background_video_markup ) {
		echo $background_video_markup; // WPCS XSS OK.
	}

	// If we have an overlay, echo our overlay markup inside the block container.
	if ( $background_overlay_markup ) {
		echo $background_overlay_markup; // WPCS XSS OK.
	}

	// If we have a pattern image and enable pattern option, echo our pattern image markup.
	if ( $background_pattern_markup ) {
		echo $background_pattern_markup; // WPCS XSS OK.
	}
}
