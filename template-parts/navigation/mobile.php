<?php

/**
 * Mobile menu — categories pulled from the "Pour la page licence" ACF block
 * on the /licences/ page, so the menu always mirrors that page's selection.
 */

function tylt_get_licence_nav_categories() {
    $page = get_page_by_path('licences');
    if (!$page) {
        return [];
    }

    $blocks = parse_blocks($page->post_content);
    foreach ($blocks as $block) {
        if (
            $block['blockName'] === 'acf/all-categorie-page-licence' &&
            !empty($block['attrs']['data'])
        ) {
            $data       = $block['attrs']['data'];
            $categories = [];
            $i          = 0;

            while (isset($data["categories_a_afficher_{$i}_categorie"])) {
                $term_id = (int) $data["categories_a_afficher_{$i}_categorie"];
                $term    = get_term($term_id, 'product_cat');

                if ($term && !is_wp_error($term)) {
                    $categories[] = $term;
                }

                $i++;
            }

            return $categories;
        }
    }

    return [];
}

$licence_categories = tylt_get_licence_nav_categories();
?>

<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('menu_tylt_mobile', 'tylt'); ?>">

    <?php if (!empty($licence_categories)) : ?>
        <div class="mobile-nav-categories">
            <?php foreach ($licence_categories as $term) :
                $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                $image        = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                $term_link    = get_term_link($term);
            ?>
                <a href="<?php echo esc_url($term_link); ?>" class="mobile-nav-categories__card">
                    <?php if ($image) : ?>
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($term->name); ?>">
                    <?php endif; ?>
                    <h2><?php echo esc_html($term->name); ?></h2>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php wp_nav_menu(array(
        'theme_location'  => 'secondary',
        'container'       => 'nav',
        'container_class' => 'footer-menu',
    )); ?>

</nav>
