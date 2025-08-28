<?php
$fields = get_fields();
extract($fields);
$i = 1;
?>
<section class="tylt_blocks" data-aos="fade-up">
    <img 
        class="tylt_blocks_bg" 
        src="<?php echo wp_get_attachment_url($background_mobile); ?>" 
        data-mobile="<?php echo wp_get_attachment_url($background_mobile); ?>" 
        data-desk="<?php echo wp_get_attachment_url($background_desk); ?>" 
        alt=""
    >
    <div class="tylt_blocks_container">
    <?php if (!empty($blocs)): ?>
        <?php foreach ($blocs as $item): extract($item) ?>
            <div class="tylt_blocks_container_item" data-aos="fade-up" data-aos-delay="<?php echo $i ?>00" >
                <img style="animation-delay: <?php echo $i ?>s" src="<?php echo wp_get_attachment_url($image); ?>" alt="<?php echo esc_attr(get_post_meta($image, '_wp_attachment_image_alt', true)); ?>">
                <h3><?php echo esc_html($titre); ?></h3>
            </div>
        <?php $i++; endforeach;  ?>
    <?php endif; ?>
    </div>
</section>