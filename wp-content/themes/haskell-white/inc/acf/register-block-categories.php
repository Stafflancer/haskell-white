<?php
/**
 * Register block categories.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Register custom block categories.
 *
 * @link https://developer.wordpress.org/reference/hooks/block_categories_all/
 *
 * @param $block_categories
 *
 * @return mixed
 */
function register_block_category( $block_categories ) {
	// Adding a new category.
	$block_categories[] = array(
		'slug'  => 'acf',
		'title' => 'ACF Blocks',
	);

	return $block_categories;
}

add_filter( 'block_categories_all', __NAMESPACE__ . '\register_block_category', 1, 2 );
