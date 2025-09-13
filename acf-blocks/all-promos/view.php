<?php
// $fields = get_fields();
// extract($fields);
?>
<section class="tylt_all_promos">

    <div class="tylt-categorie-header" style="background-color: #004aad;">
        <div class="licence-link">
            <a href="<?php echo esc_url(home_url('/licences')); ?>">< Revenir à la liste des licences</a>
        </div>
        <h2><?php echo get_the_title(); ?></h2>
    </div>
    <ul class="products columns-5">
        <?php
        // Récupère le numéro de page actuel
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 8,
            'paged' => $paged,
            'meta_query' => array(
                array(
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC'
                ),
            ),
        );

        $featured_query = new WP_Query($args);

        if ($featured_query->have_posts()) {
            while ($featured_query->have_posts()) {
                $featured_query->the_post();
                get_template_part('template-parts/product/card');
            }
            wp_reset_postdata();
        } else {
            echo '<p>Il faut des produit en promos</p>';
        }
        ?>
    </ul>

    <div class="pagination">
        <?php
        echo paginate_links(array(
            'total' => $featured_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => '&raquo;',
            'next_text' => '&laquo;',
        ));
        ?>
    </div>
</section>