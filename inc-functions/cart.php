<?php

/**
 * Page panier (bloc WooCommerce « Panier ») — skin Konoba, aligné sur l'espace client.
 */

/**
 * Bandeau d'en-tête au-dessus du bloc panier (même style que le bandeau « Bonjour » du tableau de bord).
 */
add_filter('render_block_woocommerce/cart', 'konoba_cart_header', 10, 1);
function konoba_cart_header($content)
{
	if (!function_exists('konoba_icon')) {
		return $content;
	}

	ob_start();
?>
	<div class="konoba-cart-head alignwide">
		<span class="konoba-cart-head__icon"><?php echo konoba_icon('cart', 'konoba-picto'); ?></span>
		<div class="konoba-cart-head__text">
			<h1><?php esc_html_e('Mon panier', 'konoba'); ?></h1>
			<p><?php esc_html_e('Vérifiez vos articles, puis validez votre commande en toute sécurité.', 'konoba'); ?></p>
		</div>
		<a class="konoba-cart-head__link" href="<?php echo esc_url(home_url('/licences/')); ?>"><?php esc_html_e('Continuer mes achats', 'konoba'); ?></a>
	</div>
<?php
	return ob_get_clean() . $content;
}
