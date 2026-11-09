(function ($) {

	function slickReady() {
		return typeof $.fn.slick === 'function';
	}

	function initSwiper($elements) {
		if (!slickReady()) {
			return;
		}

		$elements.each(function () {
			const $el = $(this);
			if (!$el.hasClass('slick-initialized')) {

				$el.on('init', function (event, slick) {
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

	$(document).ready(function () {
		initSwiper($('.swiper'));
	});

	if (typeof acf !== 'undefined') {
		acf.addAction('render_block_preview/type=swiper', function ($block) {
			initSwiper($block.find('.swiper'));
		});
	}

})(jQuery);
