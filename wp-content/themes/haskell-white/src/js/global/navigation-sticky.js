/**
 * File navigation-sticky.js
 *
 * Add a "stuck" class to <header> when window is scrolled down > 150px.
 */
const $header = document.getElementById('masthead');

const stickyNavigation = () => {
	const scrolledPage = Math.round(window.scrollY);

	if (scrolledPage > 150) {
		$header.classList.add('is-stuck');
	} else {
		$header.classList.remove('is-stuck');
	}
};

document.addEventListener('scroll', stickyNavigation);
