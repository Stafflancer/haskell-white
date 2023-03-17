<?php
/**
 * Privacy and Cookie Policy configuration - pp - page.
 *
 * @package  Iubenda
 */

// Including partial header.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/header.php';
?>

<div class="main-box">
	<?php
	// Including partial site-info.
	require_once IUBENDA_PLUGIN_PATH . 'views/partials/site-info.php';

	// Including partial breadcrumb.
	require_once IUBENDA_PLUGIN_PATH . 'views/partials/breadcrumb.php';
	?>
	<form class="ajax-form-to-options">
		<input hidden name="iubenda_section_name" value="iubenda_privacy_policy_solution">
		<input hidden name="iubenda_section_key" value="pp">
		<input hidden name="action" value="ajax_save_options">
		<?php wp_nonce_field( 'iub_ajax_save_options_nonce', 'iub_pp_nonce' ); ?>
		<input hidden name="_redirect" value="<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>">
		<div class="px-4 px-lg-5">
			<?php
			$url = 'javascript:void(0)';

			if ( ! empty( iubenda()->settings->links['privacy_policy_generator_edit'] ) ) {
				$url = iubenda()->settings->links['privacy_policy_generator_edit'];
			} elseif ( iub_array_get( iubenda()->options['global_options'], 'site_id' ) ) {
				$url = iubenda()->settings->links['flow_page'];
			}
			?>
			<div class="text-center py-5">
				<a target="_blank" href="<?php echo esc_url( $url ); ?>" class="btn btn-green-secondary btn-sm"><?php esc_html_e( 'Setup your privacy and cookie policy on iubenda.com', 'iubenda' ); ?></a>
			</div>
			<hr>
			<div class="py-5">
				<h3 class="m-0 mb-4"><?php esc_html_e( 'Integration', 'iubenda' ); ?></h3>
				<?php
				// Including partial button-style.
				require_once IUBENDA_PLUGIN_PATH . 'views/partials/button-style.php';

				// Including partial button-position.
				require_once IUBENDA_PLUGIN_PATH . 'views/partials/button-position.php';
				?>
			</div>
		</div>
		<hr>
		<div class="p-4 d-flex justify-content-end">
			<input class="btn btn-gray-lighter btn-sm mr-2" type="button" value="<?php esc_html_e( 'Cancel', 'iubenda' ); ?>" onclick="window.location.href = '<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>'"/>
			<button type="submit" class="btn btn-green-primary btn-sm" value="Save" name="save">
				<span class="button__text"><?php esc_html_e( 'Save settings', 'iubenda' ); ?></span>
			</button>
		</div>
	</form>
</div>

<?php
// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/footer.php';
?>
