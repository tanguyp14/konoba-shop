<?php
$fields = get_fields();
extract($fields);
?>
<section class="tylt_adds">
    <?php if (!empty($lob)): ?>
        <?php foreach ($lob as $item): extract($item) ?>
            <div class="lob-item">
                <img src="<?php echo wp_get_attachment_url($image); ?>" alt="<?php echo esc_attr(get_post_meta($image, '_wp_attachment_image_alt', true)); ?>">
                <h3><?php echo esc_html($titre); ?></h3>
                <p><?php echo esc_html($sous_titre); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="contact">
        <a href="/contact" class="tylt-button"><span>Contactez-nous</span></a>
    </div>
</section>