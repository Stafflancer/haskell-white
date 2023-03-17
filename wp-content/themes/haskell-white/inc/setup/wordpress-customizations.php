<?php
/**
 * Customization of WordPress.
 *
 * Enable or disable certain functionality to customize WordPress.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Remove tags support from posts.
 *
 * @link   https://developer.wordpress.org/reference/functions/unregister_taxonomy_for_object_type/
 *
 * @return void
 */
function unregister_tags() {
	unregister_taxonomy_for_object_type( 'post_tag', 'post' );
}

add_action( 'init', __NAMESPACE__ . '\unregister_tags' );

/**
 * Disable WordPress search function.
 *
 * @param $query
 * @param $error
 *
 * @return void
 */
function filter_query( $query, $error = true ) {
	if ( is_search() ) {
		$query->is_search       = false;
		$query->query_vars['s'] = false;
		$query->query['s']      = false;

		if ( $error == true ) {
			$query->is_404 = true;
		}
	}
}

//add_action( 'parse_query', __NAMESPACE__ . '\filter_query' );
//add_filter( 'get_search_form', function ( $a ) {
//	return '';
//} );

/**
 * Remove WP Search Widget.
 *
 * @return void
 */
function remove_search_widget() {
	unregister_widget( 'WP_Widget_Search' );
}

add_action( 'widgets_init', __NAMESPACE__ . '\remove_search_widget' );

/**
 * Remove theme options.
 *
 * @param $wp_customize
 *
 * @return void
 */
function customize_register( $wp_customize ) {
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'background_image' );
	$wp_customize->remove_section( 'header_image' );
}

add_action( 'customize_register', __NAMESPACE__ . '\customize_register' );

/**
 * Remove unused menus.
 *
 * @return void
 */
function remove_unnecessary_wordpress_menus() {
	global $submenu;

	if ( isset( $submenu['themes.php'] ) ):
		foreach ( $submenu['themes.php'] as $menu_index => $theme_menu ) {
			if ( $theme_menu[0] == 'Header' || $theme_menu[0] == 'Background' ) {
				unset( $submenu['themes.php'][ $menu_index ] );
			}
		}
	endif;
}

add_action( 'admin_menu', __NAMESPACE__ . '\remove_unnecessary_wordpress_menus', 999 );

/**
 * Show Featured Images in admin columns.
 *
 * @link https://www.isitwp.com/add-featured-thumbnail-to-admin-post-columns/
 */
function posts_columns( $defaults ) {
	$defaults['hwcpa_post_thumbs'] = __( 'Featured Image' );

	return $defaults;
}

function posts_custom_columns( $column_name, $id ) {
	if ( 'hwcpa_post_thumbs' === $column_name ) {
		the_post_thumbnail( 'thumbnail' );
	}
}

add_filter( 'manage_posts_columns', __NAMESPACE__ . '\posts_columns', 5 );
add_action( 'manage_posts_custom_column', __NAMESPACE__ . '\posts_custom_columns', 5, 2 );

function pages_columns( $defaults ) {
	$defaults['hwcpa_post_thumbs'] = __( 'Featured Image' );

	return $defaults;
}

function pages_custom_columns( $column_name, $id ) {
	if ( 'hwcpa_post_thumbs' === $column_name ) {
		the_post_thumbnail( 'thumbnail' );
	}
}

add_filter( 'manage_pages_columns', __NAMESPACE__ . '\pages_columns', 5 );
add_action( 'manage_pages_custom_column', __NAMESPACE__ . '\pages_custom_columns', 5, 2 );

/**
 * Prevent upload in WordPress media library if there is already a file with the same name.
 */
add_filter( 'wp_handle_upload_prefilter', function ( $file ) {
	$uploads       = wp_upload_dir();
	$use_yearmonth = get_option( 'uploads_use_yearmonth_folders' );

	if ( boolval( $use_yearmonth ) ) {
		// if upload to year month based folders is enabled check current target
		$year   = date( 'Y' );
		$month  = date( 'm' );
		$target = $uploads['path'] . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR . $file['name'];
	} else {
		// uploads dir
		$target = $uploads['path'] . DIRECTORY_SEPARATOR . $file['name'];
	}

	if ( file_exists( $target ) ) {
		$file['error'] = 'File with the same name already exists. Either overwrite/replace the file via FTP, or rename your file before uploading. Remember to (S)FTP overwrite/replace the @2x version of the image file if needed.';
	}

	return $file;
} );

/**
 * Set favicon icon.
 *
 * @param $url
 * @param $size
 * @param $blog_id
 *
 * @return mixed|void
 */
function get_site_icon_url( $url, $size, $blog_id ) {
	// If ACF isn't activated, then bail.
	if ( ! class_exists( 'ACF' ) ) {
		return '';
	}

	$favicon_icon = get_field( 'favicon', 'option' );

	if ( isset( $favicon_icon ) && ! empty( $favicon_icon ) ):
		$url = $favicon_icon;
	endif;

	return apply_filters( 'custom_get_site_icon_url', $url, $size, $blog_id );
}

add_filter( 'get_site_icon_url', __NAMESPACE__ . '\get_site_icon_url', 10, 3 );

/**
 * Admin backend customization for logo.
 *
 * @return void
 */
function login_logo() {
	if ( class_exists( 'ACF' ) ) {
		// Return Array to get image sizes.
		$logo = get_field( 'admin_logo', 'option' );
	}

	if ( ! empty( $logo ) && $logo['width'] && $logo['height'] ) {
		$logo_url    = $logo['url'];
		$logo_width  = $logo['width'];
		$logo_height = $logo['height'];
	} else {
		$logo_url    = get_theme_file_uri( '/assets/images/svgs/logo.svg' );
		$logo_width  = '177px';
		$logo_height = '50px';
	}
	?>
	<style>
		body.login div#login h1 a {
			background-image: url("<?php echo $logo_url; ?>");
			background-position: center;
			background-size: <?php echo $logo_width; ?> <?php echo $logo_height; ?>;
			width: <?php echo $logo_width; ?>;
			height: <?php echo $logo_height; ?>;
		}
	</style>
	<?php
}

add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\login_logo' );

/**
 * Get the home page URL.
 *
 * @return string|null
 */
function login_logo_url() {
	return home_url();
}

add_filter( 'login_headerurl', __NAMESPACE__ . '\login_logo_url' );


