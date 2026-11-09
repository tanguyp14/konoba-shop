<?php

/**
 * Register Scripts
 */

// Enqueue Scripts
add_action('wp_enqueue_scripts', 'tylt_scripts');
function tylt_scripts()
{

	// Tous les scripts du thème sont chargés en footer pour ne pas bloquer le rendu
	// (Leaflet, utilisé par app.js pour la carte, est inclus dans footer.php)
	// If SCRIPT_DEBUG is enable, load unminified JS, if disabled load minified JS
	if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
		wp_enqueue_script('aos-js',  get_stylesheet_directory_uri() . '/dist/vendors/aos/aos.js', array(), false, true);
		wp_enqueue_script('app-scripts', get_stylesheet_directory_uri() . '/dist/js/app.js', array('jquery', 'aos-js'), false, true);
		wp_enqueue_script('slick-js',  get_stylesheet_directory_uri() . '/dist/vendors/slick/js/slick.min.js', array('jquery'), false, true);
	} else {
		wp_enqueue_script('aos-js',  get_stylesheet_directory_uri() . '/dist/vendors/aos/aos.js', array(), false, true);
		wp_enqueue_script('app-scripts', get_stylesheet_directory_uri() . '/dist/js/app.min.js', array('jquery', 'aos-js'), false, true);
		wp_enqueue_script('slick-js',  get_stylesheet_directory_uri() . '/dist/vendors/slick/js/slick.min.js', array('jquery'), false, true);
	}
}

// La Poste Pro Expéditions charge MapLibre (~1 Mo) sur toutes les pages :
// on ne le garde que là où le choix du point relais est possible.
add_action('wp_enqueue_scripts', 'tylt_dequeue_laposte_assets', 100);
function tylt_dequeue_laposte_assets()
{
	if (function_exists('is_cart') && (is_cart() || is_checkout())) {
		return;
	}
	foreach (array('laposteproexp_polyfills', 'laposteproexp_maplibre_gl', 'laposteproexp_shipping') as $handle) {
		wp_dequeue_script($handle);
	}
	foreach (array('laposteproexp_maplibre_gl', 'laposteproexp_parcel_point') as $handle) {
		wp_dequeue_style($handle);
	}
}

// Enqueue Block Editor Script
add_action('enqueue_block_editor_assets', 'tylt_block_enqueues');
function tylt_block_enqueues()
{
	wp_enqueue_script('pix-editor-scripts', get_stylesheet_directory_uri() . '/editor.js', array('wp-edit-post', 'wp-blocks', 'wp-dom-ready'), '', true);

	// Les vues de blocs (ex. swiper) utilisent slick aussi dans l'aperçu de l'éditeur
	wp_enqueue_script('slick-js',  get_stylesheet_directory_uri() . '/dist/vendors/slick/js/slick.min.js', array('jquery'), false, false);
	wp_enqueue_style('slick-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick.css');
	wp_enqueue_style('slick-theme-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick-theme.css');
}
