<?php
$fields = get_fields();
extract($fields);
?>
<section class="tylt_one_categorie">
    <h2 class="category-title"><?php echo esc_html($titre); ?></h2>
    <ul class="products columns-<?php echo $nombre_de_produits_a_afficher; ?>">
<?php

$args = array(
    'post_type' => 'product',
    'posts_per_page' => $nombre_de_produits_a_afficher,
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $nom_de_la_categorie_a_afficher,
        ),
    ),
    'meta_query' => array(
        array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '=',
        ),
    ),
);

$featured_query = new WP_Query($args);
if ($featured_query->have_posts()) {
    $index = 0;
    while ($featured_query->have_posts()) {
        $featured_query->the_post();

        // calcule le delay (0 à 500ms puis reset)
        $time = ($index % 6) * 100; 
        set_query_var('time', $time);

        get_template_part('template-parts/product/card');

        $index++;
    }
    wp_reset_postdata();
}

?>
    </ul>
    <a class="tylt-button" href="<?php echo esc_url(get_term_link($nom_de_la_categorie_a_afficher, 'product_cat')); ?>" class="view-all-link">
        <span><?php echo $texte_button; ?></span>
    </a>
</section>
