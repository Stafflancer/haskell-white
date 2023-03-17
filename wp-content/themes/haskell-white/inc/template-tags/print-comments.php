<?php
/**
 * Display the comments if the count is more than 0.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Display the comments if the count is more than 0.
 *
 * @author BopDesign
 */
function print_comments() {
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}
