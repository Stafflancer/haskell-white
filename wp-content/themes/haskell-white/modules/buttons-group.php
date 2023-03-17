<?php
/**
 * MODULE: Buttons Group
 * Display one or multiple buttons.
 *
 * @package hwcpa
 */


use function BopDesign\hwcpa\print_element;
use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;

$module_defaults = [
	'class'             => [ 'acf-module', 'acf-module-buttons-group', 'd-block' ],
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );
$module_atts_atagclass = get_formatted_atts( [ 'atagclass' ], $module_args );
$remove_element = array(
	'atagclass', 
	'="', 
	'"',
);
$atagclass = str_replace($remove_element, '', $module_atts_atagclass); 
if ( ! empty( $module_args['buttons'] ) && is_array( $module_args['buttons'] ) ) :
	?>
	<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
		<?php
		// Loop through our buttons array.
		$i = 0;
		foreach ( $module_args['buttons'] as $buttons ) :
			if(!empty($buttons)) :
				foreach ( $buttons as $button ) :

					$button_data = $button['button'];
					// Button.
					if ( ! empty( $button_data ) ) :
					 
							$button_style  = $button['button_style'];
							$button_text_color_theme  = $button['button_text_color_theme']['color_picker'];
							$button_color  = $button['button_color']['color_picker'];
							if(!empty($button_color)){
								$button_data['class'] = ' has-' . esc_attr( $button_color ) . '-background-color has-background ';
							}
							if ( ! empty( $button_text_color_theme ) ) {
								$button_data['class'] .= ' has-' . esc_attr( $button_text_color_theme ) . '-color has-text-color ';
							}
							if ( ! empty( $button_color ) ) {
								switch ( $button_style ) {
									case 'outline':
										$button_data['class'] .= ' has-outline-' . esc_attr( $button_color ) . '-color outline-color';
										break;
									case 'fill':
									default:
										$button_data['class'] .= ' has-fill-' . esc_attr( $button_color ) . '-color fill-color';
										break;
								}
							}
							$button_data['class'] .= ' '.$atagclass; 
							print_element( 'button', $button_data );
				
						$i++;
					endif;
				endforeach;
			endif;
		endforeach;
		?>
	</div>
<?php endif;
