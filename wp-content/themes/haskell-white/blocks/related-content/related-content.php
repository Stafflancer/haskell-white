<?php
/**
 * BLOCK: Related Content
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
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'related-content-' . $block['id'],
	'class'    => [ 'acf-block', 'featured-posts', 'related-content', 'position-relative', 'overflow-hidden', $background_defaults['class'] ],
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
	'content_type',
	'topic',
	'buttons_group',
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
	'related-content-inner',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color'],
	$content_width_class
] );
if(!empty($block_content['section_header']) || !empty($block_content['content_type']) || !empty($block_content['topic']) || !empty($block_content['buttons_group'])) : ?>
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
					<div class="swiper related-content-section">
						<div class="swiper-wrapper"><?php
							if(!empty($block_content['content_type']) || !empty($block_content['topic'])){ 
								$args = array(
									'post_type'=> 'insight',
									'post_status' => 'publish',
									'posts_per_page' => -1,
									'tax_query' => array(
										'relation' => 'OR',
							            array(
											'taxonomy'  => 'category',
											'field'     => 'term_id',
											'terms'     => $block_content['topic'],
							            ),
							            array(
											'taxonomy'  => 'content_type',
											'field'     => 'term_id',
											'terms'     => $block_content['content_type'],
							            )
						            ),
								);
								$result = new WP_Query( $args );
								if ( $result-> have_posts() ) {
									while ( $result->have_posts() ) { 
										$result->the_post(); 
										$image = get_post_thumbnail_id(); 
										$category = get_the_terms( get_the_ID(), 'category' );
										$content_type = get_the_terms( get_the_ID(), 'content_type' ); 
										$post_heading = get_the_title(); 
										$post_text = get_the_excerpt(); ?>
										<div class="common-row related-video-card swiper-slide hover-image-zoom">
											<figure class="overflow-hidden" aria-hidden="true" role="img"><?php 
												if ( ! empty( $image ) ) : 
													$image_class = 'card-img-top w-100 h-100 object-cover object-center';?>
													<?php echo wp_get_attachment_image( $image, 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
												<?php else: ?>
														<img class="<?php echo esc_attr( $image_class ); ?>" src="<?php echo get_theme_file_uri( '/assets/images/placeholder-square.jpg' ); ?>" alt="Image Placeholder" width="764" height="764" aria-hidden="true">
												<?php endif; ?>
											</figure>
											<div class="related-videos-flex">
												<div class="card-body"><?php
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
												<div class="card-footer d-flex justify-content-end pt-0 px-4 pb-4"><?php
													print_element( 'anchor', [
														'text'  => 'Watch',
														'href'  => get_the_permalink(),
														'class' => 'watch-btn common-link',
													] ); ?>
												</div>
											</div>
										</div><?php 
									}
									wp_reset_postdata(); 
								}	
							} 
							else{
								$terms = get_the_terms( get_the_ID(), 'content_type' );
								foreach($terms as $term){
									$args = array(
										'post_type'=> 'insight',
										'post_status' => 'publish',
										'posts_per_page' => -1,
										'tax_query' => array(
							                array(
												'taxonomy'  => 'content_type',
												'field'     => 'term_id',
												'terms'     => $term,
							                )
							            ),
									);
									$result = new WP_Query( $args );
									if ( $result-> have_posts() ){
										while ( $result->have_posts() ) {
										 	$result->the_post(); 
										 	$image = get_post_thumbnail_id(); 
											$category = get_the_terms( get_the_ID(), 'category' );
											$content_type = get_the_terms( get_the_ID(), 'content_type' ); 
											$post_heading = get_the_title(); 
											$post_text = get_the_excerpt(); ?>
											<div class="common-row related-video-card swiper-slide">
												<figure class="overflow-hidden" aria-hidden="true" role="img"><?php 
													if ( ! empty( $image ) ) : 
														$image_class = 'card-img-top w-100 h-100 object-cover object-center';?>
														<?php echo wp_get_attachment_image( $image, 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
													<?php else: ?>
															<img class="<?php echo esc_attr( $image_class ); ?>" src="<?php echo get_theme_file_uri( '/assets/images/placeholder-square.jpg' ); ?>" alt="Image Placeholder" width="764" height="764" aria-hidden="true">
													<?php endif; ?>
												</figure>
												<div class="card-body"><?php
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
												<div class="card-footer d-flex justify-content-end pt-0 px-4 pb-4"><?php
													print_element( 'anchor', [
														'text'  => 'Watch',
														'href'  => get_the_permalink(),
														'class' => 'watch-btn common-link',
													] ); ?>
												</div>
										</div><?php 
										}
										wp_reset_postdata(); 
									} 
								}
							} ?>
						</div>
						
					</div>
					<div class="swiper-button-next"></div>
	    			<div class="swiper-button-prev"></div>
				</div>
			</div>
		</div>
	</section><?php
endif;