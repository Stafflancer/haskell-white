<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_main_classes;
use function BopDesign\hwcpa\print_module;

get_header();

$id = 'page-404';
$section_class_name = 'error-404 not-found';
?>
	<main id="main" class="<?php echo esc_attr( get_main_classes( [] ) ); ?>" role="main">
		<?php
		if ( have_rows( 'page_settings_404', 'option' ) ):
			while ( have_rows( 'page_settings_404', 'option' ) ): the_row();
				$page_title_404   = get_sub_field( 'page_title_404' );
				$page_content_404 = get_sub_field( 'page_content_404' );
				$page_buttons_404 = get_sub_field( 'page_buttons_404' );
				$display_options  = get_sub_field( 'display_options' );

				// Parse settings.
				$display_options['full_height']   = true;
				$display_options['align']         = 'full';
				$display_options['align_text']    = 'center';
				$display_options['align_content'] = 'center center';

				// Start a <container> with possible block options.
				$container_args = [
					'container' => 'section', // Any HTML5 container: section, div, etc...
					'id'        => $id, // Container id.
					'class'     => $section_class_name, // Container class.
				];

				hwcpa_display_block_background_options( $display_options, $container_args, [], true );
				?>
				<div class="container position-relative h-100 z-10">
					<header class="page-header">
						<p class="tagline has-white-color has-text-color font-size-colossal font-weight-bold">404</p>
						<?php if ( ! empty( $page_title_404 ) ): ?>
							<h1 class="page-title has-light-color has-text-color">
								<?php esc_html_e( $page_title_404, THEME_TEXT_DOMAIN ); ?>
							</h1>
						<?php endif; ?>
					</header><!-- .page-header -->

					<?php if ( ! empty( $page_content_404 ) ): ?>
						<div class="page-content has-white-color has-text-color">
							<?php echo wp_kses_post( $page_content_404 ); ?>
						</div><!-- .page-content -->
					<?php endif; ?>

					<?php if ( ! empty( $page_buttons_404 ) ): ?>
						<div class="page-buttons mt-5">
							<?php
							$buttons_group['class']         = 'justify-content-center';
							$buttons_group['buttons_group'] = $page_buttons_404;
							print_module(
								'buttons-group',
								$buttons_group
							);
							?>
						</div><!-- .page-buttons -->
					<?php endif; ?>
				</div>
				<?php hwcpa_close_block( $container_args['container'] );?>
			<?php endwhile; ?>
		<?php else: ?>
			<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $section_class_name ); ?> alignfull vh-100 mt-0 mb-0 padding-top-medium padding-bottom-medium has-background image-as-background position-relative overflow-hidden">
				<span aria-hidden="true" class="position-absolute z-1 has-background-dim has-foreground-background-color has-background-dim-80"></span>
				<figure class="image-background d-block w-100 h-auto m-0 position-absolute top-0 bottom-0 start-0 end-0 object-center z-0" aria-hidden="true">
					<img src="<?php echo get_theme_file_uri( '/assets/images/placeholder.jpg' ); ?>" class="w-100 h-100 object-cover" alt="Placeholder" decoding="async" loading="lazy" width="1920" height="1080">
				</figure>
				<div class="inner-container position-relative d-flex h-100 z-10 text-center align-items-center justify-content-center is-position-center-center container">
					<div class="col-12 col-md-8  wow animate__ animate__fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
						<div class="container position-relative h-100 z-10">
							<header class="page-header">
								<p class="tagline has-white-color has-text-color font-size-colossal font-weight-bold">404</p>
								<h1 class="page-title has-light-color has-text-color">
									<?php esc_html_e( 'Oops! Page not found.', THEME_TEXT_DOMAIN ); ?>
								</h1>
							</header>

							<div class="page-content has-white-color has-text-color">
								<p><?php _e( wp_kses_post( 'We can’t seem to find the page you’re looking for. It may have been moved, please try going back to the previous page or see our <a href="/resources/">Resources</a> for more information.' ), THEME_TEXT_DOMAIN ); ?></p>
							</div>

							<div class="page-buttons mt-5">
								<div class="wp-block-buttons d-flex is-layout-flex justify-content-center">
									<div class="wp-block-button is-style-fill">
										<a href="/" class="wp-block-button__link wp-element-button has-secondary-background-color has-background has-foreground-color has-text-color">Back to Home</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</main>
<?php
get_footer();
