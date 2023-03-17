<?php
/**
 * Pingback header.
 *
 * Add a pingback url header if pingbacks are open.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

add_action( 'wp_head', __NAMESPACE__ . '\pingback_header', 1 );
