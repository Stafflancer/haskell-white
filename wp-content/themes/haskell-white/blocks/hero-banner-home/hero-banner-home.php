<?php
/**
 * BLOCK: Hero Banner - Home
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
	'id'       => ! empty( $block['anchor'] ) ? $block['anchor'] : 'hero-home-' . $block['id'],
	'class'    => [ 'acf-block', 'hero-banner', 'hero-banner-home', 'position-relative', 'overflow-hidden',$background_defaults['class'] ],
	'settings' => [
		'container_size' => 'container position-relative z-10',
		'align_text'     => 'text-start text-left',
		'align_content'  => 'align-items-start justify-content-start is-position-top-left',
		'column_size'    => 'col-12',
	],
	'fields'   => [], // Fields passed via the print_block() function.
];

// Returns updated $block_defaults array with classes from Gutenberg and Background Options, or from the print_block() function.
// Returns formatted attributes as $block_atts array, $block_settings array.
[ $block_defaults, $block_atts, $block_settings, $background_options ] = setup_block_defaults( $block_args, $block_defaults, $block );

// Pull in the fields from ACF, if we've not pulled them in using print_block().
$block_content = ! empty( $block_defaults['fields'] ) ? $block_defaults['fields'] : get_acf_fields( [
	'tagline',
	'heading',
	'content',
	'buttons',
	'media',
], $block['id'] );

$block_settings = get_formatted_args( $block_settings, $block_defaults );

// Extract animation class in case we want to apply to a single element.
$content_width_class = $block_settings['settings']['content_width'];
$container_class = join( ' ', [
	$block_settings['settings']['container_size'],
	'd-flex h-100',
	'content-width-'.$content_width_class
] );
$row_class    = join( ' ', [
	'row',
	'h-100',
] );
$column_class    = join( ' ', [
	'hero-content',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color']
] );

$media = $block_content['media'];
$media_type = $media['media_type'];
$image = $media['image'];
$video = $media['video'];



if ( $block_content['heading'] || $block_content['buttons'] || $block_content['content'] || $block_content['media']) :
	?>
	<section <?php echo $block_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
		<?php print_background_options( $background_options ); ?>
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="<?php echo esc_attr( $row_class ); ?>">
				<div class="<?php echo esc_attr( $column_class ); ?>">
					<div class="hero-banner-left-block">
						<?php	
							// Heading.
							if ( $block_content['heading'] ) :
								print_element(
									'heading',
									[
										'text'  => $block_content['heading'],
										'level' => 1,
										'class'   => 'green-bar banner-heading',
									]
								);
							endif;

							// Content.
							if ( $block_content['content'] ) :
								print_element(
									'content', [
									'content' => $block_content['content'],
									'class'   => 'banner-content-block',
								] );
							endif;

							// Buttons.
							if ( $block_content['buttons'] ) :
								$buttons_group['class']   = '';
								$buttons_group['buttons'] = $block_content['buttons'];
								print_module(
									'buttons-group',
									$buttons_group
								);
							endif; ?>
						</div><?php

					if(!empty($media)){
						if($media_type == 'video' && !empty($video)){
							// Use preg_match to find iframe src.
				            preg_match('/src="(.+?)"/', $video, $matches);
				            $src = $matches[1];
				            // Add extra parameters to src and replace HTML.
				            $params = array(
								'controls'  => 1,
								'muted'        => 0,
								'hd'        => 1,
								'autoplay' => 0,
								'loop' => 0,
				            );
				            $new_src = add_query_arg($params, $src);
				            $iframe = str_replace($src, $new_src, $video);

				            // Add extra attributes to iframe HTML.
				            $attributes = 'frameborder="0"';
				            $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);
				            echo '<div class="video">'.$iframe.'</div>';
				        }
				        else{
				        	if(!empty($image) && !empty($image['url'])){ 
				        		echo '<div class="image"><img src="'.esc_url($image['url']).'" alt="'.esc_attr($image['alt']).'"></div>';
				        	}
				        }
			       	} ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
