<?php
get_header('shop');
?>

<main id="main-content">
  <h1>Résultat de la recherche pour : <?php echo get_search_query(); ?></h1>

  <?php
  // Arguments de la requête
  $args = array(
    'post_type' => 'product', // Spécifie que l'on recherche des produits
    'posts_per_page' => 12, // Nombre de produits par page
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1, // Utilisation de la pagination classique
    's' => get_search_query(), // Recherche basée sur la query actuelle
  );

  // Nouvelle requête
  $product_query = new WP_Query($args);

  if ($product_query->have_posts()) : ?>
    <ul class="products columns-4">
      <?php
      while ($product_query->have_posts()) : $product_query->the_post();
        // Inclure le template de produit
        get_template_part('template-parts/product/card');
      endwhile;
      ?>
    </ul>
    
    <?php if ( function_exists( 'the_posts_pagination' ) ) :
    the_posts_pagination( array(
        'prev_text' => '←',
        'next_text' => '→',
    ) );
endif; ?>

  <?php else : ?>
    <h2>Aucun produit trouvé pour : <?php echo get_search_query(); ?></h2>
  <?php endif; ?>

  <?php wp_reset_postdata(); ?>
</main>

<?php get_footer(); ?>
