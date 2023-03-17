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
function get_background_options( $block_id = false ) {
	if ( ! function_exists( 'get_field' ) ) :
		return '';
	endif;

	$block_id      = $block_id ? $block_id : get_the_ID();
	$background_options = [];

	if ( $block_id ) {
		$background_options = get_field( 'background_options', $block_id );
	}

	return $background_options;
}
