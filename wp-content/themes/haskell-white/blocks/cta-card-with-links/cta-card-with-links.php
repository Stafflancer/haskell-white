<?php
/**
 * BLOCK: CTA - Card with Links
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
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'cta-card-with-links-' . $block['id'],
	'class'    => [ 'acf-block', 'cta', 'cta-card-with-links', 'position-relative', 'overflow-hidden', $background_defaults['class'] ],
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
	'card_color',
	'heading',
	'content',
	'links',
	'buttons',
], $block['id'] );

$block_settings = get_formatted_args( $block_settings, $block_defaults );

$content_width_class = $block_settings['settings']['content_width'];

$container_class = join( ' ', [
	$block_settings['settings']['container_size'],
] );
$row_class    = join( ' ', [
	'row',
	'h-100',
] );
$column_class    = join( ' ', [
	'cta-card-with-links-inner',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color'],
	$content_width_class
] );
$color_picker = $block_content['card_color']['color_picker'];
if (!empty($color_picker)) {
	$card_background_class = ' has-' . esc_attr( $color_picker ) . '-background-color';
} 
if ( $block_content['card_color'] || $block_content['heading'] || $block_content['links']  || $block_content['buttons']) :
	?>
	<section <?php echo $block_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
		<?php print_background_options( $background_options ); ?>
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="<?php echo esc_attr( $row_class ); ?>">
				<div class="<?php echo esc_attr( $column_class ); ?>">
					<div class="row align-items-center justify-content-end <?php echo $card_background_class; ?>">
						<div class="col-12 col-md-8">
								<div class="white-block-card">
								<?php
								// Heading.
								if ( $block_content['heading'] ) :
									print_element( 'heading', [
										'text'  => $block_content['heading'],
										'level' => 2,
										'class' => [ 'text-center', 'text-md-start' ],
									] );
								endif;
								// Content.
								if ( $block_content['content'] ) :
									print_element(
										'content', [
										'content' => $block_content['content'],
										'class'   => 'content-block mb-0',
									] );
								endif; 				
								if(!empty($block_content['links'])) : 
										foreach($block_content['links'] as $links) :
											print_element( 'anchor', [
												'text'  => $links['link']['title'],
												'href'  => $links['link']['url'],
												'class' => [ 'link' ],
											] ); 
										endforeach;
								endif; 
								// Buttons.
								if ( $block_content['buttons'] ) :
									$buttons_group['class']   = '';
									$buttons_group['atagclass']   = '';
									$buttons_group['buttons'] = $block_content['buttons'];
									print_module(
										'buttons-group',
										$buttons_group
									); 
								endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
