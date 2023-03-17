<?php
/**
 * Preload styles and scripts.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Preload styles and scripts.
 *
 * @return void
 */
function preload_scripts() {
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preload" href="https://fonts.googleapis.com/css2?family=Jost:wght@400;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jost:wght@400;700&display=swap"></noscript>
	<link rel="preload" href="<?php echo esc_url( get_theme_file_uri( '/assets/css/vendors/bootstrap-grid.min.css' ) ); ?>" as="style">
	<link rel="preload" href="<?php echo esc_url( get_theme_file_uri( '/assets/css/vendors/bootstrap-utilities.min.css' ) ); ?>" as="style">
	<link rel="preload" href="<?php echo esc_url( get_stylesheet_uri() ); ?>" as="style">
	<link rel="preload" href="<?php echo esc_url( get_theme_file_uri( '/assets/css/vendors/animate.min.css' ) ); ?>" as="style">
	<link rel="preload" href="<?php echo esc_url( get_theme_file_uri( '/assets/js/vendors/wow/wow.min.js' ) ); ?>" as="script">
	<link rel="preload" href="<?php echo esc_url( get_theme_file_uri( '/assets/js/main.js' ) ); ?>" as="script">
	<?php
}
add_action( 'wp_head', __NAMESPACE__ . '\preload_scripts', 1 );
