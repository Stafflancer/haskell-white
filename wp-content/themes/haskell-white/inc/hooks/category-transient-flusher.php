<?php
/**
 * Flush out the transients used in BopDesign\hwcpa\get_categorized_blog.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Flush out the transients used in BopDesign\hwcpa\get_categorized_blog.
 *
 * @return bool Whether transients were deleted.
 */
function category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	// Like, beat it. Dig?
	return delete_transient( 'hwcpa_categories' );
}

add_action( 'delete_category', __NAMESPACE__ . '\category_transient_flusher' );
add_action( 'save_post', __NAMESPACE__ . '\category_transient_flusher' );
