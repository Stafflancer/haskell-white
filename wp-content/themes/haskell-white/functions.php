<?php
/**
 * Functions and definitions.
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package hwcpa
 * @since   1.0.0
 */

namespace BopDesign\hwcpa;

/**
 * Define theme globals: theme version, text domain, etc.
 *
 * @since 1.0.0
 */
$theme_version  = wp_get_theme()->get( 'Version' );
$version_string = is_string( $theme_version ) ? $theme_version : null;

define( 'THEME_VERSION', $version_string );
define( 'THEME_ROOT_PATH', trailingslashit( get_template_directory() ) );
define( 'THEME_ROOT_URL', trailingslashit( get_template_directory_uri() ) );
define( 'THEME_TEXT_DOMAIN', 'hwcpa' );

/*
 * Check if the WordPress version is 6.0 or higher, and if the PHP version is at least 7.4.
 * If not, do not activate.
 */
if ( version_compare( $GLOBALS['wp_version'], '6.0', '<' ) || version_compare( PHP_VERSION_ID, '70400', '<' ) ) {
	require( 'inc/compatibility.php' );

	return;
}

/**
 * Check to see if ACF Pro is active. Give a warning message if not.
 *
 * @since  1.0
 */
require( 'inc/dependency.php' );

/**
 * Get all the include files for the theme.
 *
 * @return void
 */
function include_inc_files() {
	$files = [
		'inc/functions/',       // Custom functions that act independently of the theme templates.
		'inc/hooks/',           // Load custom filters and hooks.
		'inc/setup/',           // Theme setup.
		'inc/helpers/',         // Includes helper files.
		'inc/shortcodes/',      // Load shortcodes.
		'inc/template-tags/',   // Custom template tags for this theme.
		'inc/acf/acf.php',      // Theme ACF setup and blocks.
		'inc/optimization.php', // Optimize theme performance. Must load last for best results.
	];

	foreach ( $files as $include ) {
		$include = trailingslashit( THEME_ROOT_PATH ) . $include;

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

include_inc_files();



