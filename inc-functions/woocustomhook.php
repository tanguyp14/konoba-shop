<?php

// Afficher ou non les produits en stock - Actuellement Caché
// add_action('pre_get_posts', function ($query) {
// 	if (!is_admin() && $query->is_main_query() && is_product_category()) {
// 		$query->set('meta_query', array(
// 			array(
// 				'key' => '_stock_status',
// 				'value' => 'instock',
// 				'compare' => '='
// 			),
// 		));
// 	}
// });

// Définir la nouvelle fonction storefront_cart_link
function storefront_cart_link()
{
    if (!storefront_woo_cart_available()) {
        return;
    }
?>
    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'storefront'); ?>">
        <?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?><span class="count"><div><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></div></span>
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


//CUSTOM : Related products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'custom_related_products', 20 );
function custom_related_products() {
    global $product;

    // Exemple : Récupérer des produits associés en fonction de la catégorie
    $category_ids = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'ids' ) );

    $args = array(
        'posts_per_page' => 5,
        'post_type'      => 'product',
        'post__not_in'   => array( $product->get_id() ), // Exclure le produit actuel
        'orderby'        => 'rand',
        'tax_query'      => array(
            array(
            'taxonomy' => 'product_cat',
            'field'    => 'id',
            'terms'    => $category_ids,
            'operator' => 'IN',
            ),
        ),
        'meta_query'     => array(
            array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '=',
            ),
        ),
    );

    $related_query = new WP_Query( $args );

    if ( $related_query->have_posts() ) {
        echo '<div class="related-products">';
        echo '<h2>' . esc_html__( 'Related products', 'woocommerce' ) . '</h2>';
        echo '<ul class="products columns-5">';

        while ( $related_query->have_posts() ) {
            $related_query->the_post();
            global $product;
            wc_get_template_part( 'template-parts/product/card', null, [ 'product' => $product ] );
        }

        echo '</ul>';
        echo '</div>';
        wp_reset_postdata();
    }
}


// Modification de la page produit-single
add_action( 'woocommerce_single_product_summary', 'afficher_categories_au_desus_du_titre', 0 );
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
add_filter( 'woocommerce_product_tabs', '__return_empty_array' );

add_action( 'woocommerce_single_product_summary', 'afficher_description_complete_sous_titre', 6 );
function afficher_description_complete_sous_titre() {
    global $product;
    echo '<div class="custom-description">';
    echo wp_kses_post( $product->get_description() );
    echo '</div>';
}

// Afficher les catégories sous forme de liens cliquables
function afficher_categories_au_desus_du_titre() {
    global $product;

    // Récupérer les catégories du produit
    $terms = get_the_terms( $product->get_id(), 'product_cat' );

    if ( $terms && ! is_wp_error( $terms ) ) {
        $categories = array();

        // Parcourir les catégories et créer des liens
        foreach ( $terms as $term ) {
            $category_link = get_term_link( $term ); // Récupérer l'URL de la catégorie
            if ( ! is_wp_error( $category_link ) ) {
                // Créer un lien cliquable pour chaque catégorie
                $categories[] = '<a href="' . esc_url( $category_link ) . '" class="product-category-link">' . esc_html( $term->name ) . '</a>';
            }
        }

        // Afficher les catégories sous forme de liens séparés par des virgules
        echo '<div class="product-categories">';
        echo implode( ', ', $categories );
        echo '</div>';
    }
}

// ADMIN : Tri des Featured products
add_filter('manage_edit-product_sortable_columns', 'add_featured_sortable_column');
function add_featured_sortable_column($sortable_columns) {
    $sortable_columns['featured'] = 'featured';
    return $sortable_columns;
}
add_action('pre_get_posts', 'custom_featured_products_admin_sorting');

function custom_featured_products_admin_sorting($query) {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'product') {
        return;
    }

    $orderby = $query->get('orderby');
    
    if ($orderby === 'featured') {
        $query->set('tax_query', array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            )
        ));
    }
}

// Désactiver le zoom sur les images de produit
add_action( 'after_setup_theme', 'remove_zoom_lightbox_theme_support', 9999 );
  
function remove_zoom_lightbox_theme_support() { 
    remove_theme_support( 'wc-product-gallery-zoom' );
    remove_theme_support( 'wc-product-gallery-lightbox' );
}


// Ajouter le champ image dans la page d'édition d'une catégorie produit WooCommerce
add_action('product_cat_add_form_fields', 'ajouter_champ_banniere_categorie', 10, 1);
add_action('product_cat_edit_form_fields', 'modifier_champ_banniere_categorie', 10, 2);

function ajouter_champ_banniere_categorie() {
    ?>
    <div class="form-field term-banner-wrap">
        <label for="term_banner"><?php _e('Bannière', 'woocommerce'); ?></label>
        <input type="hidden" id="term_banner" name="term_banner" value="">
        <div id="term_banner_preview" style="margin-top: 10px;"></div>
        <button class="upload_banner_button button"><?php _e('Ajouter une bannière', 'woocommerce'); ?></button>
    </div>
    <?php
}

function modifier_champ_banniere_categorie($term, $taxonomy) {
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

function save_term_banner_meta($term_id) {
    if (isset($_POST['term_banner'])) {
        update_term_meta($term_id, 'term_banner', intval($_POST['term_banner']));
    }
}

// Ajouter le script JS pour ouvrir la médiathèque WordPress et gérer l'upload
add_action('admin_footer', 'banner_image_script');
function banner_image_script() {
    $screen = get_current_screen();
    if ($screen && $screen->taxonomy === 'product_cat') :
    ?>
    <script>
    jQuery(document).ready(function($){
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
                $('#term_banner_preview').html('<img src="'+attachment.url+'" style="max-width:300px;" />');
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

