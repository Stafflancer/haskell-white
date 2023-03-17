<?php
/**
 * Custom ACF functions.
 *
 * A place to custom functionality related to Advanced Custom Fields.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

// If ACF isn't activated, then bail.
if ( ! class_exists( 'ACF' ) ) {
	return false;
}

/**
 * Get all the ACF include files for the theme.
 *
 * @return void
 */
function include_acf_files() {
	$acf_path  = get_theme_file_path( '/inc/acf' );
	$acf_files = [
		'disable-ui.php', // Hide ACF Menu Based on User Role.
		'acf-json.php', // Place ACF JSON in field-groups directory.
		'options-page.php', // Add ACF theme options page support.
		'get-theme-colors.php', // Returns the array of our color names and hex values.
		'acf-load-color-picker-field-choices.php', // Adds theme color to the ACF color picker.
		'search-custom-fields.php', // Extend WordPress search to include custom fields.
		'register-block-categories.php', // Registers custom block categories in the Gutenberg editor.
		'register-blocks.php', // Registers a custom block type in the Gutenberg editor.
		'block-functions.php', // Load functions used to output ACF Blocks.
	];

	foreach ( $acf_files as $acf_include ) {
		$include = trailingslashit( $acf_path ) . $acf_include;

		// Allows inclusion of individual files or all .php files in a directory.
		if ( is_dir( $include ) ) {
			foreach ( glob( $include . '*.php' ) as $file ) {
				require $file;
			}
		} else {
			require $include;
		}
	}
}

include_acf_files();
