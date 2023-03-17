<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_copyright_text;
use function BopDesign\hwcpa\print_social_network_links;
use function BopDesign\hwcpa\print_mobile_menu;
use function BopDesign\hwcpa\print_addresses;
use function BopDesign\hwcpa\print_header_buttons;

$footerlogo = get_field('logo', 'option');
?>
	<footer id="colophon" class="site-footer footer-main" role="contentinfo">
		<div class="container"><?php
			if(!empty($footerlogo)){ ?>
				<div class="footer-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php echo wp_get_attachment_image_url( $footerlogo, 'medium' ); ?>" class="logo" alt="hwcpa Logo"/>
					</a>
				</div><?php
			} ?>
			<div class="contact-details">
				<div class="row align-items-center">
					<div class="col-12 col-md-7 col-lg-6"><?php print_addresses(); ?></div>
					<div class="col-12 col-md-5 col-lg-6 text-md-end text-lg-end contact-btns"><?php print_header_buttons(); ?></div>
				</div>
			</div>
			<div class="footer-menus">
				<div class="row">
					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<nav id="site-footer-navigation" class="footer-navigation col-12 col-lg" aria-label="<?php esc_attr_e( 'Footer Navigation', THEME_TEXT_DOMAIN ); ?>">
							<ul id="footer-menu" class="footer-menu menu d-block d-lg-flex">
								<?php
								wp_nav_menu(
									array(
										'theme_location' => 'footer',
										'items_wrap'     => '%3$s',
										'container'      => false,
										'link_before'    => '<span>',
										'link_after'     => '</span>',
										'fallback_cb'    => false,
									)
								);
								?>
							</ul><!-- .footer-navigation-wrapper -->
						</nav><!-- .footer-navigation -->
					<?php endif; ?>

					<section class="social-menu col-12 col-lg-auto mt-4 mt-lg-0">
						<?php print_social_network_links(); ?>
					</section>
				</div>
			</div>

			<div class="site-info row">
				<?php print_copyright_text(); ?>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->

	<?php print_mobile_menu(); ?>
	<?php wp_footer(); ?>
</body>
</html>
