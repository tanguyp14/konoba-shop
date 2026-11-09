<?php
/**
 * Connexion / inscription — skin Konoba.
 *
 * Surcharge de woocommerce/templates/myaccount/form-login.php.
 *
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');

$konoba_registration = ('yes' === get_option('woocommerce_enable_myaccount_registration'));

$konoba_atouts = array(
    array('star',     __('Carte de fidélité', 'konoba'),  __('Vos points cumulés en boutique, consultables en ligne.', 'konoba')),
    array('calendar', __('Réservations', 'konoba'),       __('Réservez les nouvelles sorties avant tout le monde.', 'konoba')),
    array('box',      __('Commandes & suivi', 'konoba'),  __('Historique et suivi de vos commandes au même endroit.', 'konoba')),
);
?>

<div class="konoba-login">

    <div class="konoba-login__panel">
        <span class="konoba-login__eyebrow"><?php esc_html_e('Espace client', 'konoba'); ?></span>
        <h2 class="konoba-login__title"><?php esc_html_e('Se connecter', 'konoba'); ?></h2>
        <p class="konoba-login__intro"><?php esc_html_e('Retrouvez vos points de fidélité, vos réservations et vos commandes.', 'konoba'); ?></p>

        <form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

            <?php do_action('woocommerce_login_form_start'); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="username"><?php esc_html_e('Identifiant ou e-mail', 'konoba'); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password"><?php esc_html_e('Mot de passe', 'konoba'); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
            </p>

            <?php do_action('woocommerce_login_form'); ?>

            <div class="konoba-login__row">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                    <span><?php esc_html_e('Se souvenir de moi', 'konoba'); ?></span>
                </label>
                <a class="konoba-login__lost" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Mot de passe oublié ?', 'konoba'); ?></a>
            </div>

            <p class="form-row konoba-login__submit-row">
                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit konoba-btn" name="login" value="<?php esc_attr_e('Se connecter', 'konoba'); ?>">
                    <?php esc_html_e('Se connecter', 'konoba'); ?>
                    <?php echo konoba_icon('arrow', 'konoba-btn__icon'); ?>
                </button>
            </p>

            <?php do_action('woocommerce_login_form_end'); ?>

        </form>
    </div>

    <aside class="konoba-login__aside">

        <h3 class="konoba-login__aside-title">
            <?php echo $konoba_registration ? esc_html__('Créer un compte', 'konoba') : esc_html__('Pas encore de compte ?', 'konoba'); ?>
        </h3>
        <p class="konoba-login__aside-text"><?php esc_html_e('Rejoignez la communauté Konoba Cards : fidélité, réservations et nouveautés en avant-première.', 'konoba'); ?></p>

        <ul class="konoba-login__atouts">
            <?php foreach ($konoba_atouts as $konoba_atout) : ?>
                <li class="konoba-login__atout">
                    <span class="konoba-login__atout-icon"><?php echo konoba_icon($konoba_atout[0], 'konoba-picto'); ?></span>
                    <span class="konoba-login__atout-text">
                        <strong><?php echo esc_html($konoba_atout[1]); ?></strong>
                        <em><?php echo esc_html($konoba_atout[2]); ?></em>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($konoba_registration) : ?>

            <form method="post" class="woocommerce-form woocommerce-form-register register konoba-login__register" <?php do_action('woocommerce_register_form_tag'); ?>>

                <?php do_action('woocommerce_register_form_start'); ?>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_username"><?php esc_html_e('Identifiant', 'konoba'); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
                    </p>
                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_email"><?php esc_html_e('Adresse e-mail', 'konoba'); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email']) && is_string($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" />
                    <small class="konoba-login__hint"><?php esc_html_e('Utilisez la même adresse qu\'en boutique pour retrouver vos points.', 'konoba'); ?></small>
                </p>

                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_password"><?php esc_html_e('Mot de passe', 'konoba'); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
                    </p>
                <?php else : ?>
                    <p class="konoba-login__hint"><?php esc_html_e('Un lien de création de mot de passe vous sera envoyé par e-mail.', 'konoba'); ?></p>
                <?php endif; ?>

                <?php do_action('woocommerce_register_form'); ?>

                <p class="woocommerce-form-row form-row">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit konoba-btn konoba-btn--ghost" name="register" value="<?php esc_attr_e('Créer mon compte', 'konoba'); ?>">
                        <?php esc_html_e('Créer mon compte', 'konoba'); ?>
                        <?php echo konoba_icon('arrow', 'konoba-btn__icon'); ?>
                    </button>
                </p>

                <?php do_action('woocommerce_register_form_end'); ?>

            </form>

        <?php else : ?>

            <a class="konoba-btn konoba-btn--ghost" href="<?php echo esc_url(konoba_catalog_url()); ?>">
                <?php esc_html_e('Découvrir la boutique', 'konoba'); ?>
                <?php echo konoba_icon('arrow', 'konoba-btn__icon'); ?>
            </a>

        <?php endif; ?>

    </aside>

</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
