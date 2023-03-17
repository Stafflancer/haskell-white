<?php
/**
 * Render an element.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Render an element.
 *
 * @param string $element_name The name of the element.
 * @param array  $args Args for the element.
 *
 * @return string|void
 */
function print_element( $element_name, $args = [] ) {
	if ( empty( $element_name ) ) {
		return '';
	}

	// Extract args.
	if ( ! empty( $args ) ) {
		extract( $args ); //phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- We can use it here since we know what to expect on the arguments.
	}

	require THEME_ROOT_PATH . 'elements/' . $element_name . '.php';
}
