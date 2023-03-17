<?php
/**
 * Displays the buttons set in the options page: Theme Options -> Header.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Displays the header navigation buttons.
 */
function print_header_buttons() {
	// Pull in the fields from ACF Theme Options.
	$navigation_buttons = get_acf_fields( [ 'navigation_buttons' ], 'option' );
	
	if ( ! empty( $navigation_buttons ) && is_array( $navigation_buttons ) ) :

		$buttons_group['class']   = '';
		$buttons_group['buttons'] = $navigation_buttons['navigation_buttons'];

		print_module( 'buttons-group', $buttons_group );
	endif;
}
