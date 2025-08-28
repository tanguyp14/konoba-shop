(function ($) {

	function initSwiper($elements) {
		$elements.each(function () {
			const $el = $(this);
			if (!$el.hasClass('slick-initialized')) {

				$el.css('visibility', 'hidden');

				$el.on('init', function (event, slick) {
					$el.css('visibility', 'visible');
					$el.css('display', 'block');

					// Premier slide : active directement
					const $firstSlide = $(slick.$slides[0]);
					$firstSlide.addClass('is-active');
				});

				$el.on('afterChange', function (event, slick, currentSlide) {
					// Retire la classe sur tous
					$(slick.$slides).removeClass('is-active');

					// Ajoute sur le slide courant
					const $current = $(slick.$slides[currentSlide]);
					$current.addClass('is-active');
				});

				$el.slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: true,
					fade: true,
					arrows: false,
					autoplay: true,
					autoplaySpeed: 5000,
				});
			}
		});
	}

	function updateSwiperImages() {
		const isMobile = window.innerWidth < 1024;
		$('.banner-img').each(function () {
			const $img = $(this);
			const newSrc = isMobile ? $img.data('mobile') : $img.data('desk');
			if (newSrc && $img.attr('src') !== newSrc) {
				$img.attr('src', newSrc);
			}
		});

		$('.swiper.slick-initialized').slick('setPosition');
	}

	$(document).ready(function () {
		initSwiper($('.swiper'));
		updateSwiperImages();
		$(window).on('resize', updateSwiperImages);
	});

	if (typeof acf !== 'undefined') {
		acf.addAction('render_block_preview/type=swiper', function ($block) {
			initSwiper($block.find('.swiper'));
			updateSwiperImages();
		});
	}

})(jQuery);
