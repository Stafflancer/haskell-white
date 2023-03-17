/**
 * File: off-canvas.js
 *
 * Help deal with the off-canvas mobile menu.
 */

// Make sure everything is loaded first.
if (
	('complete' === document.readyState || 'loading' !== document.readyState) &&
	!document.documentElement.doScroll
) {
	hwcpaOffCanvas();
} else {
	document.addEventListener('DOMContentLoaded', hwcpaOffCanvas);
}

/**
 * Kick off our off canvas functions.
 *
 * @author Corey Collins
 * @since January 31, 2020
 */
function hwcpaOffCanvas() {
	const offCanvasScreen = document.querySelector('.off-canvas-screen');

	if (!offCanvasScreen) {
		return;
	}

	const offCanvasContainer = document.querySelector('.off-canvas-container'),
		offCanvasOpen = document.querySelector('.off-canvas-open'),
		offCanvasClose = document.querySelector('.off-canvas-close');

	offCanvasOpen.addEventListener('click', toggleOffCanvas);
	offCanvasClose.addEventListener('click', toggleOffCanvas);
	offCanvasScreen.addEventListener('click', closeOffCanvas);
	document.body.addEventListener('keydown', closeOnEscape);

	/**
	 * Close everything when we hit the escape key.
	 *
	 * @author Corey Collins
	 * @since January 31, 2020
	 * @param {Object} event The event trigger.
	 */
	function closeOnEscape(event) {
		if (27 === event.keyCode) {
			closeOffCanvas();
		}
	}

	/**
	 * Handle closing the off-canvas overlay.
	 *
	 * @author Corey Collins
	 * @since January 31, 2020
	 */
	function closeOffCanvas() {
		offCanvasContainer.classList.remove('is-visible');
		offCanvasOpen.classList.remove('is-visible');
		offCanvasClose.classList.remove('is-visible');
		offCanvasScreen.classList.remove('is-visible', 'visually-visible');
		offCanvasScreen.classList.add('visually-hidden');

		offCanvasContainer.setAttribute('aria-hidden', true);
		offCanvasOpen.setAttribute('aria-expanded', false);
	}

	/**
	 * Toggle the display of the off-canvas overlay.
	 *
	 * @author Corey Collins
	 * @since January 31, 2020
	 */
	function toggleOffCanvas() {
		if ('true' === offCanvasOpen.getAttribute('aria-expanded')) {
			closeOffCanvas();
		} else {
			openOffCanvas();
		}
	}

	/**
	 * Handle opening the off-canvas overlay.
	 *
	 * @author Corey Collins
	 * @since January 31, 2020
	 */
	function openOffCanvas() {
		offCanvasContainer.classList.add('is-visible');
		offCanvasOpen.classList.add('is-visible');
		offCanvasClose.classList.add('is-visible');
		offCanvasScreen.classList.remove('visually-hidden');
		offCanvasScreen.classList.add('is-visible', 'visually-visible');

		offCanvasContainer.setAttribute('aria-hidden', false);
		offCanvasOpen.setAttribute('aria-expanded', true);
	}
}
