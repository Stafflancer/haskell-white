<?php
/**
 * Returns an array of classes from a block's Gutenberg fields.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Returns an updated array of classes.
 *
 * @param array $block Array of block attributes.
 *
 * return array The updated array of classes.
 */
function get_block_classes( $block, $background_options, $display_options ) {
	// Setup defaults.
	$block_defaults = [
		'full_height'    => false,
		'align'          => 'full',
		'margin_top'     => 'none',
		'margin_bottom'  => 'none',
		'padding_top'    => 'medium',
		'padding_bottom' => 'medium',
	];
	$block_attrs    = [];
	$block_classes = [];

	// Get block background options.
	if ( empty( $background_options ) ) {
		$background_options = get_background_options( $block['id'] );
	}

	// Get block display options.
	if ( empty( $display_options ) ) {
		$display_options = get_display_options( $block['id'] );
	}

	if ( ! empty( $block['className'] ) ) :
		$block_classes[] = $block['className'];
	endif;

	if ( ! empty( $block['backgroundColor'] ) ) {
		$block_classes[] = 'has-background';
		$block_classes[] = 'has-' . $block['backgroundColor'] . '-background-color';
	}

	if ( ! empty( $block['textColor'] ) ) {
		$block_classes[] = 'has-text-color';
		$block_classes[] = 'has-' . $block['textColor'] . '-color';
	}

	if ( ! empty( $background_options ) ) {
		if ( ! empty( $background_options['background_type'] ) ) {
			switch ( $background_options['background_type'] ) {
				case 'color':
					$block_classes[]  = 'has-background';
					$block_classes[]  = 'color-as-background';

					if ( $background_options['background_color']['color_picker'] ) {
						$background_color = $background_options['background_color']['color_picker'];
						$block_classes[]  = 'has-' . esc_attr( $background_color ) . '-background-color';
					}
					break;
				case 'image':
				case 'video':
					$block_classes[] = 'has-background';
					$block_classes[] = $background_options['background_type'] . '-as-background';

					if ( ! empty( $background_options['has_parallax'] ) && $background_options['has_parallax'] ) {
						$block_classes[] = 'has-parallax';
					}
					break;
				case 'none':
				default:
					$block_classes[] = 'no-background';
			}
		}
	}

	if ( ! empty( $block['full_height'] ) && $block['full_height'] ) {
		$block_attrs['full_height'] = $block['full_height'];
	}

	if ( ! empty( $block['align'] ) ) {
		$block_attrs['align'] = $block['align'];
	} elseif ( empty( $block['align'] ) || '' === $block['align'] ) {
		$block_attrs['align'] = 'none';
	}

	if ( ! empty( $display_options ) ) {
		// Set top/bottom margin for the block.
		if ( ! empty( $display_options['margin_top'] ) ) {
			$block_attrs['margin_top'] = $display_options['margin_top'];
		}

		if ( ! empty( $display_options['margin_bottom'] ) ) {
			$block_attrs['margin_bottom'] = $display_options['margin_bottom'];
		}

		// Set top/bottom padding for the block.
		if ( ! empty( $display_options['padding_top'] ) ) {
			$block_attrs['padding_top'] = $display_options['padding_top'];
		}

		if ( ! empty( $display_options['padding_bottom'] ) ) {
			$block_attrs['padding_bottom'] = $display_options['padding_bottom'];
		}
	}

	$block_attrs = wp_parse_args( $block_attrs, $block_defaults );

	foreach ( $block_attrs as $attr => $value ) {
		$block_classes[] = get_class_name_by_attribute( $attr, $value );
	}

	return $block_classes;
}
