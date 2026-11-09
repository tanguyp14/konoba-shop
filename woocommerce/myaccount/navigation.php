<?php
/**
 * Navigation « Mon compte » — skin Konoba (carte identité + pictogrammes).
 *
 * Surcharge de woocommerce/templates/myaccount/navigation.php.
 *
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_navigation');

$konoba_nav_icons = array(
    'dashboard'       => 'shop',
    'orders'          => 'box',
    'reservations'    => 'calendar',
    'downloads'       => 'tag',
    'edit-address'    => 'globe',
    'edit-account'    => 'user',
    'payment-methods' => 'tag',
    'customer-logout' => 'lock',
);

$konoba_user   = wp_get_current_user();
$konoba_name   = $konoba_user->display_name ? $konoba_user->display_name : $konoba_user->user_login;
$konoba_points = function_exists('konoba_get_points') ? konoba_get_points() : null;
?>

<nav class="woocommerce-MyAccount-navigation konoba-accnav" aria-label="<?php esc_attr_e('Pages du compte', 'konoba'); ?>">

    <div class="konoba-accnav__identity">
        <span class="konoba-accnav__avatar"><?php echo konoba_icon('user', 'konoba-picto'); ?></span>
        <span class="konoba-accnav__labels">
            <strong><?php echo esc_html($konoba_name); ?></strong>
            <em><?php esc_html_e('Compte client', 'konoba'); ?></em>
        </span>
    </div>

    <?php if (null !== $konoba_points) : ?>
        <div class="konoba-accnav__points">
            <?php echo konoba_icon('star', 'konoba-accnav__points-icon'); ?>
            <span class="konoba-accnav__points-value"><?php echo esc_html(number_format_i18n($konoba_points)); ?></span>
            <span class="konoba-accnav__points-label"><?php esc_html_e('points de fidélité', 'konoba'); ?></span>
        </div>
    <?php endif; ?>

    <ul>
        <?php foreach (wc_get_account_menu_items() as $konoba_endpoint => $konoba_label) : ?>
            <li class="<?php echo esc_attr(wc_get_account_menu_item_classes($konoba_endpoint)); ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url($konoba_endpoint)); ?>" <?php echo wc_is_current_account_menu_item($konoba_endpoint) ? 'aria-current="page"' : ''; ?>>
                    <?php echo konoba_icon(isset($konoba_nav_icons[$konoba_endpoint]) ? $konoba_nav_icons[$konoba_endpoint] : 'info', 'konoba-accnav__icon'); ?>
                    <span><?php echo esc_html($konoba_label); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>
