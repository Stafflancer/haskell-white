<?php
/**
 * MODULE: Card - Post
 * Template part for displaying reusable card in blocks and archives.
 *
 * Modules are analogous to 'Molecules' in Brad Frost's Atomic Design Methodology.
 *
 * @link    https://atomicdesign.bradfrost.com/chapter-2/#molecules
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_element;
use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;
use function BopDesign\hwcpa\get_trimmed_excerpt;

$post_type = get_post_type();
if($post_type == 'insight'){
	$button_text = 'Watch';
}
else{
	$button_text = 'Read More';
}

$category = get_the_terms( get_the_ID(), 'category' );
$content_type = get_the_terms( get_the_ID(), 'content_type' );

$module_defaults = [
	'class'   => [
		'acf-module',
		'acf-module-card-with-icons',
		'card hover-image-zoom',
		'position-relative overflow-hidden',
		'',
		'',
		'',
		'has-background-background-color',
	],
	'image'   => get_post_thumbnail_id(),
	'heading' => get_the_title(),
	'excerpt' => get_trimmed_excerpt( [ 'post' => get_the_ID(), ] ), // Set value to FALSE to disable.
	'button'  => [
		'url'    => get_the_permalink(),
		'class'  => [ 'stretched-link' ],
		'title'  => $button_text,
		'target' => false,
	],
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );

$image_class = 'card-img-top w-100 h-100 object-cover object-center';
?>
<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="swiper-div">
		<div class="flex-block">
			<figure class="overflow-hidden" aria-hidden="true" role="img">
				<?php if ( ! empty( $module_args['image'] ) ) : ?>
					<?php echo wp_get_attachment_image( $module_args['image'], 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
				<?php else: ?>
					<img class="<?php echo esc_attr( $image_class ); ?>" src="<?php echo get_theme_file_uri( '/assets/images/placeholder-square.jpg' ); ?>" alt="Image Placeholder" width="764" height="764" aria-hidden="true">
				<?php endif; ?>
			</figure>
		
		<div class="card-body news-card-author"><?php
			if(!empty($category) || !empty($content_type)){ 
				$category = join(' | ', wp_list_pluck($category, 'name'));
				$content_type = join(' | ', wp_list_pluck($content_type, 'name'));
				$category_devide = ""; 
				if(!empty($category) && !empty($content_type)){ 
					$category_devide = ' | ';
				} ?>
				<label><?php echo $category.$category_devide.$content_type; ?></label><?php
			} ?>
			<?php
			// Heading.
			if ( $module_args['heading'] ) :
				print_element( 'heading', [
					'text'  => $module_args['heading'],
					'level' => 4,
					'class' => [ 'card-title', 'h4' ],
				] );
			endif;

			// Content.
			if ( $module_args['excerpt'] ) :
				print_element( 'content', [
					'content' => $module_args['excerpt'],
					'class'   => [ 'card-text', 'post-excerpt', 'm-0' ],
				] );
			endif;
			?>
			</div>
	
		</div>
		<div class="read-more card-footer">
			<?php
			print_element( 'anchor', [
				'text'  => $module_args['button']['title'],
				'href'  => $module_args['button']['url'],
				'class' => $module_args['button']['class'],
			] );
			?>
		</div>
	</div>
</div>
