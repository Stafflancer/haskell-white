<?php
/**
 * BLOCK: CTA - Promo Bar
 *
 * @param array        $block      The block settings and attributes.
 * @param string       $content    The block inner HTML (empty).
 * @param bool         $is_preview True during AJAX preview.
 * @param (int|string) $post_id    The post ID this block is saved to.
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_acf_fields;
use function BopDesign\hwcpa\get_formatted_args;
use function BopDesign\hwcpa\setup_block_defaults;
use function BopDesign\hwcpa\print_background_options;
use function BopDesign\hwcpa\print_element;
use function BopDesign\hwcpa\print_module;

$background_options = get_field('background_options');
$background_defaults = [
	'class' => 'background-options',
];
if ( 'none' === $background_options['background_options']['background_type'] ) {
		$background_defaults['class'] .= ' no-background ';
}
if ( 'color' === $background_options['background_options']['background_type'] && $background_options['background_options']['background_color']['color_picker'] ) {

	$background_color        = $background_options['background_options']['background_color']['color_picker'];
	$background_defaults['class'] .= ' has-background color-as-background has-' . esc_attr( $background_color ) . '-background-color ';
}
if ( 'gradient' === $background_options['background_options']['background_type'] && $background_options['background_options']['gradient_color'] ) {

	$background_color        = $background_options['background_options']['gradient_color'];
	$background_defaults['class'] .= ' has-background gradient-as-background has-' . esc_attr( $background_color ) . '-gradient ';
}
$block_args     = isset( $args ) ? $args : '';
$block_defaults = [
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'promo-bar-' . $block['id'],
	'class'    => [ 'acf-block', 'cta', 'cta-promo-bar', 'position-relative', 'overflow-hidden', $background_defaults['class'] ],
	'settings' => [
		'container_size' => 'container position-relative z-10',
		'column_size'    => 'col-12',
	],
	'fields'   => [], // Fields passed via the print_block() function.
];

// Returns updated $block_defaults array with classes from Gutenberg and Background Options, or from the print_block() function.
// Returns formatted attributes as $block_atts array, $block_settings array.
[ $block_defaults, $block_atts, $block_settings, $background_options ] = setup_block_defaults( $block_args, $block_defaults, $block );

// Pull in the fields from ACF, if we've not pulled them in using print_block().
$block_content = ! empty( $block_defaults['fields'] ) ? $block_defaults['fields'] : get_acf_fields( [
	'heading',
	'buttons',
], $block['id'] );

$block_settings = get_formatted_args( $block_settings, $block_defaults );

$content_width_class = $block_settings['settings']['content_width'];

$container_class = join( ' ', [
	$block_settings['settings']['container_size'],
	'content-width-'.$content_width_class
] );
$row_class    = join( ' ', [
	'row',
	'h-100',
] );
$column_class    = join( ' ', [
	'cta-promo-bar-inner',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color']
] );
if ( $block_content['heading'] || $block_content['buttons'] ) :
	?>
	<section <?php echo $block_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
		<?php print_background_options( $background_options ); ?>
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="<?php echo esc_attr( $row_class ); ?>">
				<div class="<?php echo esc_attr( $column_class ); ?>">
					<div class="row align-items-center justify-content-between">
						<div class="col-12 col-md-8 col-lg-10">
							<?php
							// Heading.
							if ( $block_content['heading'] ) :
								print_element( 'heading', [
									'text'  => $block_content['heading'],
									'level' => 4,
									'class' => [ 'text-center', 'text-md-start', 'mb-0', 'fw-500' ],
								] );
							endif;
							?>
						</div>
						<div class="col-12 col-md-4 col-lg-2">
							<?php
							// Buttons.
							if ( $block_content['buttons'] ) :
								$buttons_group['class']   = 'learn-more';
								$buttons_group['atagclass']   = 'stretched-link';
								$buttons_group['buttons'] = $block_content['buttons'];
								print_module(
									'buttons-group',
									$buttons_group
								);
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
