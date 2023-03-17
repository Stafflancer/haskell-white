<?php
/**
 * MODULE: Section Header
 *
 * Modules are analogous to 'Molecules' in Brad Frost's Atomic Design Methodology.
 *
 * @link https://atomicdesign.bradfrost.com/chapter-2/#molecules
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_element;
use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;

$module_defaults = [
	'class'      => [ 'acf-module', 'acf-module-section-header' ],
	'section_header_style' => false,
	'heading'    => false,
	'heading_tag'    => false,
	'content'    => false,
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );

?>
<div class="<?php echo $module_args['section_header_style']; ?>">
	<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php
		// Heading.
		if ( $module_args['heading'] ) :
			print_element(
				'heading',
				[
					'text'  => $module_args['heading'],
					'level' => $module_args['heading_tag'],
				]
			);
		endif;

		// Content.
		if ( $module_args['content'] ) :
			print_element(
				'content',
				[
					'content' => $module_args['content'],
				]
			);
		endif;
		?>
	</div>
</div>