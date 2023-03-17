<?php
/**
 * Adds ACF custom field_names in the backend. Won't display them for 'editor' users.
 * ACF display field_names.
 */

if ( ! current_user_can( 'editor' ) ) {
	add_action( 'acf/render_field', function ( $field ) {
		echo $field['_name'];
	}, 10, 1 );
}