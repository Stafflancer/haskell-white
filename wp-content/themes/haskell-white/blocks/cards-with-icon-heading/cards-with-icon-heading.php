<?php
/**
 * BLOCK: Cards with Icon & Heading
 *
 * @link    https://developer.wordpress.org/block-editor/
 *
 * @param array        $block      The block settings and attributes.
 * @param array        $content    The block inner HTML (empty).
 * @param bool         $is_preview True during AJAX preview.
 * @param (int|string) $post_id    The post ID this block is saved to.
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_acf_fields;
use function BopDesign\hwcpa\get_formatted_args;
use function BopDesign\hwcpa\setup_block_defaults;
use function BopDesign\hwcpa\print_background_options;
use function BopDesign\hwcpa\print_module;
use function BopDesign\hwcpa\print_element;

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
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'cards-with-icon-heading-' . $block['id'],
	'class'    => [ 'acf-block', 'cards-with-icon-heading', 'position-relative', 'overflow-hidden', $background_defaults['class'] ],
	'settings' => [
		'container_size' => 'container position-relative z-10',
		'align_text'     => 'text-start text-left',
		'align_content'  => 'justify-content-center is-position-top-center',
		'column_size'    => 'col-12',
	],
	'fields'   => [], // Fields passed via the print_block() function.
];

// Returns updated $block_defaults array with classes from Gutenberg and Background Options, or from the print_block() function.
// Returns formatted attributes as $block_atts array, $block_settings array.
[ $block_defaults, $block_atts, $block_settings, $background_options ] = setup_block_defaults( $block_args, $block_defaults, $block );

// Pull in the fields from ACF, if we've not pulled them in using print_block().
$block_content = ! empty( $block_defaults['fields'] ) ? $block_defaults['fields'] : get_acf_fields( [
	'section_header',
	'cards',
	'buttons_group',
], $block['id'] );

$block_settings = get_formatted_args( $block_settings, $block_defaults );

$content_width_class = $block_settings['settings']['content_width'];

$container_class = join( ' ', [
	$block_settings['settings']['container_size'],
] );
$row_class    = join( ' ', [
	'row',
	'mb-4',
] );
$column_class    = join( ' ', [
	'cards-with-icon-heading-inner',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color'],
	$content_width_class
] );
if(!empty($block_content['section_header']) || !empty($block_content['cards']) || !empty($block_content['buttons_group'])) : ?>
	<section <?php echo $block_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
		<?php print_background_options( $background_options ); ?>
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="<?php echo esc_attr( $row_class ); ?>">
				<div class="<?php echo esc_attr( $column_class ); ?>"><?php
					// Section Header.
					if ( ! empty( $block_content['section_header'] ) ) : 
						$block_content['section_header'];
						print_module(
							'section-header',
							$block_content['section_header']
						); 
					endif; 
					// Buttons.
					if ( $block_content['buttons_group'] ) :
						echo '<div class="col-12 col-md-4">';
								$buttons_group['class']   = '';
								$buttons_group['buttons'] = $block_content['buttons_group'];
								print_module(
									'buttons-group',
									$buttons_group
								);
						echo '</div>';
					endif; ?>
					<div class="cards"><?php
						// Cards.
						if ( ! empty( $block_content['cards'] ) ) :
							$animation_delay = 0;
							foreach ( $block_content['cards'] as $cards ):
								echo '<div class="col wow animate__animated animate__fadeIn" data-wow-delay="' . esc_attr( $animation_delay ) . 's">';
								print_module( 'card-with-icon-heading', $cards );
								echo '</div>';
								$animation_delay += 0.25;
							endforeach;
						endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section><?php
endif;