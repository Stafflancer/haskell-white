<?php
/**
 * Get the theme colors for this project. Set these first in the theme.json and/or Sass partial,
 * then migrate them over here.
 *
 * @return array The array of our color names and hex values.
 */

namespace BopDesign\hwcpa;

function get_theme_colors() {
	$theme_colors    = [];
	$theme_json_file = get_theme_file_path( 'theme.json' );

	if ( file_exists( $theme_json_file ) ) {
		$theme_json_contents = file_get_contents( $theme_json_file );
		$theme_json_data     = json_decode( $theme_json_contents, true );

		if ( ! empty( $theme_json_data ) && ! empty( $theme_json_data['settings']['color']['palette'] ) ) {
			foreach ( $theme_json_data['settings']['color']['palette'] as $color ) {
				$color_name  = esc_html__( $color['name'], THEME_TEXT_DOMAIN );
				$color_value = $color['color'];

				$theme_colors[ $color_name ] = $color_value;
			}
		}

		if ( ! empty( $theme_colors ) ) {
			return $theme_colors;
		}
	}

	// If we are not using theme.json file, then setup theme colors here.
	return [
		esc_html__( 'Base', THEME_TEXT_DOMAIN )    => '#fff',
		esc_html__( 'Contrast', THEME_TEXT_DOMAIN )  => '#707070',
		esc_html__( 'Primary', THEME_TEXT_DOMAIN )   => '#0077bf',
		esc_html__( 'Secondary', THEME_TEXT_DOMAIN ) => '#8f9094',
		esc_html__( 'Green', THEME_TEXT_DOMAIN )    => '#00af93',
		esc_html__( 'Cobalt Blue', THEME_TEXT_DOMAIN )     => '#005ca2',
		esc_html__( 'Curious Blue', THEME_TEXT_DOMAIN ) => '#3d8acb',
		esc_html__( 'Pattens Blue', THEME_TEXT_DOMAIN ) => '#ebf3f8',
		esc_html__( 'Light Blue', THEME_TEXT_DOMAIN ) => '#acd5e6',
		esc_html__( 'Silver Chalice', THEME_TEXT_DOMAIN ) => '#adadad',
		esc_html__( 'White Smoke', THEME_TEXT_DOMAIN ) => '#f3f3f3',
	];
}


function get_gradient_colors() {
	$theme_colors    = [];
	$theme_json_file = get_theme_file_path( 'theme.json' );

	if ( file_exists( $theme_json_file ) ) {
		$theme_json_contents = file_get_contents( $theme_json_file );
		$theme_json_data     = json_decode( $theme_json_contents, true );

		if ( ! empty( $theme_json_data ) && ! empty( $theme_json_data['settings']['color']['gradients'] ) ) {
			foreach ( $theme_json_data['settings']['color']['gradients'] as $gradient ) {
				$gradient_name  = esc_html__( $gradient['name'], THEME_TEXT_DOMAIN );
				$gradient_value = $gradient;

				$theme_gradients[ $gradient_name ] = $gradient_value;
			}
		}

		if ( ! empty( $theme_gradients ) ) {
			return $theme_gradients;
		}
	}

	// If we are not using theme.json file, then setup theme colors here.
	return [
		esc_html__( 'Light Blue to Dark Blue', THEME_TEXT_DOMAIN )       => 'linear-gradient(0deg, #0077bc, #014785)',
		esc_html__( 'Dark Blue to Light Blue', THEME_TEXT_DOMAIN )       => 'linear-gradient(38deg, #005A9E, #2A97D8)',
		esc_html__( 'Light Gray to Dark Gray', THEME_TEXT_DOMAIN )       => 'linear-gradient(0deg, rgb(127 134 142 / 16%), #Fff)',
	];
}
