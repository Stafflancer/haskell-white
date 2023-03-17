<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_svg;
use function BopDesign\hwcpa\print_header_buttons;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<?php
	if ( isset( $_ENV['PANTHEON_ENVIRONMENT'] ) && 'live' !== $_ENV['PANTHEON_ENVIRONMENT'] ):
		// Set $api_key for DEV and TEST environment.
		$api_key = ( 'dev' === $_ENV['PANTHEON_ENVIRONMENT'] ) ? 'oq49c8yyhq0wu8bg8nwtuq' : 'oq49c8yyhq0wu8bg8nwtuq';
		?>
		<script src="https://www.bugherd.com/sidebarv2.js?apikey=<?php echo $api_key; ?>" async></script>
	<?php endif; ?>
</head>
<body <?php body_class( 'site-wrapper no-js' ); ?>>
	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', THEME_TEXT_DOMAIN ); ?></a>

	<?php
	$site_logo        = get_field( 'site_logo', 'option' );
	$wrapper_classes  = 'site-header w-100 fixed-top';
	$wrapper_classes .= $site_logo ? ' has-logo' : '';
	$wrapper_classes .= has_nav_menu( 'primary' ) || has_nav_menu( 'mobile' ) ? ' has-menu' : '';
	?>
	<header id="masthead" class="<?php echo esc_attr( $wrapper_classes ); ?> header-hastell" role="banner">
		<div class="container position-relative">
			<div class="row align-items-center">
				<div class="col-6 col-lg-3">
					<div class="site-branding header-logo">
						<?php if ( $site_logo ) : ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<img src="<?php echo wp_get_attachment_image_url( $site_logo, 'medium' ); ?>" class="logo" width="200" height="80" alt="Go back to home page"/>
							</a>
						<?php else: ?>
							<p class="site-title">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
							</p>
						<?php endif; ?>
					</div><!-- .site-branding -->
				</div>

				<div class="col-6 col-lg-9 text-end">
					<div class="header-right-content">
						<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'mobile' ) ) : ?>
							<button type="button" class="off-canvas-open d-block d-lg-none p-0 position-absolute top-50 translate-middle-y bottom-0" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open Menu', THEME_TEXT_DOMAIN ); ?>">
								<?php
								print_svg( [
									'icon'   => 'hamburger',
									'width'  => '24',
									'height' => '24',
								] );
								?>
							</button>
						<?php endif; ?>

						<nav id="site-navigation" class="main-navigation navigation-menu d-none d-lg-flex justify-content-end" aria-label="<?php esc_attr_e( 'Main Navigation', THEME_TEXT_DOMAIN ); ?>">
							<?php
							wp_nav_menu(
								[
									'theme_location' => 'primary',
									'menu_id'        => 'primary-menu',
									'menu_class'     => 'menu dropdown d-flex justify-content-end',
									'container'      => false,
									'fallback_cb'    => false,
								]
							);
							?>
							<div class="header-btn">
								<?php print_header_buttons(); ?>
								<?php
								$search_shortcode = get_field( 'search_shortcode', 'option' );
								if ( $search_shortcode ) { ?>
									<div class="search-icon search-desktop">
										<div class="header-search-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="18.948" height="19.953" viewBox="0 0 18.948 19.953">
												<g id="Search_Icon" data-name="Search Icon" transform="translate(-1781.653 -49.226)">
												   <g id="Ellipse_675" data-name="Ellipse 675" transform="translate(1781.653 49.226)" fill="none" stroke="#fff" stroke-width="3">
												      <circle cx="8" cy="8" r="8" stroke="none"/>
												      <circle cx="8" cy="8" r="6.5" fill="none"/>
												    </g>
												    <path id="Path_54687" data-name="Path 54687" d="M0,0,4.24,4.24" transform="translate(1794.239 62.818)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="3"/>
												 </g>
											</svg>
										</div>
										<div class="search-form" style="display: none;">
											<?php echo do_shortcode( $search_shortcode ); ?>
										</div>
									</div><?php
								} ?>
							</div>
						</nav><!-- #site-navigation -->
					</div>
				</div>
			</div>
		</div><!-- .container -->
	</header><!-- #masthead -->
