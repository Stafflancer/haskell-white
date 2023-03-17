<?php
/**
 * ELEMENT: Button
 * Displays the theme button.
 *
 * Elements are analogous to 'Atoms' in Brad Frost's Atomic Design Methodology.
 *
 * @link https://atomicdesign.bradfrost.com/chapter-2/#atoms
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;
use function BopDesign\hwcpa\print_svg;

$element_defaults = [
	'class'         => [ 'acf-element', 'acf-element-button' ],
	'id'            => '',
	'title'         => false,
	'url'           => false,
	'target'        => false,
	'type'          => false,
	'style'         => 'fill', // fill | outline.
	'icon'          => [],
	'icon_position' => 'after', // before | after.
	'role'          => '',
	'aria'          => [
		'controls' => '',
		'disabled' => false,
		'expanded' => false,
		'label'    => false,
		'current'  => '',
	],
];

$element_args = get_formatted_args( $args, $element_defaults );
// Make sure element should render.
if ( $element_args['title'] || $element_args['icon'] ) :
	if ( ! empty( $element_args['icon'] ) ) :
		$element_args['class'][] = 'icon';
		$element_args['class'][] = 'icon-' . $element_args['icon_position'];
	endif;

	if ( ! empty( $element_args['style'] ) ) {
		$element_args['class'][] = 'btn-' . $element_args['style'];
	}

	// Set up element attributes.
	$element_atts = get_formatted_atts( [ 'id', 'href', 'target', 'class', 'type', 'aria', 'role' ], $element_args );
	?>
	<<?php echo $element_args['href'] ? 'a ' : 'button '; ?><?php echo $element_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
	if ( $element_args['title'] ) :
		echo $element_args['title'].'<svg xmlns="http://www.w3.org/2000/svg" width="5.949" height="9.899" viewBox="0 0 5.949 9.899">
  <path id="Vector_4" data-name="Vector 4" d="M0,0,3.535,3.535,0,7.07" transform="translate(1.414 1.414)" fill="none" stroke="#0077bf" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke-dasharray="0 0"/>
</svg>';
	endif;

	if ( ! empty( $element_args['icon'] ) ) :
		print_svg( $element_args['icon'] );
	endif;
	?>
	</<?php echo $element_args['href'] ? 'a' : 'button'; ?>>
<?php endif; ?>
