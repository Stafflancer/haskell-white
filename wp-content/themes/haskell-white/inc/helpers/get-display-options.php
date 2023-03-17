<?php
/**
 * Returns an array of ACF fields.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Returns an array of ACF fields.
 *
 * @param int $block_id (optional) ID of the post or of the block ($block[id]).
 */
function get_display_options( $block_id = false ) {
	if ( ! function_exists( 'get_field' ) ) :
		return '';
	endif;

	$block_id      = $block_id ? $block_id : get_the_ID();
	$display_options = [];

	if ( $block_id ) {
		$display_options = get_field( 'display_options', $block_id );
	}

	return $display_options;
}
