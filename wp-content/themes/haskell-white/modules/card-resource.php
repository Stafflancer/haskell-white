<?php
/**
 * MODULE: Card - Resource
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

$module_defaults = [
	'class'   => [
		'acf-module',
		'acf-module-card-with-icons',
		'card hover-image-zoom',
		'position-relative d-flex flex-column overflow-hidden',
		'h-100',
		'text-break',
		'rounded rounded-4',
		'has-background-background-color',
	],
	'image'   => get_post_thumbnail_id(),
	'heading' => get_the_title(),
	'excerpt' => get_trimmed_excerpt( [ 'post' => get_the_ID(), ] ), // Set FALSE, to hide it.
	'button' => [
		'title'  => 'Read',
		'url'    => get_the_permalink(),
		'target' => false,
		'class'  => [ 'stretched-link' ],
	],
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );

$image_class = 'card-img-top w-100 h-100 object-cover object-center';
?>
<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<figure class="w-100 m-0 ratio ratio-16x9 overflow-hidden">
		<?php if ( ! empty( $module_args['image'] ) ) : ?>
			<?php echo wp_get_attachment_image( $module_args['image'], 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
		<?php else: ?>
			<img class="<?php echo esc_attr( $image_class ); ?>" src="<?php echo get_theme_file_uri( '/assets/images/placeholder-square.jpg' ); ?>" alt="Image Placeholder" width="764" height="764" aria-hidden="true">
		<?php endif; ?>
	</figure>
	<div class="card-body flex-grow-1 flex-shrink-1 p-3 p-lg-4 p-xl-5">
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
	<div class="card-footer d-flex justify-content-end pt-0 pe-4 pe-xl-5 pb-3 ps-4 ps-xl-5">
		<?php
		print_element( 'anchor', [
			'text'  => $module_args['button']['title'],
			'href'  => $module_args['button']['url'],
			'class' => $module_args['button']['class'],
		] );
		?>
	</div>
</div>
