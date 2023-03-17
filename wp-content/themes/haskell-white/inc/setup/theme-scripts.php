<?php
/**
 * Setup theme scripts and styles.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function scripts() {
	/**
	 * Enqueue 3rd party required styles.
	 */
	wp_enqueue_style( 'bootstrap-grid', get_theme_file_uri( '/assets/css/vendors/bootstrap-grid.min.css' ), [], null );
	wp_enqueue_style( 'bootstrap-utilities', get_theme_file_uri( '/assets/css/vendors/bootstrap-utilities.min.css' ), [], null );

	/**
	 * Enqueue theme required styles.
	 */
	wp_enqueue_style( 'hwcpa-menus-style', get_theme_file_uri( '/assets/css/menus.css' ), [], null );
	wp_enqueue_style( 'hwcpa-mobile-menu-style', get_theme_file_uri( '/assets/css/mobile-menu.css' ), [], null, '(max-width: 1023px)' );
	wp_enqueue_style( 'hwcpa-off-canvas-style', get_theme_file_uri( '/assets/css/off-canvas.css' ), [], null, '(max-width: 1023px)' );
	wp_enqueue_style( 'hwcpa-style', get_stylesheet_uri(), [], null );
	
	wp_enqueue_style( 'main-css', get_stylesheet_directory_uri() . '/assets/css/main.min.css' );
	wp_enqueue_style( 'responsive-css', get_stylesheet_directory_uri() . '/assets/css/responsive.css' );
	/**
	 * Register scripts to use in templates and load only when needed.
	 */
	wp_register_style( 'swiperjs-style', get_theme_file_uri( '/assets/css/vendors/swiper-bundle.min.css' ), [], null );
	wp_register_script( 'swiperjs-script', get_theme_file_uri( '/assets/js/vendors/swiperjs/swiper-bundle.min.js' ), [], null, true );

	wp_register_style( 'modal-style', get_theme_file_uri( '/assets/css/modules/modal.css' ), [], null );
	wp_register_script( 'modal-video-script', get_theme_file_uri( '/assets/js/modules/modal-video.js' ), [], null, true );

	wp_enqueue_script( 'custom-script-js', get_theme_file_uri( '/assets/js/custom-script.js' ), [], null, true );
	
	/**
	 * Enqueue required scripts.
	 */
	wp_enqueue_script( 'wow-script', get_theme_file_uri( '/assets/js/vendors/wow/wow.min.js' ), [], null, true );
	wp_enqueue_script( 'hwcpa-script', get_theme_file_uri( '/assets/js/main.js' ), [ 'wow-script' ], null, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_user_logged_in() ) {
		wp_enqueue_style( 'hwcpa-wp-admin-style', get_theme_file_uri( '/assets/css/wp-admin.css' ), [], null );
	}
	
	/**
	 * Register block script
	 */

	wp_register_script( 'slider-testimonials-script', get_template_directory_uri() . '/blocks/slider-testimonials/slider-testimonials.js', [ 'jquery', 'acf' ] );

	wp_register_script( 'slider-featured-posts-script', get_template_directory_uri() . '/blocks/slider-featured-posts/slider-featured-posts.js', [ 'jquery', 'acf' ] );

	wp_register_script( 'related-content-script', get_template_directory_uri() . '/blocks/related-content/related-content.js', [ 'jquery', 'acf' ] );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );
