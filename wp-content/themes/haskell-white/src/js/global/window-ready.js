/**
 * File window-ready.js
 *
 * Add a "ready" class to <body> when window is ready.
 */
document.body.className = document.body.className.replace('no-js', 'js');

function hwcpaWindowReady() {
	document.body.classList.add('ready');
}

if (
	('complete' === document.readyState || 'loading' !== document.readyState) &&
	!document.documentElement.doScroll
) {
	hwcpaWindowReady();
} else {
	document.addEventListener('DOMContentLoaded', hwcpaWindowReady);
}
