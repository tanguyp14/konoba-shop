<?php

/**
 * Categorie header template part
 */
?>
<?php
$term = get_queried_object();
$parent_id = $term->parent; // ID du parent direct
$ancestors = get_ancestors($term->term_id, 'product_cat'); // Récupérer les ancêtres
$ancestors = array_reverse($ancestors); // Ancêtres du plus ancien au plus récent

// Ajouter le parent direct au tableau des ancêtres si ce n'est pas déjà inclus
if ($parent_id && !in_array($parent_id, $ancestors)) {
    array_unshift($ancestors, $parent_id);
}

// Récupérer l'ID de la catégorie principale
$main_category_id = !empty($ancestors) ? $ancestors[0] : $term->term_id;

// Récupérer l'URL de l'image de la catégorie principale
$thumbnail_id = get_term_meta($main_category_id, 'thumbnail_id', true);
$banner_id = get_term_meta($main_category_id, 'term_banner', true);
$image_url = $banner_id ? wp_get_attachment_url($banner_id) : '';




$target_urls = [];

// Vérifier si le répéteur 'liens_vers_card_market' existe
if (have_rows('liens_vers_card_market', 'option')) {
    // Parcourir chaque ligne du répéteur
    while (have_rows('liens_vers_card_market', 'option')) {
        the_row();

        // Récupérer le nom de la catégorie (qui est un objet WP_Term)
        $category_object = get_sub_field('nom_de_la_categorie'); // L'objet WP_Term
        $category_link = get_sub_field('liens_vers_cardmarket'); // Lien vers CardMarket

        // Vérifier si l'objet WP_Term a une propriété 'name' (chaîne)
        if ($category_object && isset($category_object->name)) {
            $category_name = $category_object->name; // Extraire le nom de la catégorie
        } else {
            $category_name = ''; // Valeur par défaut si l'objet est invalide
        }

        // Ajouter ces valeurs au tableau
        if ($category_name && $category_link) {
            $target_urls[$category_name] = $category_link; // Ajouter la catégorie et le lien dans le tableau
        }
    }
}

$main_category = $term;
while ($main_category->parent != 0) {
    $main_category = get_term($main_category->parent, 'product_cat');
}

// Récupérer l'URL correspondante dans $target_urls
$main_category_name = $main_category->name;
$main_category_url = $target_urls[$main_category_name] ?? '#';
?>
<div class="tylt-categorie-header">
    <div class="licence-link">
        <a href="<?php echo esc_url(home_url('/licences')); ?>">
            < Revenir à la liste des licences</a>
    </div>
    <?php if ($main_category_name !== 'Tournois' && $main_category_name !== 'Vanguard' && $main_category_name !== 'Promos' && $main_category_name !== 'Accessoires généraux') : ?>
        <a class="cardmark" target="_blank" href="<?php echo esc_url($main_category_url); ?>">Carte à l'unité <?php echo $main_category_name ?><span class="external"></span></a>
    <?php endif; ?>
    <?php if ($image_url) : ?>
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_term($main_category_id, 'product_cat')->name); ?>" class="category-main-image">
    <?php endif; ?>
    <h1>
        <?php if (!empty($ancestors)) : ?>
            <?php foreach ($ancestors as $key => $ancestor_id) : ?>
                <?php
                $ancestor_term = get_term($ancestor_id, 'product_cat');
                if ($ancestor_term && !is_wp_error($ancestor_term)) :
                    $ancestor_link = get_term_link($ancestor_term, 'product_cat');
                ?>
                    <a href="<?php echo esc_url($ancestor_link); ?>">
                        <?php echo esc_html($ancestor_term->name); ?>
                    </a>
                    <?php if ($key < count($ancestors)) : ?>
                        -
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Afficher la catégorie actuelle -->
        <?php echo esc_html($term->name); ?>
    </h1>
</div>