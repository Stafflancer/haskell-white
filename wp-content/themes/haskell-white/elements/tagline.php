<?php
/**
 * ELEMENT: Tagline
 *
 * Elements are analogous to 'Atoms' in Brad Frost's Atomic Design Methodology.
 *
 * @link    https://atomicdesign.bradfrost.com/chapter-2/#atoms
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;

$element_defaults = [
	'class' => [ 'acf-element', 'acf-element-tagline' ],
	'text'  => false,
];

$element_args = get_formatted_args( $args, $element_defaults );

// Make sure element should render.
if ( $element_args['text'] ) :
	// Set up element attributes.
	$element_atts = get_formatted_atts( [ 'class' ], $element_args );
	?>
	<p <?php echo $element_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>><?php echo esc_html( $element_args['text'] ); ?></p>
<?php endif; ?>
