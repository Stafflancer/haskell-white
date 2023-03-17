<?php
/**
 * Adds custom classes to apply to <main>.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Adds custom classes to apply to <main>.
 *
 * @param array $new_classes Classes for the <main> element.
 *
 * @return string main classes.
 */
function get_main_classes( $new_classes ) {
	$classes = [ 'site-main', 'site-content' ];

	if ( ! empty( $new_classes ) ) {
		$classes = array_merge( $classes, $new_classes );
	}

	$classes = apply_filters( 'hwcpa_main_classes', $classes );

	return implode( ' ', $classes );
}
