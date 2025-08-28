<?php
$fields = get_fields();
extract($fields);
?>
<section class="tylt_promos">
    <h2 class="category-title"><?php echo esc_html($titre); ?></h2>
    <ul class=" products columns-<?php echo $number_of_products; ?>">
<?php

$args = array(
    'post_type' => 'product',
    'posts_per_page' => $number_of_products,
    'meta_query' => array(
        array(
            'key'     => '_sale_price',
            'value'   => 0,
            'compare' => '>',
            'type'    => 'NUMERIC'
        ),
    ),
    'orderby' => 'modified',
    'order' => 'DESC',
);

$featured_query = new WP_Query($args);

if ($featured_query->have_posts()) {
    while ($featured_query->have_posts()) {
        $featured_query->the_post();
        get_template_part( 'template-parts/product/card' );
    }
    wp_reset_postdata();
} else {
    echo '<p>Il faut des produit en promos</p>';
}
?>
    </ul>
    <a class="tylt-button" href="/promos" class="view-all-link">
        <span><?php echo $texte_button; ?></span>
    </a>
</section>