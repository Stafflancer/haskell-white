/**
 * File modal.js
 *
 * Deal with multiple modals and their media.
 */

// Make sure everything is loaded first.
if (('complete' === document.readyState || 'loading' !== document.readyState) && !document.documentElement.doScroll) {
	hwcpaModals();
} else {
	document.addEventListener('DOMContentLoaded', hwcpaModals);
}

/**
 * Fire off our modal functions.
 *
 * @author Aivaras Stankevicius
 * @since December 30, 2022
 */
function hwcpaModals() {
	const modalTrigger = document.querySelectorAll('.modal-trigger'),
		modalClose = document.querySelectorAll('.modal-close'),
		pageBody = document.body;

	// Loop through each modal trigger on the page and add a listener for its header.
	modalTrigger.forEach((trigger) => {
		trigger.addEventListener('click', openModal);
	});

	modalClose.forEach((trigger) => {
		trigger.addEventListener('click', closeModalOnCloseButton);
	});

	pageBody.addEventListener('keydown', closeOnEscape);
	pageBody.addEventListener('click', closeOnClick);

	/**
	 * Open a modal when we trigger it.
	 *
	 * @author Aivaras Stankevicius
	 * @since December 30, 2022
	 * @param {Object} event The triggered event.
	 */
	function openModal(event) {
		const thisTarget = event.target,
			thisModalTarget = thisTarget.getAttribute('data-target'),
			thisModal = document.querySelector(thisModalTarget),
			modalDialog = thisModal.querySelector('.modal-dialog'),
			modalIframe = thisModal.querySelector('iframe'),
			focusableChildren = thisModal.querySelectorAll(
				'a, input, button'
			);

		pageBody.classList.add('modal-opened');
		thisModal.classList.remove('modal-closed');
		thisModal.classList.add('modal-opened');
		modalDialog.classList.remove('is-closed');
		modalDialog.classList.add('is-opened');
		thisModal.setAttribute('aria-hidden', false);

		if (modalIframe) {
			const iframeURL = modalIframe.getAttribute('data-src');

			modalIframe.setAttribute('src', iframeURL);
		}

		if (0 < focusableChildren.length) {
			focusableChildren[0].focus();
		}
	}

	/**
	 * Close a modal when we hit the close button.
	 *
	 * @author Aivaras Stankevicius
	 * @since December 30, 2022
	 * @param {Object} event The triggered event.
	 */
	function closeModalOnCloseButton(event) {
		if (!pageBody.classList.contains('modal-opened')) {
			return;
		}

		closeModal();
	}

	/**
	 * Close the modal when we hit the escape key.
	 *
	 * @author Aivaras Stankevicius
	 * @since December 30, 2022
	 * @param {Object} event The triggered event.
	 */
	function closeOnEscape(event) {
		if (!pageBody.classList.contains('modal-opened')) {
			return;
		}

		if (27 === event.keyCode) {
			closeModal();
		}
	}

	/**
	 * Close the modal when we hit outside the modal area.
	 *
	 * @author Aivaras Stankevicius
	 * @since December 30, 2022
	 * @param {Object} event The triggered event.
	 */
	function closeOnClick(event) {
		if (!pageBody.classList.contains('modal-opened')) {
			return;
		}

		const clickedElement = event.target;

		if (clickedElement.classList.contains('modal-content') || clickedElement.classList.contains('modal-overlay')) {
			closeModal();
		}
	}

	/**
	 * Close the active modal by targeting currently open one.
	 *
	 * @author Aivaras Stankevicius
	 * @since December 30, 2022
	 */
	function closeModal() {
		const currentlyOpenModal = document.querySelector(
				'.modal.modal-opened'
			),
			modalDialog = currentlyOpenModal.querySelector('.modal-dialog'),
			modalIframe = currentlyOpenModal.querySelector('iframe');

		modalDialog.classList.remove('is-opened');
		modalDialog.classList.add('is-closed');

		if (modalIframe) {
			modalIframe.setAttribute('src', '');
		}

		setTimeout(function () {
			currentlyOpenModal.setAttribute('aria-hidden', true);
			currentlyOpenModal.classList.remove('modal-opened');
			currentlyOpenModal.classList.add('modal-closed');
			pageBody.classList.remove('modal-opened');
		}, 350);
	}
}
