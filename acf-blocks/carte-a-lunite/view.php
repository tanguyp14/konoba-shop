<?php
$fields = get_fields();
extract($fields);
?>
<section class="tylt_cau" data-aos="fade-up">
  <div class="tylt_cau_top">
    <div class="tylt_cau_top_left" data-aos="fade-left">
      <p class="tylt_cau_top_left_title"><?php the_title(); ?></p>
      <h1><?php echo $titre; ?></h1>
      <div class="tylt_cau_top_left_text"><?php echo $zone_de_texte; ?></div>
    </div>
    <div class="tylt_cau_top_right">
      <?php if (!empty($images_haut)) :
        $image = wp_get_attachment_image_src($images_haut, 'full');
        if ($image) : ?>
          <img src="<?php echo esc_url($image[0]); ?>" alt="" />
      <?php endif;
      endif; ?>
    </div>
  </div>
  <div class="tylt_cau_bot">
    <?php
    if (have_rows('liens_vers_card_market', 'option')) {
      $rows = get_field('liens_vers_card_market', 'option');
      if ($rows) {
        $index = 0;
        foreach ($rows as $row) {
          $categorie = $row['nom_de_la_categorie'];
          $lien = $row['liens_vers_cardmarket'];

          // Vérifier que c'est bien un objet WP_Term valide
          if ($categorie instanceof WP_Term) {
            // Récupérer l'ID de l'image de bannière de la catégorie WooCommerce
            // Vérifier si l'utilisateur est sur mobile
            $is_mobile = wp_is_mobile();

            // Récupérer l'ID de l'image selon le device
            if ($is_mobile) {
              // Utiliser la miniature (thumbnail) si mobile
              $thumbnail_id = get_term_meta($categorie->term_id, 'thumbnail_id', true);
            } else {
              // Utiliser la bannière personnalisée sinon
              $thumbnail_id = get_term_meta($categorie->term_id, 'term_banner', true);
            }
            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
          }
    ?>
          <div class="tylt_cau_bot_cardmarket" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
            <a href="<?php echo esc_url($lien); ?>" target="_blank" rel="noopener noreferrer" class="tylt_cau_bot_cardmarket_link">
            </a>
            <?php if (!empty($image_url)): ?>
              <div class="tylt_cau_bot_cardmarket_banner">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($categorie->name); ?> bannière">
              </div>
            <?php endif; ?>
            <h3><?php echo esc_html($categorie->name); ?></h3>
            <?php if (!empty($categorie->description)): ?>
              <p class="tylt_cau_bot_cardmarket_text"><?php echo esc_html($categorie->description); ?></p>
            <?php endif; ?>
            <p class="tylt_cau_bot_cardmarket_button">Voir la catégorie <?php echo esc_html($categorie->name); ?></p>
          </div>
    <?php
        }
      }
    }
    ?>
  </div>
</section>