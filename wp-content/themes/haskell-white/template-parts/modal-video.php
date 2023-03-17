<?php
wp_enqueue_style( 'modal-style' );
wp_enqueue_script( 'modal-video-script' );

$modal_id = uniqid( 'modal-' );
// TODO: Button element should be dynamic, to pull in data from the block
?>
<div class="is-layout-flex wp-block-buttons">
	<div class="wp-block-button">
		<a class="wp-block-button__link wp-element-button modal-trigger" data-target="#<?php echo esc_attr( $modal_id ); ?>">Watch Video</a>
	</div>
</div>
<div id="<?php echo esc_attr( $modal_id ); ?>" class="modal modal-closed position-fixed top-0 end-0 bottom-0 start-0" aria-hidden="true">
	<div class="modal-dialog is-closed d-flex p-3 position-absolute top-0 end-0 bottom-0 start-0 justify-content-center align-content-center">
		<div class="modal-overlay d-block position-absolute top-0 end-0 bottom-0 start-0"></div>
		<button class="modal-close d-block border-0 shadow-none p-0 position-absolute top-0 end-0 z-1" type="button" data-target="#modal-1" aria-label="Close Modal">
			<?php echo hwcpa_get_svg( [ 'icon' => 'close', 'color' => '#fff', 'width' => '32', 'height' => '32', 'title' => 'Close Modal' ] ); ?>
		</button>
		<div class="modal-content position-relative">
			<div class="modal-body w-100 h-auto position-absolute top-50 end-0 start-0 translate-middle-y">
				<div class="ratio ratio-16x9">
					<iframe data-src="https://www.youtube.com/embed/hwFNtN4rqDQ?autoplay=1" src="" width="560" height="315" allowfullscreen=""></iframe>
				</div>
			</div>
		</div>
	</div>
</div>
