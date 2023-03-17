<?php
/**
 * Show '(No title)' if post has no title.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Show '(No title)' if post has no title.
 *
 * @param string $title The post title.
 *
 * @return string The updated or set title.
 */
function no_title( $title ) {
	if ( ! is_admin() && empty( $title ) ) {
		$title = __( '(No title)', THEME_TEXT_DOMAIN );
	}

	return $title;
}

add_filter( 'the_title', __NAMESPACE__ . '\no_title' );
