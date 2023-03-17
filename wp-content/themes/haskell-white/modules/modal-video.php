<?php
/**
 * MODULE: Modal - Video
 * Display one or multiple buttons.
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_svg;

wp_enqueue_style( 'modal-style' );
wp_enqueue_script( 'modal-video-script' );

$modal_id = uniqid( 'modal-' );
// TODO: convert to new structure.
// TODO: Button element should be dynamic, to pull in data from the block
?>
<div class="acf-module acf-module-buttons-group d-flex is-layout-flex">
	<div class="is-style-fill">
		<button class="acf-element acf-element-button modal-trigger btn px-3 py-2 border border-2 rounded-2" data-target="#<?php echo esc_attr( $modal_id ); ?>">Watch Video</button>
	</div>
</div>
<div id="<?php echo esc_attr( $modal_id ); ?>" class="modal modal-closed position-fixed top-0 end-0 bottom-0 start-0" aria-hidden="true">
	<div class="modal-dialog is-closed d-flex p-3 position-absolute top-0 end-0 bottom-0 start-0 justify-content-center align-content-center">
		<div class="modal-overlay d-block position-absolute top-0 end-0 bottom-0 start-0"></div>
		<button class="modal-close d-block border-0 shadow-none p-0 position-absolute top-0 end-0 z-1" type="button" data-target="#modal-1" aria-label="Close Modal">
			<?php
			print_svg( [
				'icon'   => 'close',
				'color'  => '#fff',
				'width'  => '32',
				'height' => '32',
				'title'  => 'Close Modal',
			] );
			?>
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
