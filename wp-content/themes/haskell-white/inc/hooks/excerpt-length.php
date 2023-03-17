<?php
/**
 * Customize the excerpt length.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Customize the excerpt length.
 *
 * @param int $length The custom length of the excerpt. Default = 20.
 *
 * @return int The length of the excerpt.
 */
function excerpt_length( $length ) {
	if ( ! empty( $length ) && is_numeric( $length ) ) {
		return $length;
	}

	return 20;
}

add_filter( 'excerpt_length', __NAMESPACE__ . '\excerpt_length', 999 );
