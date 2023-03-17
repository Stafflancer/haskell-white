<?php
/**
 * Force Yoast panel to the bottom of edit screens.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

// Bail if Yoast is not installed.
if ( ! class_exists( 'WPSEO_Options' ) ) {
	return;
}

/**
 * Move Yoast settings panel to the bottom of the page.
 */
add_filter( 'wpseo_metabox_prio', function () {
	return 'low';
}, 100, 1 );
