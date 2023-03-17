<?php
/**
 * BLOCK: Search & Filter Shortcode
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
use function BopDesign\hwcpa\print_module;
use function BopDesign\hwcpa\print_element;
use function BopDesign\hwcpa\setup_block_defaults;
use function BopDesign\hwcpa\print_background_options;

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
	'id'              => ! empty( $block['anchor'] ) ? $block['anchor'] : 'latest-reads-' . $block['id'],
	'class'           => [ 'acf-block', 'search-filter-shortcode', 'search-filter-shortcode', 'position-relative', 'overflow-hidden' , $background_defaults['class'] ],
	'show_arrows'     => true,
	'show_pagination' => true,
	'settings'        => [
		'container_size' => 'container position-relative z-10',
		'align_text'     => 'text-start text-left',
		'align_content'  => 'align-items-start justify-content-start is-position-top-left',
		'column_size'    => 'col-12',
		'animation'      => '',
	],
	'fields'          => [], // Fields passed via the print_block() function.
];

// Returns updated $block_defaults array with classes from Gutenberg and Background Options, or from the print_block() function.
// Returns formatted attributes as $block_atts array, $block_settings array.
[ $block_defaults, $block_atts, $block_settings, $background_options ] = setup_block_defaults( $block_args, $block_defaults, $block );

// Pull in the fields from ACF, if we've not pulled them in using print_block().
$block_content = ! empty( $block_defaults['fields'] ) ? $block_defaults['fields'] : get_acf_fields( [
	'section_header',
	'search_shortcode',
	'results_shortcode',
], $block['id'] );

$block_settings = get_formatted_args( $block_settings, $block_defaults );

$content_width_class = $block_settings['settings']['content_width'];

$container_class = join( ' ', [
	$block_settings['settings']['container_size'],
] );
$row_class    = join( ' ', [
	'row',
] );
$column_class    = join( ' ', [
	'search-filter-shortcode',
	$block_settings['settings']['heading_color'],
	$block_settings['settings']['text_color'],
	$content_width_class
] );

$search_shortcode = $block_content['search_shortcode'];
$results_shortcode = $block_content['results_shortcode'];

if ( $block_content['section_header'] || $block_content['search_shortcode'] || $block_content['results_shortcode'] ) :
	?>
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
				</div>
				<div class="shortcode-box-outer" id="shortcode_result"><?php
	                if(!empty($search_shortcode)){ ?>
	                    <div class="shortcode-filter">
	                        <div class="shortcode-filter-inner">
	                        	<label>Filter by type:</label>
	                            <?php echo $search_shortcode; ?>
	                        </div>
	                    </div><?php
	                } 
	                if(!empty($results_shortcode)){ ?>
	                    <div class="shortcode-result">
	                        <?php echo $results_shortcode; ?>
	                    </div><?php
	                } ?>
            	</div>
			</div>
		</div>
	</section>
<?php endif; ?>