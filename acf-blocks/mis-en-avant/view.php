<?php
$fields = get_fields();
extract($fields);
?>
<section class="tylt_stars">
    <h2 class="category-title"><?php echo esc_html($titre); ?></h2>
    <ul class="products columns-<?php echo $number_of_products; ?>">
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $number_of_products,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ),
            ),
            'orderby' => 'modified',
            'order'   => 'DESC'
        );
        if (!empty($only_in_stock)) {
            $args['meta_query'][] = array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '='
            );
        }

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
        } else {
            echo '<p>Il faut cliquer sur la petite étoiles "Mis en avant" à coté des produits pour afficher des produits ici</p>';
        }
        ?>
    </ul>
</section>