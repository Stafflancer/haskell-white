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
 * @param array $fields Array of field names ie: [ 'layout', 'tagline, 'heading', 'content' ].
 * @param int   $block_id (optional) ID of the post or of the block ($block[id]).
 */
function get_acf_fields( $fields = [], $block_id = false ) {
	if ( ! function_exists( 'get_field' ) ) :
		return '';
	endif;

	$block_id      = $block_id ? $block_id : get_the_ID();
	$return_fields = [];

	foreach ( $fields as $field ) :
		$value                   = get_field( $field, $block_id );
		$return_fields[ $field ] = $value;
	endforeach;

	return $return_fields;
}
