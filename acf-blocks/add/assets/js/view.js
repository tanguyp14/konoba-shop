(function ($) {
	$(document).ready(function ($) {
		$('.tylt_blocks_bg').each(function () {
			var $img = $(this);
			function updateImage() {
				var src = window.innerWidth >= 768 ? $img.data('desk') : $img.data('mobile');
				$img.attr('src', src);
			}
			updateImage();
			$(window).on('resize', updateImage);
		});
	});
})(jQuery);