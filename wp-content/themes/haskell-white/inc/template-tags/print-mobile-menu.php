<?php
/**
 * Displays the mobile menu with off-canvas background layer.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Displays the mobile menu with off-canvas background layer.
 *
 * @author BopDesign
 *
 * @return string An empty string if no menus are found at all.
 */
function print_mobile_menu() {
	// Bail if no mobile or primary menus are set.
	if ( ! has_nav_menu( 'mobile' ) && ! has_nav_menu( 'primary' ) ) {
		return '';
	}

	// Set a default menu location.
	$menu_location = 'primary';

	// If we have a mobile menu explicitly set, use it.
	if ( has_nav_menu( 'mobile' ) ) {
		$menu_location = 'mobile';
	}
	?>
	<div class="off-canvas-screen d-block d-lg-none position-fixed top-0 end-0 bottom-0 start-0 visually-hidden"></div>
	<nav class="off-canvas-container d-block d-lg-none position-fixed top-0 bottom-0 h-100 overflow-y-auto" aria-label="<?php esc_attr_e( 'Mobile Menu', THEME_TEXT_DOMAIN ); ?>" aria-hidden="true" tabindex="-1">
		<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'mobile' ) ) : ?>
			<button type="button" class="off-canvas-close d-block d-lg-none position-absolute" aria-expanded="false" aria-label="<?php esc_attr_e( 'Close Menu', THEME_TEXT_DOMAIN ); ?>">
				<?php
				print_svg( [
					'icon'   => 'close',
					'width'  => '24',
					'height' => '24',
				] );
				?>
			</button>
		<?php endif; ?>
		<?php
		// Mobile menu args.
		$mobile_args = [
			'theme_location'  => $menu_location,
			'container'       => 'div',
			'container_class' => 'off-canvas-content',
			'container_id'    => '',
			'menu_id'         => 'site-mobile-menu',
			'menu_class'      => 'mobile-menu',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'fallback_cb'     => false,
		];

		// Display the mobile menu.
		wp_nav_menu( $mobile_args );
		?>
	</nav>
	<?php
}
