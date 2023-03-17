<?php
/**
 * ELEMENT: Anchor
 *
 * Elements are analogous to 'Atoms' in Brad Frost's Atomic Design Methodology.
 *
 * @link https://atomicdesign.bradfrost.com/chapter-2/#atoms
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;
$element_defaults = [
	'class'  => [ 'acf-element', 'acf-element-anchor' ],
	'text'   => false,
	'href'   => false,
	'target' => false,
];

$element_args = get_formatted_args( $args, $element_defaults );

// Make sure element should render.
if ( $element_args['href'] && $element_args['text'] ) :
	// Set up element attributes.
	$element_atts = get_formatted_atts( [ 'href', 'target', 'class' ], $element_args );
	?>
	<a <?php echo $element_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><span><?php echo esc_html( $element_args['text'] ); ?><svg xmlns="http://www.w3.org/2000/svg" width="5.949" height="9.899" viewBox="0 0 5.949 9.899"><path id="Vector_4" data-name="Vector 4" d="M0,0,3.535,3.535,0,7.07" transform="translate(1.414 1.414)" fill="none" stroke="#0077bf" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke-dasharray="0 0"/></svg></span></a>
<?php endif; ?>
