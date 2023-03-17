<?php
/**
 * Merges the element defaults with the passed args.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Merges the element defaults with the passed args.
 *
 * @param array $args     Array of settings passed to the element.
 * @param array $defaults Array of default settings for the element.
 */
function get_formatted_args( $args, $defaults ) {
	if ( empty( $args ) ) {
		$args = [];
	}

	// Set the 'class' array key if it doesn't exist.
	$args['class'] = array_key_exists( 'class', $args ) ? $args['class'] : [];

	// Allow class to be passed as a string or an array.
	$args['class'] = ( $args['class'] && ! is_array( $args['class'] ) ) ? explode( ' ', $args['class'] ) : $args['class'];

	// Construct button args.
	if ( array_key_exists( 'button_link', $args ) ) {
		// Map the ACF link 'title' attribute to an 'button_link' -> 'title' attribute.
		if ( ! empty( $args['button_link']['title'] ) ) {
			$args['title'] = $args['button_link']['title'];
		}

		// Map the ACF link 'url' attribute to an 'href' attribute.
		if ( ! empty( $args['button_link']['url'] ) ) {
			$args['href'] = $args['button_link']['url'];
		}

		if ( ! empty( $args['button_link']['target'] ) ) {
			$args['target'] = $args['button_link']['target'];
		}

		if ( ! empty( $args['button_style'] ) ) {
			$args['style'] = $args['button_style'];
		}

		if ( ! empty( $args['button_color']['color_picker'] ) && 'fill' === $args['button_style'] ) {
			$args['class'] = array_merge( $args['class'], [ 'has-background', 'has-' . esc_attr( $args['button_color']['color_picker'] ) . '-background-color' ] );
		}

		if ( ! empty( $args['button_text_color']['color_picker'] ) ) {
			$args['class'] = array_merge( $args['class'], [ 'has-text-color', 'has-' . esc_attr( $args['button_text_color']['color_picker'] ) . '-color' ] );
		}
	}

	if ( ! empty( $args['url'] ) ) {
		// Map the ACF link 'url' attribute to an 'href' attribute.
		$args['href'] = $args['url'];
	}

	// Merge with defaults.
	$classes = is_array( $args['class'] ) ? array_merge( $defaults['class'], $args['class'] ) : $defaults['class'];

	$formatted_args = wp_parse_args( $args, $defaults );

	$formatted_args['class'] = $classes;

	return $formatted_args;
}
