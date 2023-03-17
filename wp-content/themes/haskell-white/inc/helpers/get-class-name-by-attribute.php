<?php
/**
 * Returns the class value of the attribute.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

function get_class_name_by_attribute( string $attr, $value = '' ) {
	$allowed_attributes = [
		'container_size' => 'container',    // container | container-fluid
		'content_width'    => 'full',         
		'heading_color'      => 'base',         
		'text_color'      => 'base',        
	];

	if ( ! array_key_exists( $attr, $allowed_attributes ) ) {
		return '';
	}
	$class_name = '';
	
	switch ( $attr ) {
		case 'heading_color':
			switch ( $value ) {
			case 'base':
				$class_name = 'base-heading-color';
				break;
			case 'contrast':
				$class_name = 'contrast-heading-color';
			break;
			case 'primary':
				$class_name = 'primary-heading-color';
			break;
		}
		break;

		case 'text_color':
			switch ( $value ) {
			case 'base':
				$class_name = 'base-text-color';
				break;
			case 'contrast':
				$class_name = 'contrast-text-color';
			break;
			case 'primary':
				$class_name = 'primary-text-color';
			break;
		}
		break;

		case 'container_size':
			switch ( $value ) {
			case 'container':
				$class_name = 'container ';
				break;
			case 'full':
				$class_name = 'full';
			break;
		}
		break;

		case 'content_width':
			switch ( $value ) {
			case '6':
				$class_name = '50 ';
				break;
			case '7':
				$class_name = '58';
			break;

			case '8':
				$class_name = '66';
			break;

			case '9':
				$class_name = '75';
			break;

			case '10':
				$class_name = '83';
			break;

			case '11':
				$class_name = '92';
			break;

			case '12':
				$class_name = '100';
			break;
		}
		break;
	}

	return $class_name;
}
