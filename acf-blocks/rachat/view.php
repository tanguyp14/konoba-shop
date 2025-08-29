<?php
$fields = get_fields();
extract($fields);
?>
<section class="tylt_rachat" data-aos="fade-up">
  <div class="tylt_rachat_bg">
    <?php if (!empty($image_background)) :
        $image_url = wp_get_attachment_image_url($image_background, 'full'); ?>
        <img src="<?php echo esc_url($image_url); ?>" alt="" />
    <?php endif; ?>
  </div>

  <div class="tylt_rachat_content">
    <?php if (!empty($titre)) : ?>
      <h2><?php echo $titre; ?></h2>
    <?php endif; ?>
    <?php if (!empty($zone_de_texte)) : ?>
      <p><?php echo $zone_de_texte; ?></p>
    <?php endif; ?>
  </div>

</section>

