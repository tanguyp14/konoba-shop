<?php
/**
 * Register nav menus locations
 */

 if (!function_exists('tylt_register_nav_menu')) {

    function tylt_register_nav_menu()
    {
        register_nav_menus(array(
            'menu_tylt_principal' => __('Menu Global', 'menu'),
            'menu_tylt_mobile' => __('Menu Mobile', 'menu'),
        ));
    }
    add_action('after_setup_theme', 'tylt_register_nav_menu', 0);
}
