<?php
/**
 * MODULE: Cards - Wide Simple
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
		'acf-module-card-wide-simple',
		'card',
		'has-background-background-color',
	],
	'icon'    => false,
	'heading' => false,
	'button'    => false,
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );

$image_class = 'card-img-top w-100 h-100 object-cover object-center';
?>
<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="card-content">
		<?php if ( ! empty( $module_args['icon'] ) ) : ?>
			<figure class="overflow-hidden card-img mb-0" role="img" aria-hidden="true">
				<?php echo wp_get_attachment_image( $module_args['icon'], 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
			</figure>
		<?php endif; ?>
		<div class="card-body">
			<?php
			// Heading.
			if ( $module_args['heading'] ) :
				print_element( 'heading', [
					'text'  => $module_args['heading'],
					'level' => 3,
					'class' => [ 'card-title', 'h5', 'text-capitalize', 'mb-0' ],
				] );
			endif;
			?>
		</div>
	</div>
	<?php
	// Link.
	if ( ! empty( $module_args['button'] ) ) :
		?>
		<div class="card-footer">
			<?php
			print_element( 'anchor', [
				'text'  => $module_args['button']['title'],
				'href'  => $module_args['button']['url'],
				'class' => [ 'stretched-link', 'card-wide-simple-btn', 'has-cobalt-blue-background-color', 'has-background', 'has-base-color', 'has-text-color', 'has-fill-cobalt-blue-color',  'fill-color', 'btn-fill' ],
			] );
			?>
		</div>
	<?php endif; ?>
</div>
