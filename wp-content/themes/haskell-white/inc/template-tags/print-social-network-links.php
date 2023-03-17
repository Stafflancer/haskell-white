<?php
/**
 * Display the social links saved in the theme options page.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Display the social links saved in the customizer.
 *
 * @author BopDesign
 */
function print_social_network_links() {
	// Create an array of our social links for ease of setup.
	// Change the order of the networks in this array to change the output order.
	$social_networks = get_field( 'social_media', 'option' );

	if ( ! empty( $social_networks ) && is_array( $social_networks ) ) :
		$count = count( $social_networks );
		?>
		<ul class="d-flex social-icons menu">
			<?php
			$i = 1;
			// Loop through our networks array.
			foreach ( $social_networks as $network => $network_url ) :
				// Only display the list item if a URL is set.
				if ( ! empty( $network_url ) ) :
					$icon_wrapper_class = ' me-2 me-lg-3';

					if ( $count === $i ) {
						$icon_wrapper_class = '';
					}
					?>
					<li class="<?php echo esc_attr( $icon_wrapper_class ); ?>">
						<a href="<?php echo esc_url( $network_url ); ?>" class="social-icon d-flex align-items-center justify-content-center rounded-circle <?php echo esc_attr( $network ); ?>">
							<?php
							print_svg( [
								'icon'   => $network,
								'width'  => '24',
								'height' => '24',
							] );
							?>
							<span class="screen-reader-text">
								<?php
								/* translators: the social network name */
								printf( esc_attr__( 'Link to %s', THEME_TEXT_DOMAIN ), ucwords( esc_html( $network ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
								?>
							</span>
						</a><!-- .social-icon -->
					</li>
					<?php
					$i++;
				endif;
			endforeach;
			?>
		</ul><!-- .social-icons -->
	<?php endif;
}
