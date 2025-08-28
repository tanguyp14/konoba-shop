<?php

/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="Cartes Normandes, votre boutique spécialisée dans l'achat, la vente et l'échange de cartes TCG. Magic: The Gathering, Yu-Gi-Oh!, Pokémon, One Piece et Lorcana." />

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<!-- Google Tag Manager -->

	<!-- End Google Tag Manager -->

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MTWG34L7"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php wp_body_open(); ?>

	<?php do_action('storefront_before_site'); ?>
	<div id="page" class="hfeed site">
		<?php do_action('storefront_before_header'); ?>

		<!-- <div id="shipping-bar"></div> -->
		<header class="site-header hidden" role="banner">
			<span class="inner-menu">
				<div class="tylt-site-branding" data-aos="fade-left">
					<?php if (function_exists('the_custom_logo') && has_custom_logo()) : ?>
						<div class="site-logo"><?php the_custom_logo(); ?></div>
					<?php else : ?>
						<div class="site-title">
							<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
						</div>
					<?php endif; ?>
				</div>
				<?php get_template_part('template-parts/navigation/primary'); ?>
			</span>
			<div class="search_wrapper">
				<span class="search_icon">
					<span class="icon_search"></span><span class="text-icon">Rechercher</span>
				</span>
				<div class="search_form_wrapper">
					<span class="close_icon">
						<span class="close_search"></span>
					</span>
					<?php get_search_form(); ?>
				</div>
			</div>
			<div class="account_wrapper">
				<a alt="Mon compte" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"><span class="icon_account"></span></a>
				<?php if (is_user_logged_in()) : ?>
					<a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="logout-link" title="Se déconnecter">
						<span class="icon_logout"></span>
					</a>
				<?php endif; ?>
			</div>
			<div class="site-header-cart">
				<?php storefront_header_cart(); ?>
			</div>
		</header>
		<header class="site-header-mobile hidden" role="banner">
			<div class="button_menu_mobile"><span></span><span></span><span></span></div>
			<div class="tylt-site-branding">
				<?php if (function_exists('the_custom_logo') && has_custom_logo()) : ?>
					<div class="site-logo"><?php the_custom_logo(); ?></div>
				<?php else : ?>
					<div class="site-title">
						<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
					</div>
				<?php endif; ?>
			</div>
			<div class="account_wrapper mobile">
				<a alt="Mon compte" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"><span class="icon_account"></span></a>
			</div>
		</header>
		<menu-header>
			<div class="search">
				<?php get_search_form(); ?>
			</div>
			<?php get_template_part('template-parts/navigation/mobile'); ?>
		</menu-header>
		<sub-header class="hidden">
			<div class="site-header-cart">
				<a class="tylt-button" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e('View your shopping cart'); ?>"><span>Voir mon panier</span><span class="count"><?php echo sprintf(_n('%d', '%d', WC()->cart->get_cart_contents_count()), WC()->cart->get_cart_contents_count()); ?></span>
				</a>
				<a class="price-tag" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e('View your shopping cart'); ?>">

					<span class="price"><?php echo WC()->cart->get_cart_total(); ?></span>
				</a>
			</div>
		</sub-header>

		<?php
		/**
		 * Functions hooked in to storefront_before_content
		 *
		 * @hooked storefront_header_widget_region - 10
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action('storefront_before_content');
		?>

		<div id="content" class="site-content" tabindex="-1">
			<div class="tylt_max">

				<?php
				do_action('storefront_content_top');
