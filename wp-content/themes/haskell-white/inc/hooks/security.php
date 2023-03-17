<?php
/**
 * Security functions.
 *
 * Enable or disable certain functionality to harden WordPress.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Remove generator meta tags.
 *
 * @see https://developer.wordpress.org/reference/functions/the_generator/
 */
add_filter( 'the_generator', '__return_false' );

/**
 * Disable XML RPC.
 *
 * @see https://developer.wordpress.org/reference/hooks/xmlrpc_enabled/
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Change REST-API header from "null" to "*".
 *
 * @see https://w3c.github.io/webappsec-cors-for-developers/#avoid-returning-access-control-allow-origin-null
 */
function cors_control() {
	header( 'Access-Control-Allow-Origin: *' );
}

add_action( 'rest_api_init', __NAMESPACE__ . '\cors_control' );

/**
 * Disable use X-Pingback.
 *
 * @param $headers
 *
 * @return mixed
 */
function disable_x_pingback( $headers ) {
	unset( $headers['X-Pingback'] );

	return $headers;
}

add_filter( 'wp_headers', __NAMESPACE__ . '\disable_x_pingback' );

/**
 * Login page custom messages.
 *
 * @return string
 */
function add_login_message() {
	return '<p class="message"><strong>Tip:</strong> Use a unique and complex password to keep your login secure.</p>';
}

add_filter( 'login_message', __NAMESPACE__ . '\add_login_message' );

/**
 * Show less info to users on failed login for security.
 * On a failed login attempt, WordPress shows errors that tell users whether their username was incorrect or
 * the password. These login hints can be used by someone for malicious attempts.
 * (Will not let a valid username be known.)
 *
 * @return string
 */
function no_wordpress_errors() {
	return '<strong>ERROR</strong>: Something is wrong!';
}

add_filter( 'login_errors', __NAMESPACE__ . '\no_wordpress_errors' );
