<?php
/**
 * BLOCK: Offic
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
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'office-' . $block['id'],
	'class'    => [ 'acf-block', 'office',  'position-relative', 'overflow-hidden', $background_defaults['class'] ],
	'settings' => [
		'container_size' => 'container position-relative z-10',
		'align_text'     => 'text-start text-left',
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
	'location_map',
	'title',
	'address',
	'phone',
	'fax',
	'email',
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
	'office-inner',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color'],
	$content_width_class
] );
if ( $block_content['section_header'] || $block_content['offices'] ) :
	?>
	<section <?php echo $block_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
		<?php print_background_options( $background_options ); ?>
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="<?php echo esc_attr( $row_class ); ?>">
				<div class="<?php echo esc_attr( $column_class ); ?>">
					<div class="row align-items-center justify-content-between">
						<div class="col-12 col-md-12">
							<div class="offices-heading-block">
								<?php
									// Section Header.
									if ( ! empty( $block_content['section_header'] ) ) : 
										$block_content['section_header'];
										print_module(
											'section-header',
											$block_content['section_header']
										); 
									endif; ?>
									<div class="offices-row office-row-single"><?php
										$location_map =	$block_content['location_map'];
										$title = $block_content['title'];
										$address = $block_content['address'];
										$phone = $block_content['phone']; ?>
										<div class="offices-repeater-item"><?php
											if(!empty($location_map) && !empty($location_map['url'])){ ?>
												<div class="offices-repeater-image">
													<img src="<?php echo esc_url($location_map['url']); ?>" alt="<?php echo esc_attr($location_map['alt']); ?>">
												</div><?php
											} ?>
											<div class="headquarters-info"><?php 
												if(!empty($title)){ ?>
													<h3><a href=""><?php echo $title; ?> <svg xmlns="http://www.w3.org/2000/svg" width="5.949" height="9.899" viewBox="0 0 5.949 9.899">
															  <path id="Vector_4" data-name="Vector 4" d="M0,0,3.535,3.535,0,7.07" transform="translate(1.414 1.414)" fill="none" stroke="#0077bf" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke-dasharray="0 0"></path>
															</svg></a></h3> <div class="fixed-contact-block"><?php
												} 
												if(!empty($address)){ ?>
													<div class="headquarters">
														<?php echo $address; ?>
													</div><?php
												} 
												if(!empty($phone) && !empty($phone['url'])){ ?>
													<a href="<?php echo esc_url($phone['url']); ?>"><?php echo $phone['title']; ?></a></div><?php
												} ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
