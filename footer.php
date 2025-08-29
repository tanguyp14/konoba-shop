</div>
<footer>
    <div class="one">
        <?php the_custom_logo(); ?>
        <p class="footer-description">
            <?php bloginfo('description'); ?>
        </p>
    </div>
    <div class="two">
        <div class="two_one">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'secondary',
                'container' => 'nav',
                'container_class' => 'footer-menu',
            ));
            ?>
        </div>
        <div class="two_two">
            <h3>Contact</h3>
            <p class="mail"><a href="mailto:<?php the_field('email', 'option'); ?>"><?php the_field('email', 'option'); ?></a></p>
            <?php
            $phone = get_field('telephone', 'option');
            if ($phone) {
                // Remove all non-digit characters
                $digits = preg_replace('/\D/', '', $phone);
                // Split into groups of 2 digits for display
                $formatted = trim(chunk_split($digits, 2, ' '));
                // Create tel link (international format if starts with 0)
                $tel_link = $digits;
                if (strpos($tel_link, '0') === 0) {
                    $tel_link = '+33' . substr($tel_link, 1);
                }
                echo '<p class="phone"><a href="tel:' . esc_attr($tel_link) . '">' . esc_html($formatted) . '</a></p>';
            }
            ?>
            <div class="socials">
                <?php
                $liens_rs = get_field('liens', 'option');
                if ($liens_rs) {
                    foreach ($liens_rs as $lien) {
                        extract($lien);
                        if (!empty($icon) && !empty($liens_vers)) {
                            echo '<p><a href="' . esc_url($liens_vers) . '" target="_blank">';
                            echo '<img src="' . esc_url($icon) . '" alt="' . esc_attr($nom_du_rs) . '">';
                            echo esc_html($nom_du_rs);
                            echo '</a></p>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="tree">
        <div class="tree_one">
            <h3>Boutique</h3>
            <p class="adress"><?php the_field('adresse', 'option'); ?></p>
        </div>
        <p><?php the_field('horaires', 'option'); ?></p>

    </div>
    <div class="four">
        <div data-aos="zoom-in" id="map-footer"></div>
    </div>
    <div class="under">
        <span class="cpr">© <?php echo date('Y'); ?> <?php bloginfo('name'); ?> -
            <a href="/mentions-legales">Mentions légales</a> -
            <a href="/cgv">CGV</a> -
            <a href="/rgpd">RGPD</a> - Conception site : <a href="https://le-tengu.fr" target="_blank">tylt</a>
        </span>
    </div>
</footer>

<?php wp_footer(); ?>