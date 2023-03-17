<?php
/**
 * MODULE: Column with Icon
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
	'class'             => [
		'acf-module',
		'acf-module-column-with-icon',
		'd-flex',
		'py-3 py-lg-4 py-xl-5',
		'text-break',
	],
	'icon'              => false,
	'icon_position'     => 'top', // Available values top | left.
	'content_alignment' => 'left', // Available values: left | center. Option visible when icon position is 'top'.
	'heading'           => false,
	'content'           => false,
	'link'              => false,
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set image default classes.
$figure_class  = 'ratio ratio-1x1 overflow-hidden';
$image_class   = 'w-100 h-100 object-cover object-center';
$content_class = 'column-content';

if ( 'top' === $module_args['icon_position'] ) {
	$module_args['class'][] = 'flex-column';

	if ( 'center' === $module_args['content_alignment'] ) {
		$module_args['class'][] = 'text-center';
		$figure_class           .= ' mx-auto';
	}
} else {
	$module_args['class'][] = 'flex-row align-items-start';
	$figure_class           .= ' flex-shrink-0';
	$content_class          .= ' flex-grow-1 ms-3';
}

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );
?>
<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! empty( $module_args['icon'] ) ) : ?>
		<figure class="<?php echo esc_attr( $figure_class ); ?>" role="img" aria-hidden="true">
			<?php echo wp_get_attachment_image( $module_args['icon'], 'thumbnail', array( 'class' => esc_attr( $image_class ) ) ); ?>
		</figure>
	<?php endif; ?>
	<div class="<?php echo esc_attr( $content_class ); ?>">
		<?php
		// Heading.
		if ( $module_args['heading'] ) :
			print_element( 'heading', [
				'text'  => $module_args['heading'],
				'level' => 4,
				'class' => [ 'column-title', 'h5' ],
			] );
		endif;

		// Content.
		if ( $module_args['content'] ) :
			print_element( 'content', [
				'content' => $module_args['content'],
			] );
		endif;

		// Link.
		if ( ! empty( $module_args['link'] ) ) :
			print_element( 'anchor', [
				'text'  => $module_args['link']['title'],
				'href'  => $module_args['link']['url'],
				'class' => [ 'text-right' ],
			] );
		endif;
		?>
	</div>
</div>
