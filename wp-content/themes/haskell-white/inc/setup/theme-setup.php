<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function theme_setup() {
	/**
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on hwcpa, refer to the
	 * README.md file in this theme to find and replace all
	 * references of hwcpa
	 */
	load_theme_textdomain( THEME_TEXT_DOMAIN, get_template_directory() . '/languages' );

	// Add support for block styles.
	add_theme_support( 'wp-block-styles' );


	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Let WordPress manage the document title.
	 * This feature enables plugins and themes to manage the document title tag (1). This should be used in place of
	 * wp_title() (2) function.
	 *
	 * @link https://codex.wordpress.org/Title_Tag (1)
	 *       https://developer.wordpress.org/reference/functions/wp_title/ (2)
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	/**
	 * Register new image sizes.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_image_size/
	 */
	// add_image_size( 'rename-me', width, height );
	// add_image_size( 'rename-me-too', width, height, true ); // true if we need cropped size for consistency.
	// Add additional image sizes.
	add_image_size( 'full-width', 1920, 1080 );

	/**
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 * Remove type="text/javascript" and type="text/css" from enqueued scripts and styles.
	 */
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		]
	);

	// Gutenberg responsive embed support.
	//add_theme_support( 'responsive-embeds' );

	/**
	 * Disabling the default block patterns.
	 * WordPress comes with a number of block patterns built-in, themes can opt out of the bundled patterns and
	 * provide their own set.
	 */
	remove_theme_support( 'core-block-patterns' );

	// Register navigation menus.
	register_nav_menus( [
		'primary' => esc_html__( 'Primary Menu', THEME_TEXT_DOMAIN ),
		'footer'  => esc_html__( 'Footer Menu', THEME_TEXT_DOMAIN ),
		'mobile'  => esc_html__( 'Mobile Menu', THEME_TEXT_DOMAIN ),
	] );

	// Enqueue editor styles.
	add_theme_support( 'editor-styles' );
	add_editor_style( [
		'assets/css/vendors/bootstrap-grid.css',
		'assets/css/vendors/bootstrap-utilities.css',
		'blocks/columns-with-icon/style.css',
		'style.css',
	] );

	/**
	 * Load additional block styles.
	 */
	$styled_blocks = [ 'columns', 'media-text', 'quote' ];

	foreach ( $styled_blocks as $block_name ) {
		if ( file_exists( get_theme_file_path( "assets/css/blocks/$block_name.css" ) ) ) {
			$args = array(
				'handle' => "bop-acf-$block_name",
				'src'    => get_theme_file_uri( "assets/css/blocks/$block_name.css" ),

				$args['path'] = get_theme_file_path( "assets/css/blocks/$block_name.css" ),
			);

			// Replace the "core" prefix if you are styling blocks from plugins.
			wp_enqueue_block_style( "core/$block_name", $args );
		}
	}
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\theme_setup' );

/**
 * Dequeue WordPress core Block Library styles.
 */
function deregister_core_block_styles() {
	// This will remove the inline styles for the following core blocks.
	$block_styles_to_remove = [
		'heading',
		'paragraph',
		'table',
		'list',
	];

	foreach ( $block_styles_to_remove as $block_style ) {
		wp_deregister_style( 'wp-block-' . $block_style );
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\deregister_core_block_styles' );

/**
 * Filters whether block styles should be loaded separately - only load styles for used blocks.
 *
 * Returning false loads all core block assets, regardless of whether they are rendered
 * in a page or not. Returning true loads core block assets only when they are rendered.
 *
 * $load_separate_assets
 *     (bool) Whether separate assets will be loaded.
 *     Default false (all block assets are loaded, even when not used).
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Prevent loading patterns from the WordPress.org pattern directory.
 */
add_filter( 'should_load_remote_block_patterns', '__return_false' );

