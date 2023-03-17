<?php
/**
 * BLOCK: Slider - Featured Posts
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
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'slider-featured-posts-' . $block['id'],
	'class'    => [ 'acf-block', 'featured-posts', 'slider-featured-posts', 'position-relative', 'overflow-hidden', $background_defaults['class'] ],
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
	'featured_posts',
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
	'slider-featured-posts-inner',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color'],
	$content_width_class
] );

if(!empty($block_content['section_header']) || !empty($block_content['featured_posts']) || !empty($block_content['buttons_group'])) : ?>
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
					<div class="swiper featured-posts-slider">
						<div class="swiper-wrapper"><?php
							if ( ! empty( $block_content['featured_posts'] ) ) :
								global $post; 
								foreach ( $block_content['featured_posts'] as $featured_posts ):
									setup_postdata( $featured_posts ); 
									$image = get_post_thumbnail_id($featured_posts->ID); 
									$category = get_the_terms( $featured_posts->ID, 'category' );
									$image_class = 'card-img-top w-100 h-100 object-cover object-center'; ?>
									<div class="featured-posts-card swiper-slide">
										<div class="featured-post-image"><?php
											if ( ! empty( $image ) ) : ?>
												<?php echo wp_get_attachment_image( $image, 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
											<?php else: ?>
												<img class="<?php echo esc_attr( $image_class ); ?>" src="<?php echo get_theme_file_uri( '/assets/images/placeholder-square.jpg' ); ?>" alt="Image Placeholder" width="764" height="764" aria-hidden="true">
											<?php endif; ?>
										</div>
										<div class="featured-posts-content">
											<h5>FEATURED</h5><?php
											if(!empty($category)){ 
												$category = join(' | ', wp_list_pluck($category, 'name')); ?>
												<label><?php echo $category; ?></label><?php
											} ?>
											<h3><?php echo get_the_title($featured_posts->ID) ?></h3>
											<div class="content">
												<?php echo get_the_excerpt($featured_posts->ID) ?>
											</div>
											<div class="card-footer read-more-btn">
												<a href="<?php echo get_the_permalink($featured_posts->ID); ?>" class="btn has-cobalt-blue-background-color has-background  has-base-color has-text-color has-fill-cobalt-blue-color fill-color btn-fill">Watch  
													<svg xmlns="http://www.w3.org/2000/svg" width="5.949" height="9.899" viewBox="0 0 5.949 9.899">
													  <path id="Vector_4" data-name="Vector 4" d="M0,0,3.535,3.535,0,7.07" transform="translate(1.414 1.414)" fill="none" stroke="#0077bf" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke-dasharray="0 0"></path>
													</svg>
												</a>
											</div>									
										</div>
									</div><?php
								endforeach;
								wp_reset_postdata();
							endif; ?>
						</div>
						<div class="swiper-button-next"></div>
    					<div class="swiper-button-prev"></div>
						<div class="swiper-scrollbar"></div>
					</div>
				</div>
			</div>
		</div>
	</section><?php
endif;