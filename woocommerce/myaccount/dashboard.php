<?php
/**
 * Tableau de bord « Mon compte » — skin Konoba.
 *
 * Bandeau de bienvenue, chiffres clés (points de fidélité, commandes, dernière
 * commande) et raccourcis vers les autres onglets.
 *
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined('ABSPATH') || exit;

// Les points sont affichés ici en chiffre clé : on retire le bloc auto du plugin.
if (class_exists('TFP_Preorders')) {
    remove_action('woocommerce_account_dashboard', array('TFP_Preorders', 'dashboard_points'));
}

$konoba_uid    = get_current_user_id();
$konoba_hello  = $current_user->display_name;
$konoba_points = function_exists('konoba_get_points') ? konoba_get_points() : null;

$konoba_orders = wc_get_orders(array(
    'customer' => $konoba_uid,
    'limit'    => -1,
    'status'   => array_keys(wc_get_order_statuses()),
    'orderby'  => 'date',
    'order'    => 'DESC',
));

$konoba_count = count($konoba_orders);
$konoba_last  = $konoba_count ? $konoba_orders[0] : null;

// Réservations accessibles au client (plugin TYLT - Fidélités & Réservations).
$konoba_reservations = function_exists('konoba_get_reservations') ? konoba_get_reservations(true) : array();
?>

<div class="konoba-dash">

    <div class="konoba-dash__welcome">
        <span class="konoba-dash__welcome-icon"><?php echo konoba_icon('cards', 'konoba-picto'); ?></span>
        <div class="konoba-dash__welcome-text">
            <h2><?php printf(esc_html__('Bonjour %s', 'konoba'), esc_html($konoba_hello)); ?></h2>
            <p><?php esc_html_e('Bienvenue dans votre espace. Retrouvez ici vos points de fidélité, vos réservations et vos commandes.', 'konoba'); ?></p>
        </div>
        <a class="konoba-dash__logout" href="<?php echo esc_url(wc_logout_url()); ?>"><?php esc_html_e('Se déconnecter', 'konoba'); ?></a>
    </div>

    <ul class="konoba-dash__stats">
        <li class="konoba-dash__stat konoba-dash__stat--highlight">
            <span class="konoba-dash__stat-label"><?php esc_html_e('Points de fidélité', 'konoba'); ?></span>
            <strong class="konoba-dash__stat-value">
                <?php echo null === $konoba_points ? '&mdash;' : esc_html(number_format_i18n($konoba_points)); ?>
            </strong>
            <?php if (null === $konoba_points) : ?>
                <em class="konoba-dash__stat-note"><?php esc_html_e('Cumulez des points à chaque achat en boutique.', 'konoba'); ?></em>
            <?php endif; ?>
        </li>
        <li class="konoba-dash__stat">
            <span class="konoba-dash__stat-label"><?php esc_html_e('Commandes passées', 'konoba'); ?></span>
            <strong class="konoba-dash__stat-value"><?php echo esc_html($konoba_count); ?></strong>
        </li>
        <li class="konoba-dash__stat">
            <span class="konoba-dash__stat-label"><?php esc_html_e('Dernière commande', 'konoba'); ?></span>
            <strong class="konoba-dash__stat-value">
                <?php if ($konoba_last) : ?>
                    <a href="<?php echo esc_url($konoba_last->get_view_order_url()); ?>">#<?php echo esc_html($konoba_last->get_order_number()); ?></a>
                    <em><?php echo esc_html(wc_format_datetime($konoba_last->get_date_created(), 'd/m/Y')); ?></em>
                <?php else : ?>
                    &mdash;
                <?php endif; ?>
            </strong>
        </li>
    </ul>

    <?php if (!empty($konoba_reservations)) : ?>
        <div class="konoba-dash__reservations">
            <div class="konoba-dash__reservations-head">
                <h3><?php echo konoba_icon('calendar', 'konoba-picto'); ?> <?php esc_html_e('Réservations ouvertes', 'konoba'); ?></h3>
                <a href="<?php echo esc_url(wc_get_endpoint_url('reservations')); ?>"><?php esc_html_e('Tout voir', 'konoba'); ?></a>
            </div>
            <ul class="konoba-dash__reservations-list">
                <?php foreach (array_slice($konoba_reservations, 0, 3) as $konoba_item) : ?>
                    <li>
                        <?php if ($konoba_item['image_url']) : ?>
                            <img src="<?php echo esc_url($konoba_item['image_url']); ?>" alt="" />
                        <?php endif; ?>
                        <span class="konoba-dash__reservation-name"><?php echo esc_html($konoba_item['name']); ?></span>
                        <span class="konoba-dash__reservation-meta">
                            <?php echo wp_kses_post(wc_price($konoba_item['price'])); ?>
                            <?php if ($konoba_item['date_end']) : ?>
                                &middot; <?php printf(esc_html__('jusqu\'au %s', 'konoba'), esc_html(date_i18n(get_option('date_format'), strtotime($konoba_item['date_end'])))); ?>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="konoba-dash__shortcuts">
        <?php
        $konoba_cards = array(
            array('calendar', __('Mes réservations', 'konoba'), __('Réservez les prochaines sorties.', 'konoba'), wc_get_endpoint_url('reservations')),
            array('box',      __('Mes commandes', 'konoba'),    __('Suivi et détail de vos commandes.', 'konoba'), wc_get_endpoint_url('orders')),
            array('globe',    __('Mes adresses', 'konoba'),     __('Facturation et livraison.', 'konoba'),         wc_get_endpoint_url('edit-address')),
            array('user',     __('Mes informations', 'konoba'), __('Nom, e-mail et mot de passe.', 'konoba'),      wc_get_endpoint_url('edit-account')),
            array('cart',     __('Boutique', 'konoba'),         __('Retour au catalogue.', 'konoba'),              konoba_catalog_url()),
        );
        foreach ($konoba_cards as $konoba_card) : ?>
            <a class="konoba-dash__card" href="<?php echo esc_url($konoba_card[3]); ?>">
                <span class="konoba-dash__card-icon"><?php echo konoba_icon($konoba_card[0], 'konoba-picto'); ?></span>
                <strong><?php echo esc_html($konoba_card[1]); ?></strong>
                <em><?php echo esc_html($konoba_card[2]); ?></em>
            </a>
        <?php endforeach; ?>
    </div>

</div>

<?php
do_action('woocommerce_account_dashboard');
do_action('woocommerce_before_my_account');
do_action('woocommerce_after_my_account');
