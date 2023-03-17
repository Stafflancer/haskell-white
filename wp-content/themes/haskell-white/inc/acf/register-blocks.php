<?php
/**
 * Register custom blocks for the theme.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Register our custom blocks.
 *
 * @return void
 */
function register_acf_blocks() {
	$blocks_path = get_theme_file_path( '/blocks' );

	if ( file_exists( $blocks_path ) ) {
		$block_dirs = array_filter( glob( $blocks_path . '/*' ), 'is_dir' );

		foreach ( $block_dirs as $block ) {
			register_block_type( $block );
		}
	}
}

add_action( 'acf/init', __NAMESPACE__ . '\register_acf_blocks', 5 );
