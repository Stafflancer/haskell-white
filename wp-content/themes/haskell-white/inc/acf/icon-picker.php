<?php
/**
 * Modify paths for the ACF Icon Picker plugin.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Modify the path to the icons' directory.
 */
add_filter( 'acf_icon_path_suffix', function ( $path_suffix ) {
	return 'assets/images/acf-icons/';
} );

/**
 * Modify the path to the above prefix.
 */
add_filter( 'acf_icon_path', function ( $path_suffix ) {
	return get_template_directory();
} );

/**
 * Modify the URL to the icons' directory to display on the page.
 */
add_filter( 'acf_icon_url', function ( $path_suffix ) {
	return get_stylesheet_directory_uri();
} );
