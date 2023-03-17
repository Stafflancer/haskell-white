<?php
/**
 * Returns an array of module attributes from a block's Gutenberg fields and Display Options.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Returns a string of formatted container attributes.
 *
 * @param array $block           Array of block attributes.
 * @param array $display_options Array of Display Options attributes.
 *
 * @return array The array of classes.
 */
function get_module_settings( $block, $display_options ) {
	// Bail early, if the block is not provided.
	if ( empty( $block ) ) {
		return [];
	}

	// Get block display options.
	if ( empty( $display_options ) ) {
		$display_options = get_display_options();
	}
	

	// Setup defaults.
	$module_defaults = [
		'container_size'     => 'container', // container/container-fluid
		'content_width'  => '100',
		'heading_color'  => 'base',  
		'text_color'      => 'base',  
	];
	$module_attributes    = [];

	if ( ! empty( $display_options['display_options']['container_size'] ) ) {
		$module_attributes['container_size'] = $display_options['display_options']['container_size'];
	}

	if ( ! empty( $display_options['display_options']['content_width'] ) ) {
		$module_attributes['content_width'] = $display_options['display_options']['content_width'];
	}

	// Set the heading color
	if ( isset( $display_options['display_options']['heading_color'] ) && ! empty( $display_options['display_options']['heading_color'] ) ) {
		$module_attributes['heading_color'] = esc_attr( $display_options['display_options']['heading_color'] );
	}

	// Set the text color
	if ( isset( $display_options['display_options']['text_color'] ) && ! empty( $display_options['display_options']['text_color'] ) ) {
		$module_attributes['text_color'] =  esc_attr( $display_options['display_options']['text_color'] );
	}
	$module_attributes = wp_parse_args( $module_attributes, $module_defaults );

	foreach ( $module_attributes as $attr => $value ) {
		$module_settings[$attr] = get_class_name_by_attribute( $attr, $value );
	}

	return $module_settings;


}
