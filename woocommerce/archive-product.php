<?php

/**
 * Version: 8.6.0
 * Template for displaying product archives.
 */

get_header('shop');

?>
<?php

$time = ($index % 6) * 100;
$index++;

// Gros banger
// Récupérer l'objet de la catégorie
if (is_tax('product_cat')) :
    $term = get_queried_object();
    $is_parent = (get_term_children($term->term_id, 'product_cat')) ? true : false;
    $intermediate_parent = get_term($term->parent, 'product_cat');
    $is_child = ($term->parent != 0);

    $target_urls = [];

    // Vérifier si la catégorie est un parent sans parent (catégorie principale)
    if (!$is_child && $is_parent) : ?>
        <div class="main-parent-category-template">
            <?php get_template_part('template-parts/navigation/categorie-header'); ?>
            <ul class="child-categories">
                <li class="current"><a href="<?php echo get_term_link($term->term_id); ?>">Nouveautés</a></li>
                <?php
                $child_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => $term->term_id,
                    'hide_empty' => true,
                ));
                ?>

                <?php foreach ($child_categories as $child_category): ?>
                    <?php $class = ($child_category->term_id == $term->term_id) ? 'class="current"' : ''; ?>
                    <li <?php echo $class; ?>>
                        <a href="<?php echo get_term_link($child_category); ?>">
                            <?php echo $child_category->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if ($main_category_name !== 'Tournois' && $main_category_name !== 'Vanguard' && $main_category_name !== 'Promos' && $main_category_name !== 'Accessoires généraux') : ?>

                <?php endif; ?>
            </ul>

            <!-- <div class="woocommerce-pagination">
                <?php
                // Affiche la pagination WooCommerce
                woocommerce_pagination();
                ?>
            </div> -->
            <?php woocommerce_product_loop_start();
            if (!have_posts()) {
                if (!$is_parent && $main_category_url == '#') {
                    echo '<h2 class="empty_message">Cette catégorie ne contient actuellement aucun produit ou sous-catégorie.</h2>';
                } else {
                    echo '<h2 class="empty_message">Aucun produit trouvé dans cette catégorie.</h2>';
                }
            }
            while (have_posts()) : the_post();
                $time = ($aos_index % 5) * 100;
                set_query_var('time', $time);
                get_template_part('template-parts/product/card');
                $aos_index++;
            endwhile;
            woocommerce_product_loop_end(); ?>
            <div class="woocommerce-pagination">
                <?php
                // Affiche la pagination WooCommerce
                woocommerce_pagination();
                ?>
            </div>
        </div>

    <?php
    // Vérifier si la catégorie est à la fois un enfant et un parent (catégorie intermédiaire)
    elseif ($is_child && $is_parent) : ?>
        <div class="intermediate-category-template">
            <?php get_template_part('template-parts/navigation/categorie-header'); ?>


            <!-- Affiche les catégories sœurs de la catégorie actuelle -->
            <ul class="sibling-categories">
                <li><a href="<?php echo get_term_link($term->parent); ?>">Nouveautés</a></li>
                <?php
                $sibling_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => $term->parent,
                    'hide_empty' => true,
                ));
                ?>
                <?php foreach ($sibling_categories as $sibling_category): ?>
                    <?php $class = ($sibling_category->term_id == $term->term_id) ? 'class="current"' : ''; ?>
                    <li <?php echo $class; ?>>
                        <a href="<?php echo get_term_link($sibling_category); ?>">
                            <?php echo $sibling_category->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if ($main_category_name !== 'Tournois' && $main_category_name !== 'Vanguard' && $main_category_name !== 'Promos' && $main_category_name !== 'Accessoires généraux') : ?>

                <?php endif; ?>
            </ul>
            <!-- Affiche les sous-catégories de la catégorie actuelle -->
            <ul class="child-categories">
                <?php
                $child_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => $term->term_id,
                    'hide_empty' => true,
                ));
                ?>
                <?php foreach ($child_categories as $child_category): ?>
                    <?php $class = ($child_category->term_id == $term->term_id) ? 'class="current"' : ''; ?>
                    <li <?php echo $class; ?>>
                        <a href="<?php echo get_term_link($child_category); ?>">
                            <?php echo $child_category->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>

            </ul>

            <div class="woocommerce-pagination">
                <?php
                // Affiche la pagination WooCommerce
                woocommerce_pagination();
                ?>
            </div>
            <?php woocommerce_product_loop_start();
            if (!have_posts()) {
                if (!$is_parent && $main_category_url == '#') {
                    echo '<h2 class="empty_message">Cette catégorie ne contient actuellement aucun produit ou sous-catégorie.</h2>';
                } else {
                    echo '<h2 class="empty_message">Aucun produit trouvé dans cette catégorie.</h2>';
                }
            }
            while (have_posts()) : the_post();
                $time = ($aos_index % 5) * 100;
                set_query_var('time', $time);
                get_template_part('template-parts/product/card');
                $aos_index++;
            endwhile;
            woocommerce_product_loop_end(); ?>
            <div class="woocommerce-pagination">
                <?php
                // Affiche la pagination WooCommerce
                woocommerce_pagination();
                ?>
            </div>
        </div>

    <?php
    elseif ($is_child && !$is_parent) : ?>
        <?php if ($intermediate_parent->parent == 0) : ?>
            <?php get_template_part('template-parts/navigation/categorie-header'); ?>

            <ul class="sibling-categories">
                <li><a href="<?php echo get_term_link($term->parent); ?>">Nouveautés</a></li>
                <?php
                $sibling_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => $term->parent,
                    'hide_empty' => true,
                ));
                ?>
                <?php foreach ($sibling_categories as $sibling_category): ?>
                    <?php $class = ($sibling_category->term_id == $term->term_id) ? 'class="current"' : ''; ?>
                    <li <?php echo $class; ?>>
                        <a href="<?php echo get_term_link($sibling_category); ?>">
                            <?php echo $sibling_category->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if ($main_category_name !== 'Tournois' && $main_category_name !== 'Vanguard' && $main_category_name !== 'Promos' && $main_category_name !== 'Accessoires généraux') : ?>

                <?php endif; ?>
            </ul>
        <?php else : ?>
            <div class="final-child-category-template">
                <?php get_template_part('template-parts/navigation/categorie-header'); ?>

                <!-- Vérifier si la catégorie actuelle n'est pas un enfant direct de la catégorie principale -->
                <?php
                // Récupérer la catégorie parente principale
                $parent_category = get_term($term->parent, 'product_cat');

                // Récupérer les sœurs de la catégorie parente (les catégories ayant la même parente que la catégorie principale)
                $sibling_parent_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => $parent_category->parent, // Sœurs de la catégorie parente
                    'hide_empty' => true,
                )); ?>

                <!-- Afficher les sœurs de la catégorie parente -->
                <ul class="parent-sibling-categories">
                    <li><a href="<?php echo get_term_link($parent_category->parent); ?>">Nouveautés</a></li>
                    <?php foreach ($sibling_parent_categories as $sibling_parent_category): ?>
                        <?php $class = ($sibling_parent_category->term_id == $term->term_id) ? 'class="current"' : ''; ?>
                        <li <?php echo $class; ?>>
                            <a href="<?php echo get_term_link($sibling_parent_category); ?>">
                                <?php echo $sibling_parent_category->name; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($main_category_name !== 'Tournois' && $main_category_name !== 'Vanguard' && $main_category_name !== 'Promos' && $main_category_name !== 'Accessoires généraux') : ?>

                    <?php endif; ?>
                </ul>

                <ul class="sibling-categories">
                    <?php
                    $sibling_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'parent' => $term->parent,
                        'hide_empty' => true,
                    ));

                    foreach ($sibling_categories as $sibling_category): ?>
                        <?php $class = ($sibling_category->term_id == $term->term_id) ? 'class="current"' : ''; ?>
                        <li <?php echo $class; ?>>
                            <a href="<?php echo get_term_link($sibling_category); ?>">
                                <?php echo $sibling_category->name; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="woocommerce-pagination">
            <?php
            // Affiche la pagination WooCommerce
            woocommerce_pagination();
            ?>
        </div>
        <?php woocommerce_product_loop_start();
        if (!have_posts()) {
            if (!$is_parent && $main_category_url == '#') {
                echo '<h2 class="empty_message">Cette catégorie ne contient actuellement aucun produit ou sous-catégorie.</h2>';
            } else {
                echo '<h2 class="empty_message">Aucun produit trouvé dans cette catégorie.</h2>';
            }
        }
        while (have_posts()) : the_post();
            $time = ($aos_index % 5) * 100;
            set_query_var('time', $time);
            get_template_part('template-parts/product/card');
            $aos_index++;
        endwhile;
        woocommerce_product_loop_end(); ?>
        <div class="woocommerce-pagination">
            <?php
            // Affiche la pagination WooCommerce
            woocommerce_pagination();
            ?>
        </div>
    <?php elseif (!$is_parent && !$is_child) : ?>
        <div class="main-parent-category-template">
            <?php get_template_part('template-parts/navigation/categorie-header'); ?>

            <div class="woocommerce-pagination">
                <?php woocommerce_pagination(); ?>
            </div>

            <?php woocommerce_product_loop_start();

            if (!have_posts()) {
                echo '<h2 class="empty_message">Aucun produit trouvé dans cette catégorie.</h2>';
            }

            while (have_posts()) : the_post();
                $time = ($aos_index % 5) * 100;
                set_query_var('time', $time);
                get_template_part('template-parts/product/card');
                $aos_index++;
            endwhile;

            woocommerce_product_loop_end(); ?>

            <div class="woocommerce-pagination">
                <?php woocommerce_pagination(); ?>
            </div>
        </div>

    <?php endif; ?>
<?php else : ?>
    <?php
    /**
     * Hook: woocommerce_before_shop_loop.
     *
     * @hooked woocommerce_output_all_notices - 10
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */
    do_action('woocommerce_before_shop_loop');

    woocommerce_product_loop_start();

    if (wc_get_loop_prop('total')) {
        while (have_posts()) {
            the_post();

            /**
             * Hook: woocommerce_shop_loop.
             */
            do_action('woocommerce_shop_loop');

            wc_get_template_part('content', 'product');
        }
    }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action('woocommerce_after_shop_loop'); ?>
<?php endif; ?>



<?php get_footer('shop'); ?>