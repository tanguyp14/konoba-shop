<?php
$fields = get_fields();
extract($fields);
?>
<section class="tylt_histoire" >
    <div class="tylt_histoire_left" data-aos="fade-right">
        <?php if (!empty($pre_titre)): ?>
            <h2 class="tylt_histoire_left_pre-titre"><?php echo $pre_titre; ?></h2>
        <?php endif; ?>

        <?php if (!empty($titre)): ?>
            <span class="tylt_histoire_left_titre"><?php echo $titre; ?></span>
        <?php endif; ?>

        <?php if (!empty($champs_texte)): ?>
            <div class="tylt_histoire_left_texte"><?php echo wp_kses_post($champs_texte); ?></div>
        <?php endif; ?>

        <?php if (!empty($sous_titre)): ?>
            <div class="tylt_histoire_left_pre-titre"><?php echo esc_html($sous_titre); ?></div>
        <?php endif; ?>

        <?php if (!empty($zone_de_texte)): ?>
            <div class="tylt_histoire_left_texte"><?php echo wp_kses_post($zone_de_texte); ?></div>
        <?php endif; ?>
        <div class="tylt_histoire_left_socials">
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
    <div class="tylt_histoire_right" data-aos="fade-left">
        <?php if (!empty($image)): ?>
            <?php echo wp_get_attachment_image($image, 'full', false, ['class' => 'tylt_histoire__image']); ?>
        <?php endif; ?>
    </div>
</section>