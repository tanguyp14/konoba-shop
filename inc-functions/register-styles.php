<?php

/**
 * Register Styles
 */

// Enqueue Styles
add_action('wp_enqueue_scripts', 'tylt_styles', 20);
function tylt_styles()
{
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');

	// If SCRIPT_DEBUG is enable, load unminified CSS, if disabled load minified CSS
	if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
		wp_enqueue_style('slick-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick.css');
		wp_enqueue_style('aos-css',  get_stylesheet_directory_uri() . '/dist/vendors/aos/aos.css');
		wp_enqueue_style('slick-theme-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick-theme.css');
		wp_enqueue_style('app-styles',  get_stylesheet_directory_uri() . '/dist/css/main.css');
	} else {
		wp_enqueue_style('app-styles',  get_stylesheet_directory_uri() . '/dist/css/main.min.css');
		wp_enqueue_style('aos-css',  get_stylesheet_directory_uri() . '/dist/vendors/aos/aos.css');
		wp_enqueue_style('slick-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick.css');
		wp_enqueue_style('slick-theme-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick-theme.css');

	}
}

// Enqueue Admin Styles
// add_action('admin_enqueue_scripts', 'tylt_admin_styles', 20);
// function tylt_admin_styles()
// {
// 	wp_enqueue_style('admin-styles', get_stylesheet_directory_uri() . '/dist/css/admin.css', false, '1.0.0');
// }

// Enqueue Block Editor styles
add_action('enqueue_block_editor_assets', 'tylt_editor_styles', 20);
function tylt_editor_styles()
{

	wp_enqueue_style('tylt-editor-styles', get_stylesheet_directory_uri() . '/editor.css', array('wp-edit-blocks'));
}
add_action('admin_enqueue_scripts', 'tylt_admin_styles');
function tylt_admin_styles() {
	wp_enqueue_style('tylt-admin-styles', get_stylesheet_directory_uri() . '/admin.css');
}