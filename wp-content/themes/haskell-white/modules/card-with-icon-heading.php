<?php
/**
 * MODULE: Card with Icon & Heading
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
		'acf-module-card-with-icon-heading',
		'card',
		'',
		'',
		'',
		'',
		'has-background-background-color',
	],
	'icon'    => false,
	'heading' => false,
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );

$image_class = 'card-img-top w-100 h-100 object-cover object-center';
?>
<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! empty( $module_args['icon'] ) ) : ?>
		<figure class="verflow-hidden icon-service" role="img" aria-hidden="true">
			<?php echo wp_get_attachment_image( $module_args['icon'], 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
		</figure>
	<?php endif; ?>
	<?php
	// Link.
	if ( ! empty( $module_args['heading'] ) ) :
		?>
		<div class="card-footer">
			<?php
			print_element( 'anchor', [
				'text'  => $module_args['heading']['title'],
				'href'  => $module_args['heading']['url'],
				'class' => [ 'stretched-link' ],
			] );
			?>
		</div>
	<?php endif; ?>
</div>
