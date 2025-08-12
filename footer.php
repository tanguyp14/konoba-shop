<footer>
    <div class="left">
        <div class="search">
            <?php get_search_form(); ?>
        </div>
        <div class="footer-contact">
            <p class="big">Une question, une recherche particulière ?</p>
            <a href="/contact" class="tylt-button"><span>Contactez-nous</span></a>
        </div>
        <div class="footer-info">
            <p class="info">Le saviez-vous ?</p>
            <p class="big">Nous rachetons vos cartes !</p>
            <a href="/qui-sommes-nous/#rachat" class="tylt-button"><span>En savoir plus</span></a>
        </div>
    </div>
    <div class="right">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'secondary',
            'container' => 'nav',
            'container_class' => 'footer-menu',
        ));
        ?>
    </div>
    <div class="under">
        <span class="socials">
            <?php
            $liens = get_field('liens', 'option');
            if ($liens) {
                foreach ($liens as $lien) {
                    extract($lien);
                    if (!empty($icon)) {
            ?>
                        <a href="<?php echo $liens_vers; ?>" target="_blank" class="social-link">
                            <img src="<?php echo $icon; ?>" alt="<?php echo $icon; ?>"></a>
                <?php
                    }
                }
            }
                ?>
        </span>
        <span class="cpr">© 2021 <?php bloginfo('name'); ?> -
            <a href="/mentions-legales">Mentions légales</a> -
            <a href="/cgv">CGV</a> -
            <a href="/rgpd">RGPD</a> - Conception site : <a href="https://le-tengu.fr" target="_blank">tylt</a>
        </span>
    </div>
</footer>

<?php wp_footer(); ?>