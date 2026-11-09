<?php

function custom_woocommerce_product_query($q) {
    // Seulement sur le frontend et pour les requêtes principales
    if (!is_admin() && $q->is_main_query()) {
        
        // Pour la boutique et les pages de catégories/tags de produits
        if (is_shop() || is_product_category() || is_product_tag()) {
            
            // Tri par stock puis par date
            $q->set('orderby', array(
                'meta_value' => 'ASC',      // Stock status en premier
                'meta_value_num' => 'DESC', // Featured en second
                'date'       => 'DESC'      // Puis par date d'ajout
            ));
            $q->set('meta_query', array(
                'relation' => 'OR',
                array(
                    'key'     => '_stock_status',
                    'compare' => 'EXISTS',
                ),
                array(
                    'key'     => '_featured',
                    'compare' => 'EXISTS',
                ),
            ));
            $q->set('meta_key', '_stock_status');
        }
    }
}
add_action('pre_get_posts', 'custom_woocommerce_product_query');




// Définir la nouvelle fonction storefront_cart_link
function storefront_cart_link()
{
    if (!storefront_woo_cart_available()) {
        return;
    }
?>
    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'storefront'); ?>">
        <?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?><span class="count">
            <div><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></div>
        </span>
    </a>
<?php
}

// Remplacer la fonction d'origine par la nouvelle fonction
if (!function_exists('storefront_cart_link')) {
    function storefront_cart_link()
    {
        storefront_cart_link();
    }
}

// Modification de la page produit-single
add_action('woocommerce_single_product_summary', 'afficher_categories_au_desus_du_titre', 0);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
add_filter('woocommerce_product_tabs', '__return_empty_array');

add_action('woocommerce_single_product_summary', 'afficher_description_complete_sous_titre', 6);
function afficher_description_complete_sous_titre()
{
    global $product;
    // wc_format_content applique wpautop : les retours à la ligne saisis
    // dans l'éditeur deviennent des paragraphes au lieu d'être fusionnés.
    echo '<div class="custom-description">';
    echo wc_format_content(wp_kses_post($product->get_description()));
    echo '</div>';
}

/**
 * Affichage du stock sur la fiche produit : pas de quantité exacte,
 * seulement « En stock », « Stock presque épuisé » (moins de 10) ou « En rupture de stock ».
 */
function konoba_availability_text($availability, $product)
{
    if (!$product->is_in_stock()) {
        return 'En rupture de stock';
    }

    // Précommande / réapprovisionnement : on garde le message WooCommerce
    if ($product->is_on_backorder()) {
        return $availability;
    }

    if ($product->managing_stock() && $product->get_stock_quantity() < 10) {
        return 'Stock presque épuisé';
    }

    return 'En stock';
}
add_filter('woocommerce_get_availability_text', 'konoba_availability_text', 10, 2);

// Afficher les catégories sous forme de liens cliquables
function afficher_categories_au_desus_du_titre()
{
    global $product;

    // Récupérer les catégories du produit
    $terms = get_the_terms($product->get_id(), 'product_cat');

    if ($terms && ! is_wp_error($terms)) {
        $categories = array();

        // Parcourir les catégories et créer des liens
        foreach ($terms as $term) {
            $category_link = get_term_link($term); // Récupérer l'URL de la catégorie
            if (! is_wp_error($category_link)) {
                // Créer un lien cliquable pour chaque catégorie
                $categories[] = '<a href="' . esc_url($category_link) . '" class="product-category-link">' . esc_html($term->name) . '</a>';
            }
        }

        // Afficher les catégories sous forme de liens séparés par des virgules
        echo '<div class="product-categories">';
        echo implode(', ', $categories);
        echo '</div>';
    }
}



// Désactiver le zoom sur les images de produit
add_action('after_setup_theme', 'remove_zoom_lightbox_theme_support', 9999);

function remove_zoom_lightbox_theme_support()
{
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
}


// Ajouter le champ image dans la page d'édition d'une catégorie produit WooCommerce
add_action('product_cat_add_form_fields', 'ajouter_champ_banniere_categorie', 10, 1);
add_action('product_cat_edit_form_fields', 'modifier_champ_banniere_categorie', 10, 2);

function ajouter_champ_banniere_categorie()
{
?>
    <div class="form-field term-banner-wrap">
        <label for="term_banner"><?php _e('Bannière', 'woocommerce'); ?></label>
        <input type="hidden" id="term_banner" name="term_banner" value="">
        <div id="term_banner_preview" style="margin-top: 10px;"></div>
        <button class="upload_banner_button button"><?php _e('Ajouter une bannière', 'woocommerce'); ?></button>
    </div>
<?php
}

function modifier_champ_banniere_categorie($term, $taxonomy)
{
    $image_id = get_term_meta($term->term_id, 'term_banner', true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
?>
    <tr class="form-field term-banner-wrap">
        <th scope="row" valign="top"><label for="term_banner"><?php _e('Bannière', 'woocommerce'); ?></label></th>
        <td>
            <input type="hidden" id="term_banner" name="term_banner" value="<?php echo esc_attr($image_id); ?>">
            <div id="term_banner_preview" style="margin-bottom: 10px;">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 300px;">
                <?php endif; ?>
            </div>
            <button class="upload_banner_button button"><?php _e('Modifier la bannière', 'woocommerce'); ?></button>
            <button class="remove_banner_button button" style="<?php echo $image_url ? '' : 'display:none;'; ?>"><?php _e('Supprimer la bannière', 'woocommerce'); ?></button>
        </td>
    </tr>
    <?php
}

// Sauvegarder la valeur du champ image lors de l'enregistrement ou modification
add_action('created_product_cat', 'save_term_banner_meta', 10, 2);
add_action('edited_product_cat', 'save_term_banner_meta', 10, 2);

function save_term_banner_meta($term_id)
{
    if (isset($_POST['term_banner'])) {
        update_term_meta($term_id, 'term_banner', intval($_POST['term_banner']));
    }
}

// Ajouter le script JS pour ouvrir la médiathèque WordPress et gérer l'upload
add_action('admin_footer', 'banner_image_script');
function banner_image_script()
{
    $screen = get_current_screen();
    if ($screen && $screen->taxonomy === 'product_cat') :
    ?>
        <script>
            jQuery(document).ready(function($) {
                var mediaUploader;

                $('.upload_banner_button').click(function(e) {
                    e.preventDefault();

                    // If the uploader object has already been created, reopen the dialog
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }

                    // Create the media uploader
                    mediaUploader = wp.media.frames.file_frame = wp.media({
                        title: 'Choisir une image pour la bannière',
                        button: {
                            text: 'Choisir cette image'
                        },
                        multiple: false
                    });

                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        $('#term_banner').val(attachment.id);
                        $('#term_banner_preview').html('<img src="' + attachment.url + '" style="max-width:300px;" />');
                        $('.remove_banner_button').show();
                    });

                    mediaUploader.open();
                });

                $('.remove_banner_button').click(function(e) {
                    e.preventDefault();
                    $('#term_banner').val('');
                    $('#term_banner_preview').html('');
                    $(this).hide();
                });
            });
        </script>
<?php
    endif;
}

/**
 * Redirection 301 de la page boutique (/boutique) vers l'accueil.
 *
 * La boutique n'est pas exposée dans la navigation : l'entrée dans le catalogue
 * se fait par /licences et les catégories produit. Les archives de catégories,
 * d'étiquettes et la recherche ne sont pas concernées.
 *
 * Filtre `konoba_redirect_shop_to_home` pour désactiver ponctuellement.
 */
function konoba_redirect_shop_to_home()
{
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }

    if (!function_exists('is_shop') || !is_shop()) {
        return;
    }

    // is_shop() est aussi vrai sur une recherche limitée aux produits
    // (?s=...&post_type=product) et sur les flux : on les laisse passer.
    if (is_search() || is_feed()) {
        return;
    }

    // Les catégories et étiquettes produit ne sont pas is_shop() : elles restent intactes.
    if (!apply_filters('konoba_redirect_shop_to_home', true)) {
        return;
    }

    wp_safe_redirect(home_url('/'), 301);
    exit;
}
add_action('template_redirect', 'konoba_redirect_shop_to_home');

/**
 * La boutique redirigeant vers l'accueil, les liens « retour au catalogue »
 * pointent vers la liste des licences, qui est la vraie entrée du catalogue.
 */
function konoba_catalog_url()
{
    return home_url('/licences/');
}

add_filter('woocommerce_return_to_shop_redirect', 'konoba_catalog_url');
