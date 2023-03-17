<?php
/**
 * Echo data in a formatted style.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Display data in formatted style.
 *
 * @param $var
 *
 * @return false|void
 */
function echo_data( $var ) {
	if ( empty( $var ) ) {
		return false;
	}

	echo '<pre>';
	print_r( $var );
	echo '</pre>';
}
