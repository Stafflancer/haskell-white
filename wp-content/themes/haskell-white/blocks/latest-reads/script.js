/**
 * File latest-reads/script.js
 *
 */
// Make sure everything is loaded first.
if (
	( 'complete' === document.readyState ||
		'loading' !== document.readyState ) &&
	! document.documentElement.doScroll
) {
	sliderLatestReads();
} else {
	document.addEventListener( 'DOMContentLoaded', sliderLatestReads );
}

/**
 * Start Slider function.
 *
 */
function sliderLatestReads() {
	new Swiper( '.latest-reads.swiper', {
		slidesPerView: 1,
		spaceBetween: 15,

		// Navigation arrows.
		navigation: {
			nextEl: '.latest-reads-nav.swiper-button-next',
			prevEl: '.latest-reads-nav.swiper-button-prev',
		},
		// Optional parameters.
		loop: true,
		a11y: true,
		preloadImages: false,
		lazy: true,
		keyboard: true,
		// Responsive breakpoints.
		breakpoints: {
			// When window width is >= 768px.
			768: {
				slidesPerView: 2,
			},
			// When window width is >= 1024px.
			1024: {
				slidesPerView: 3,
			},
			// When window width is >= 1200px.
			1200: {
				slidesPerView: 3,
				spaceBetween: 30,
			},
		},
	});
}
