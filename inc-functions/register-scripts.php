<?php

/**
 * Register Scripts
 */

// Enqueue Scripts
add_action('wp_enqueue_scripts', 'tylt_scripts');
function tylt_scripts()
{

	// If SCRIPT_DEBUG is enable, load unminified JS, if disabled load minified JS
	if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
		wp_enqueue_script('aos-js',  get_stylesheet_directory_uri() . '/dist/vendors/aos/aos.js');
		wp_enqueue_script('app-scripts', get_stylesheet_directory_uri() . '/dist/js/app.js', array('jquery'), false, false);
		wp_enqueue_script('slick-js',  get_stylesheet_directory_uri() . '/dist/vendors/slick/js/slick.min.js');
	} else {
		wp_enqueue_script('aos-js',  get_stylesheet_directory_uri() . '/dist/vendors/aos/aos.js');
		wp_enqueue_script('app-scripts', get_stylesheet_directory_uri() . '/dist/js/app.min.js', array('jquery'), false, false);
		wp_enqueue_script('slick-js',  get_stylesheet_directory_uri() . '/dist/vendors/slick/js/slick.min.js');
	}
}

// Enqueue Block Editor Script
add_action('enqueue_block_editor_assets', 'tylt_block_enqueues');
function tylt_block_enqueues()
{
	wp_enqueue_script('pix-editor-scripts', get_stylesheet_directory_uri() . '/editor.js', array('wp-edit-post', 'wp-blocks', 'wp-dom-ready'), '', true);
}
