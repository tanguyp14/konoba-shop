<?php
$fields = get_fields();
extract($fields);
?>
<?php if (!empty($slider)) : ?>
<section class="tylt_swiper">
	<div class="swiper">
		<?php foreach ($slider as $s) : 
			extract($s); // => $banner_desk, $banner_mobile, $lien, $titre, $sous_titre, $texte_du_bouton
		?>
			<div class="slide">
				<?php if (!empty($lien)) : ?><a href="<?php echo esc_url($lien); ?>"><?php endif; ?>

					<img 
						class="banner-img"
						src="<?php echo esc_url(wp_get_attachment_image_url($banner_desk, 'full')); ?>" 
						data-desk="<?php echo esc_url(wp_get_attachment_image_url($banner_desk, 'full')); ?>" 
						data-mobile="<?php echo esc_url(wp_get_attachment_image_url($banner_mobile, 'full')); ?>" 
						alt="<?php echo esc_attr($titre); ?>"
					>

					<div class="banner-content">
						<?php if (!empty($titre)) : ?><h1 data-aos="fade-left"><?php echo esc_html($titre); ?></h1><?php endif; ?>
						<?php if (!empty($sous_titre)) : ?><h2 data-aos="fade-left" data-aos-delay="200"><?php echo esc_html($sous_titre); ?></h2><?php endif; ?>
						<?php if (!empty($texte_du_bouton)) : ?>
							<span data-aos="fade-left" data-aos-delay="400" class="banner-button"><?php echo esc_html($texte_du_bouton); ?></span>
						<?php endif; ?>
					</div>

				<?php if (!empty($lien)) : ?></a><?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>
