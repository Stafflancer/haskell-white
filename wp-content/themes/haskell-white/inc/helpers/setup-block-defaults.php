<?php
/**
 * Returns arrays of defaults for a block.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Returns arrays of Block defaults.
 *
 * @param array $block_args     Array of arguments from the print_block() function.
 * @param array $block_defaults Array of defaults from the block.
 * @param array $block          Array containing the block's values.
 */
function setup_block_defaults( $block_args, $block_defaults, $block = null ) {
	if ( empty( $block_args ) ) {
		$block_args = [];
	}

	// Get block ACF settings.
	$background_options = get_background_options( $block['id'] );
	$display_options    = get_display_options( $block['id'] );

	// Parse the $block_args if we're rendering this with print_block() from a theme.
	if ( ! empty( $block_args ) ) :
		$block_defaults = get_formatted_args( $block_args, $block_defaults );
	endif;

	// Get custom classes for the block and/or for block colors, alignment, spacing.
	$block_classes = isset( $block ) ? get_block_classes( $block, $background_options, $display_options ) : [];

	// Get custom classes for the container: align_content, container_size.
	$block_settings['settings'] = isset( $block ) && ! empty ( $display_options ) ? get_module_settings( $block, $display_options ) : [];

	if ( ! empty( $block_classes ) ) :
		$block_defaults['class'] = array_merge( $block_defaults['class'], $block_classes );
	endif;

	// Set up block attributes.
	$block_atts = get_formatted_atts( [ 'class', 'id' ], $block_defaults );

	return [ $block_defaults, $block_atts, $block_settings, $background_options ];
}
