<?php
/**
 * BLOCK: Author Created Blog
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
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'author-created-blog-' . $block['id'],
	'class'    => [ 'acf-block', 'testimonials', 'author-created-blog', 'position-relative', 'overflow-hidden', $background_defaults['class'] ],
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
	'select_created_blog',
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
	'author-created-blog-inner',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color'],
	$content_width_class
] );
if(!empty($block_content['section_header']) || !empty($block_content['select_created_blog'])) : ?>
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
					endif; ?>
					<div class="author-created-blog-main"><?php
						if ( ! empty( $block_content['select_created_blog'] ) ) :
							foreach ( $block_content['select_created_blog'] as $select_created_blog ):
								$image = get_post_thumbnail_id($select_created_blog); 
								$category = get_the_terms( $select_created_blog, 'category' );
								$content_type = get_the_terms( $select_created_blog, 'content_type' ); 
								$post_heading = get_the_title($select_created_blog); 
								$post_text = get_the_excerpt($select_created_blog); ?>
								<div class="common-row author-blog ">
									<div class="flex-space-between">
										<figure class="overflow-hidden hover-image-zoom mb-0" aria-hidden="true" role="img"><?php 
											if ( ! empty( $image ) ) :
											$image_class = 'card-img-top w-100 h-100 object-cover object-center';  ?>
												<?php echo wp_get_attachment_image( $image, 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
											<?php else: ?>
												<img class="<?php echo esc_attr( $image_class ); ?>" src="<?php echo get_theme_file_uri( '/assets/images/placeholder-square.jpg' ); ?>" alt="Image Placeholder" width="764" height="764" aria-hidden="true">
											<?php endif; ?>
										</figure>
										<div class="card-body"><div class="flex-space"><?php
											if(!empty($category) || !empty($content_type)){ 
												$category = join(' | ', wp_list_pluck($category, 'name'));
												$content_type = join(' | ', wp_list_pluck($content_type, 'name'));
												$category_devide = ""; 
												if(!empty($category) && !empty($content_type)){ 
													$category_devide = ' | ';
												} ?>
												<label><?php echo $category.$category_devide.$content_type; ?></label><?php
											} 
											// Heading.
											if ( $post_heading ) :
												print_element( 'heading', [
													'text'  => $post_heading,
													'level' => 4,
													'class' => [ 'card-title', 'h4' ],
												] );
											endif;

											// Content.
											if ( $post_text ) :
												print_element( 'content', [
													'content' => $post_text,
													'class'   => [ 'card-text', 'post-excerpt', 'm-0' ],
												] );
											endif; ?>
											</div>
										</div>
									</div>

									<div class="card-footer"><?php
										print_element( 'anchor', [
											'text'  => 'Read More',
											'href'  => get_the_permalink($select_created_blog),
											'class' => 'watch-btn common-link',
										] ); ?>
									</div>
									
								</div><?php 
							endforeach;
						endif; ?>
					</div><?php
					// Buttons.
					if ( $block_content['buttons'] ) :
						echo '<div class="col-12 col-md-12 text-center">';
								$buttons_group['class']   = 'read-all-post';
								$buttons_group['buttons'] = $block_content['buttons'];
								print_module(
									'buttons-group',
									$buttons_group
								);
						echo '</div></div>';
					endif; ?>
				</div>
			</div>
		</div>
	</section><?php
endif;