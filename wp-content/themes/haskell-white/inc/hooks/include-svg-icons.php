<?php
/**
 * Add SVG definitions to footer.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Add SVG definitions to footer.
 */
function include_svg_icons() {
	// Define SVG sprite file.
	$svg_icons = get_theme_file_path( '/assets/images/svg-icons/svg-icons-defs.svg' );

	// Check the SVG file exists.
	if ( file_exists( $svg_icons ) ) {
		echo '<div class="svg-sprite-wrapper">';
		require_once $svg_icons;
		echo '</div>';
	}
}

add_action( 'wp_footer', __NAMESPACE__ . '\include_svg_icons', 9999 );
