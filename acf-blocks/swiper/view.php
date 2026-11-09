<?php
$fields = get_fields();
extract($fields);
?>
<?php if (!empty($slider)) : ?>
<section class="tylt_swiper">
	<div class="swiper">
		<?php foreach ($slider as $i => $s) :
			extract($s); // => $banner_desk, $banner_mobile, $lien, $titre, $sous_titre, $texte_du_bouton
			$desk   = wp_get_attachment_image_src($banner_desk, 'full');
			$mobile = $banner_mobile ? wp_get_attachment_image_src($banner_mobile, 'full') : false;
			$first  = (0 === $i);
		?>
			<div class="slide<?php echo $first ? ' is-active' : ''; ?>">
				<?php if (!empty($lien)) : ?><a href="<?php echo esc_url($lien); ?>"><?php endif; ?>

					<?php // <picture> : le navigateur charge directement la bonne image (mobile < 1024px), sans attendre le JS ?>
					<picture>
						<?php if ($mobile) : ?>
							<source media="(max-width: 1023px)" srcset="<?php echo esc_url($mobile[0]); ?>" width="<?php echo (int) $mobile[1]; ?>" height="<?php echo (int) $mobile[2]; ?>">
						<?php endif; ?>
						<img
							class="banner-img"
							src="<?php echo esc_url($desk ? $desk[0] : ''); ?>"
							<?php if ($desk) : ?>width="<?php echo (int) $desk[1]; ?>" height="<?php echo (int) $desk[2]; ?>"<?php endif; ?>
							alt="<?php echo esc_attr($titre); ?>"
							decoding="async"
							<?php echo $first ? 'fetchpriority="high"' : 'loading="lazy"'; ?>
						>
					</picture>

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
