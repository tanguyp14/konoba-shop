<?php
require_once 'inc-functions/woocustomhook.php';
require_once 'inc-functions/register-menus.php';
require_once 'inc-functions/register-styles.php';
require_once 'inc-functions/register-scripts.php';
require_once 'inc-functions/acf-options-page.php';

// Autoriser svg 
function autoriser_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'autoriser_svg_upload');


/**
 * Essential theme supports
 * */
add_action('after_setup_theme', 'tylt_theme_setup');
function tylt_theme_setup()
{
	/** tag-title **/
	add_theme_support('title-tag');

	/** post-thumnails **/
	add_theme_support('post-thumbnails');

	/** editor-styles **/
	add_theme_support('editor-styles');

	/** editor-styles-css **/
	add_editor_style('editor.css');

	/** Load block styles on frontend **/
	add_theme_support('wp-block-styles');

	/** Align wide **/
	add_theme_support('align-wide');

	add_theme_support('woocommerce');
    add_theme_support('wc-blocks');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}

/**
 * Add custom logo for admin login screen and link to homepage
 */
function tylt_filter_login_head()
{

	if (has_custom_logo()) {
		$image = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
?>
		<style type="text/css">
			.login h1 a {
				background-image: url(<?php echo esc_url($image[0]); ?>);
				-webkit-background-size: contain;
				background-size: contain;
				height: 80px;
				width: 200px;
			}
		</style>
<?php
	}
}
add_action('login_head', 'tylt_filter_login_head', 100);

function tylt_new_wp_login_url()
{
	return home_url();
}
add_filter('login_headerurl', 'tylt_new_wp_login_url');

function ts_custom_my_account_menu_items($items)
{
	unset($items['downloads']);
	return $items;
}
add_filter('woocommerce_account_menu_items', 'ts_custom_my_account_menu_items');


add_action( 'init', 'bbloomer_remove_storefront_breadcrumbs' );
 
function bbloomer_remove_storefront_breadcrumbs() {
   remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
}

if ( ! function_exists( 'storefront_page_header' ) ) {
    function storefront_page_header() {
        // Ne rien afficher
    }
}


add_action('woocommerce_product_options_stock_fields', function () {
    global $post;

    echo '<div class="options_group">';
    woocommerce_wp_text_input([
        'id'          => '_sku',
        'label'       => __('UGS (SKU)', 'woocommerce'),
        'desc_tip'    => true,
        'description' => __('Identifiant unique pour la gestion du stock.', 'woocommerce'),
    ]);
    echo '</div>';
}, 9); // priorité 9 pour le placer avant le champ GTIN


// Activation pour la suppression de toutes les catégories de produits
// function tylt_delete_all_product_categories()
// {
// 	$taxonomy = 'product_cat';
// 	$terms = get_terms(array(
// 		'taxonomy' => $taxonomy,
// 		'hide_empty' => false,
// 	));

// 	foreach ($terms as $term) {
// 		wp_delete_term($term->term_id, $taxonomy);
// 	}
// }
// add_action('init', 'tylt_delete_all_product_categories');