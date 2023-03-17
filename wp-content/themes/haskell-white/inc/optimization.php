<?php
/**
 * Optimize theme performance.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Clean up WordPress Header
 */
remove_action( 'wp_head', 'wp_resource_hints', 2 );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

/**
 * Disable the emojis.
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'embed_head', 'print_emoji_detection_script' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

add_action( 'init', __NAMESPACE__ . '\disable_emojis' );
/** Clean up WordPress Header END */

/**
 * Remove JQuery migrate.
 *
 * @param $scripts
 *
 * @return void
 */
function dequeue_jquery_migrate( $scripts ) {
	if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
		$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, [ 'jquery-migrate' ] );
	}
}

add_action( 'wp_default_scripts', __NAMESPACE__ . '\dequeue_jquery_migrate' );

/**
 * Remove self pings.
 */
add_action( 'pre_ping', function ( &$links ) {
	$home = get_option( 'home' );

	foreach ( $links as $l => $link ) {
		if ( 0 === strpos( $link, $home ) ) {
			unset( $links[ $l ] );
		}
	}
} );

/**
 * Slow down the default heartbeat.
 */
add_filter( 'heartbeat_settings', function ( $settings ) {
	// 60 seconds.
	$settings['interval'] = 60;

	return $settings;
} );

/**
 * Remove wp-embed.min.js
 *
 * @return void
 */
function deregister_scripts() {
	wp_dequeue_script( 'wp-embed' );
}

add_action( 'wp_footer', __NAMESPACE__ . '\deregister_scripts' );

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function disable_loading_css_js() {
	if ( ! is_user_logged_in() ) {
		wp_dequeue_style( 'dashicons' );

		wp_dequeue_script( 'jquery-ui-datepicker' );
	}

	if ( is_front_page() && ! is_user_logged_in() ) {
		wp_dequeue_style( 'addthis_all_pages' );
		wp_dequeue_style( 'search-filter-plugin-styles' );

		wp_dequeue_script( 'addthis_widget' );
		wp_dequeue_script( 'jquery-ui-datepicker' );
		wp_dequeue_script( 'search-filter-plugin-build' );
		wp_dequeue_script( 'search-filter-plugin-chosen' );
	}

	wp_dequeue_script( 'devicepx' );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\disable_loading_css_js', 9999 );

/**
 * Remove JS/CSS version for Speed Optimization.
 *
 * @param $src
 *
 * @return string
 */
function remove_script_version( $src ) {
	$parts = explode( '?ver', $src );

	return $parts[0];
}

//add_filter( 'script_loader_src', __NAMESPACE__ . '\remove_script_version', 15, 1 );
//add_filter( 'style_loader_src', __NAMESPACE__ . '\remove_script_version', 15, 1 );
