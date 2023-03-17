<?php
/**
 * Echo the copyright text saved in the Customizer.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Echo the copyright text saved in the Customizer.
 *
 * @author BopDesign
 */
function print_copyright_text() {
	// Grab our copyright group from the theme settings.
	$copyright_group = get_field( 'copyright_group', 'option' );
	$copyright_data = $copyright_group['copyright'];
	if ( ! empty( $copyright_group ) && is_array( $copyright_group ) ) :

		$copyright_links = $copyright_group['links'];
		?>
		<section class="font-size-small">
			&copy; <?php echo gmdate( 'Y' ); ?> <?php echo esc_html( $copyright_data ); ?><?php
			if ( $copyright_links ):
				foreach ( $copyright_links as $link ) {
					$link_url   = $link['link']['url'];
					$link_title = $link['link']['title'];

					echo '<span>&nbsp;|&nbsp;</span><a href="' . $link_url . '">' . $link_title . '</a>';
				}
				?>
			<?php endif; ?>
		</section>
	<?php else: ?>
		<section class="font-size-small">&copy; <?php echo gmdate( 'Y' ) . ' ' . get_bloginfo( 'name' ); ?></section>
	<?php
	endif;
}
