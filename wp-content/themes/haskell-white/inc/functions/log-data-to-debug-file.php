<?php
/**
 * Log data to the debug.log file.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Log data to the debug.log file.
 * Useful when getting a white screen of death or an error in general.
 *
 * @param $var
 *
 * @return false|void
 */
function log_data_to_debug_file( $var ) {
	if ( empty( $var ) ) {
		return false;
	}

	ob_start();
	var_dump( $var );
	$contents = ob_get_contents();
	ob_end_clean();
	error_log( $contents );
}
