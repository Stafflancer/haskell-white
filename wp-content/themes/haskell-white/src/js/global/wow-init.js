/**
 * File wow-init.js
 *
 * Initiates wow animation, when window is ready.
 */
function wowInit() {
	const wow = new WOW({
		offset: 100, // distance to the element when triggering the animation (default is 0)
		mobile: false, // trigger animations on mobile devices (default is true)
	});

	wow.init();
}

if (
	('complete' === document.readyState || 'loading' !== document.readyState) &&
	!document.documentElement.doScroll
) {
	wowInit();
} else {
	document.addEventListener('DOMContentLoaded', wowInit);
}
