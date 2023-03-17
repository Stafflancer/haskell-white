<?php
/**
 * BLOCK: Fifty Fifty
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
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'fifty-fifty-' . $block['id'],
	'class'    => [ 'acf-block', 'fifty-fifty', 'position-relative', 'overflow-hidden', $background_defaults['class'] ],
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
	'section_header',
	'content_layout',
	'left_side_image',
	'content_primary',
	'right_side_image',
	'content_secondary',
	'text_content',
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
	'fifty-fifty-inner',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color'],
	$content_width_class
] );
$content_layout = $block_content['content_layout'];
$left_side_image = $block_content['left_side_image'];
$content_primary = $block_content['content_primary'];
$right_side_image = $block_content['right_side_image'];
$content_secondary = $block_content['content_secondary'];
$text_content = $block_content['text_content'];
if ( $block_content['section_header'] || $block_content['content_layout'] || $block_content['left_side_image'] || $block_content['content_primary'] || $block_content['right_side_image'] || $block_content['content_secondary'] || $block_content['text_content'] ) :
	?>
	<section <?php echo $block_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
		<?php print_background_options( $background_options ); ?>
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="<?php echo esc_attr( $row_class ); ?>">
				<div class="<?php echo esc_attr( $column_class ); ?>">
					<div class="row align-items-center justify-content-between"><?php
						// Section Header.
						if ( ! empty( $block_content['section_header'] ) ) : 
							$block_content['section_header'];
							print_module(
								'section-header',
								$block_content['section_header']
							); 
						endif; ?>
					</div>
					<div class="fifty-fifty-content"><?php
						if($content_layout == 'text-text'){ ?>
							<div class="fifty-text-text"><?php
								if(!empty($text_content)){
									foreach($text_content as $text_content){
										$heading = $text_content['text_heading'];
										$content = $text_content['text_content'];
										$button = $text_content['text_buttons']; ?>
										<div class="fifty-text"><?php
											if ( $heading ) :
												print_element( 'heading', [
													'text'  => $heading,
													'level' => 3,
													'class' => [ 'text-md-start' ],
												] );
											endif; 
											// Content.
											if ( $content ) :
												print_element(
													'content', [
													'content' => $content,
													'class'   => 'mt-3 mb-3',
												] );
											endif; 
											// Buttons.
											if ( !empty($button) ) :
												echo '<div class="col-12 col-md-4">';
														$buttons_group['class']   = 'ms-0 ms-sm-2 ms-lg-3';
														$buttons_group['buttons'] = $button;
														print_module(
															'buttons-group',
															$buttons_group
														);
												echo '</div>';
											endif; ?>
										</div><?php 
									}
								} ?>
							</div><?php
						} 
						if($content_layout == 'media-text'){ ?>
							<div class="fifty-media-text experties"><?php 
								if ( ! empty( $left_side_image ) ) { 
									$image_class = 'card-img-top w-100 h-100 object-cover object-center'; ?>
									<figure class="discussion-img" role="img" aria-hidden="true">
										<?php echo wp_get_attachment_image( $left_side_image, 'large', array( 'class' => esc_attr( $image_class ) ) ); ?>
									</figure><?php
								}
								if(!empty($content_primary)){
									foreach($content_primary as $content_primary){
										$heading = $content_primary['heading'];
										$content = $content_primary['content'];
										$button = $content_primary['content_primary_buttons']; ?>
										<div class="fifty-text"><?php
											if ( $heading ) :
												print_element( 'heading', [
													'text'  => $heading,
													'level' => 2,
													'class' => [ 'text-md-start' ],
												] );
											endif; 
											// Content.
											if ( $content ) :
												print_element(
													'content', [
													'content' => $content,
													'class'   => 'experties-content',
												] );
											endif; 
											// Buttons.
											if ( !empty($button) ) :
												echo '<div class="col-12 col-md-4">';
														$buttons_group['class']   = 'ms-0 ms-sm-2 ms-lg-3';
														$buttons_group['buttons'] = $button;
														print_module(
															'buttons-group',
															$buttons_group
														);
												echo '</div>';
											endif; ?>
										</div><?php 
									}
								} ?>
							</div><?php
						}
						if($content_layout == 'text-media'){ ?>
							<div class="fifty-media-text"><?php 
								if(!empty($content_secondary)){
									foreach($content_secondary as $content_secondary){
										$heading = $content_secondary['heading'];
										$content = $content_secondary['content'];
										$button = $content_secondary['buttons']; ?>
										<div class="fifty-text"><?php
											if ( $heading ) :
												print_element( 'heading', [
													'text'  => $heading,
													'level' => 3,
													'class' => [ 'text-md-start' ],
												] );
											endif; 
											// Content.
											if ( $content ) :
												print_element(
													'content', [
													'content' => $content,
													'class'   => 'mt-3 mb-3',
												] );
											endif; 
											// Buttons.
											if ( !empty($button) ) :
												echo '<div class="col-12 col-md-4">';
														$buttons_group['class']   = 'ms-0 ms-sm-2 ms-lg-3';
														$buttons_group['buttons'] = $button;
														print_module(
															'buttons-group',
															$buttons_group
														);
												echo '</div>';
											endif; ?>
										</div><?php 
									}
								} 
								if ( ! empty( $right_side_image ) ) { 
									$image_class = 'card-img-top w-100 h-100 object-cover object-center'; ?>
									<figure class="discussion-img" role="img" aria-hidden="true">
										<?php echo wp_get_attachment_image( $right_side_image, 'large', array( 'class' => esc_attr( $image_class ) ) ); ?>
									</figure><?php
								} ?>
							</div><?php
						}  ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
