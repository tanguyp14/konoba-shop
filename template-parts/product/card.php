<?php
global $product; 
$time = get_query_var('time'); 
?>
<li class="tylt_card_product <?php echo !$product->is_in_stock() ? 'out-of-stock' : ''; ?>" data-aos="fade-up" data-aos-delay="<?php echo $time ?>" data-aos-anchor-placement="top-bottom">
    <div class="tylt_tags">
        <?php if ($product->is_featured()) : ?>
            <div class="tylt_featured_product">Nouveauté</div>
        <?php endif; ?>
        <?php if (has_term('precommande', 'product_tag', $product->get_id())) : ?>
            <div class="tylt_pre_order">Précommande</div>
        <?php endif; ?>
    </div>
    <a href="<?php echo get_permalink(); ?>">
        <div class="tylt_card_image">
            <?php if (has_post_thumbnail()) : ?>
                <?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
            <?php else : ?>
                <div class="empty">Image à venir</div>
            <?php endif; ?>
        </div>
        <h3 class="tylt_card_name"><?php echo get_the_title(); ?></h3>
        <?php if ($product->is_in_stock()) : ?>
            <div class="price"><?php echo $product->get_price_html(); ?></div>
        <?php else : ?>
            <div class="out-of-stock-message">Stock Épuisé</div>
        <?php endif; ?>
    </a>
    <div class="tylt_card_actions">
        <?php if ($product->is_in_stock()) : ?>
            <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="tylt_card_add_to_cart">Ajouter au panier</a>
        <?php endif; ?>
        <a href="<?php echo get_permalink(); ?>" class="tylt-see-more"><span>Voir plus</span></a>
    </div>
</li>