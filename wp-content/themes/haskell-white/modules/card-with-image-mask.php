<?php
/**
 * MODULE: Card with Image Mask
 *
 * Modules are analogous to 'Molecules' in Brad Frost's Atomic Design Methodology.
 *
 * @link    https://atomicdesign.bradfrost.com/chapter-2/#molecules
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_element;
use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;

$module_defaults = [
	'class'   => [
		'acf-module',
		'acf-module-card-with-image-mask',
		'card',
		'',
		'',
		'',
		'',
		'has-background-background-color',
	],
	'image'    => false,
	'heading' => false,
	'content' => false,
	'button'    => false,
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );

$image_class = 'card-img-top w-100 h-100 object-cover object-center';
?>
<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="image-mask-flex"><?php if ( ! empty( $module_args['image'] ) ) : ?>
		<figure class="" role="img" aria-hidden="true">
			<?php echo wp_get_attachment_image( $module_args['image'], 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
		</figure>
	<?php endif; ?>
	<div class="card-body flex-grow-1 flex-shrink-1">
		<?php
		// Heading.
		if ( $module_args['heading'] ) :
			print_element( 'heading', [
				'text'  => $module_args['heading'],
				'level' => 4,
				'class' => [ 'card-title', 'h3', 'text-uppercase' ],
			] );
		endif;

		// Content.
		if ( $module_args['content'] ) :
			print_element( 'content', [
				'content' => $module_args['content'],
			] );
		endif;
		?>
	</div></div>
	<?php
	// Link.
	if ( ! empty( $module_args['button'] ) ) :
		?>
		<div class="card-footer d-flex justify-content-center">
			<?php
			print_element( 'anchor', [
				'text'  => $module_args['button']['title'],
				'href'  => $module_args['button']['url'],
				'class' => [ 'stretched-link', 'has-background', 'has-primary-color', 'has-text-color', 'btn-fill' ],
			] );
			?>
		</div>
	<?php endif; ?>
</div>
