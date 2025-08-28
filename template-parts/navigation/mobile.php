<?php

/**
 * Primary menu template part
 */
?>

<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('menu_tylt_principal', 'tylt'); ?>">

    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'menu_tylt_mobile',
            'menu_id'        => 'menu_tylt_mobile',
        )
    );
    ?>
    <?php
        wp_nav_menu(array(
            'theme_location' => 'secondary',
            'container' => 'nav',
            'container_class' => 'footer-menu',
        ));
        ?>
</nav><!-- #site-navigation -->