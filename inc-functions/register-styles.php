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

	// Source Sans Pro (Storefront) n'est pas utilisée : le thème force Merriweather Sans,
	// auto-hébergée dans dist/fonts (@font-face dans src/scss/main.scss)
	wp_dequeue_style('storefront-fonts');

	// Carte Leaflet du footer : CSS chargée sans bloquer le rendu (voir tylt_async_style_tag)
	wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), null);

	// If SCRIPT_DEBUG is enable, load unminified CSS, if disabled load minified CSS
	if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
		wp_enqueue_style('slick-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick.css');
		wp_enqueue_style('aos-css',  get_stylesheet_directory_uri() . '/dist/vendors/aos/aos.css');
		wp_enqueue_style('slick-theme-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick-theme.css');
		wp_enqueue_style('app-styles',  get_stylesheet_directory_uri() . '/dist/css/main.css');
	} else {
		// Version = date de modification du fichier : le cache navigateur est invalidé à chaque build
		wp_enqueue_style('app-styles',  get_stylesheet_directory_uri() . '/dist/css/main.min.css', array(), @filemtime(get_stylesheet_directory() . '/dist/css/main.min.css'));
		wp_enqueue_style('aos-css',  get_stylesheet_directory_uri() . '/dist/vendors/aos/aos.css');
		wp_enqueue_style('slick-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick.css');
		wp_enqueue_style('slick-theme-css',  get_stylesheet_directory_uri() . '/dist/vendors/slick/css/slick-theme.css');

	}
}

// Styles non critiques (sous la ligne de flottaison) : chargés sans bloquer le rendu
add_filter('style_loader_tag', 'tylt_async_style_tag', 10, 2);
function tylt_async_style_tag($tag, $handle)
{
	if ('leaflet' === $handle) {
		$tag = str_replace(" media='all'", " media='print' onload=\"this.media='all'\"", $tag);
	}
	return $tag;
}

// Preload des polices auto-hébergées pour accélérer le premier affichage
add_action('wp_head', 'tylt_font_hints', 1);
function tylt_font_hints()
{
	echo '<link rel="preload" href="' . esc_url(get_stylesheet_directory_uri() . '/dist/fonts/MerriweatherSans-Regular-latin.woff2') . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="preload" href="' . esc_url(get_stylesheet_directory_uri() . '/dist/fonts/Avega-Italic.woff2') . '" as="font" type="font/woff2" crossorigin>' . "\n";
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