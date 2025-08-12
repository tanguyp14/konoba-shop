<?php
$fields = get_fields();
extract($fields);
?>
<section class="tylt_all_categorie_liscence_page">
        <?php
        foreach ($categories_a_afficher as $term_array) {
                $term = $term_array['categorie'];
                $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                $image = wp_get_attachment_url($thumbnail_id);
                $term_link = get_term_link($term);
        ?>
                <div class="tylt_all_categorie_liscence_page_card">
                        <a href="<?php echo esc_url($term_link); ?>">
                                <h2><?php echo esc_html($term->name); ?></h2>
                                <?php if ($image) : ?>
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($term->name); ?>">
                                <?php endif; ?>

                        </a>
                </div>
        <?php
        }
        ?>
</section>