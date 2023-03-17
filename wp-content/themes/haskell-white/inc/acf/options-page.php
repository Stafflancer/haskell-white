<?php
/**
 * ACF theme options page - Setting up ACF options pages
 * Enables "Options" pages in Advanced Custom Fields
 */

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( [
		'page_title' => 'Theme Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug'  => 'theme-settings',
		'capability' => 'edit_posts',
		'redirect'   => true,
		'position'   => 3.1,
	] );

	acf_add_options_sub_page( [
		'page_title'  => 'General Settings',
		'menu_title'  => 'General',
		'parent_slug' => 'theme-settings',
	] );

	acf_add_options_sub_page( [
		'page_title'  => 'Header Settings',
		'menu_title'  => 'Header',
		'parent_slug' => 'theme-settings',
	] );

	acf_add_options_sub_page( [
		'page_title'  => 'Footer Settings',
		'menu_title'  => 'Footer',
		'parent_slug' => 'theme-settings',
	] );
}