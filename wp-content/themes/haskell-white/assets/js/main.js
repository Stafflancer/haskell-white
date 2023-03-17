document.body.className = document.body.className.replace('no-js', 'js');

function hwcpaWindowReady() {
	document.body.classList.add('ready');
}

if (('complete' === document.readyState || 'loading' !== document.readyState) && !document.documentElement.doScroll) {
	hwcpaWindowReady();
} else {
	document.addEventListener('DOMContentLoaded', hwcpaWindowReady);
}

let $header = document.getElementById('masthead');

stickyNavigation = () => {
	let scrolledPage = Math.round(window.scrollY);

	if (scrolledPage > 150) {
		$header.classList.add('is-stuck');
	} else {
		$header.classList.remove('is-stuck');
	}
};

document.addEventListener('scroll', stickyNavigation);

function wowInit() {
	let wow = new WOW({
		offset: 100,         // distance to the element when triggering the animation (default is 0)
		mobile: false,       // trigger animations on mobile devices (default is true)
	});

	wow.init();
}

if (('complete' === document.readyState || 'loading' !== document.readyState) && !document.documentElement.doScroll) {
	wowInit();
} else {
	document.addEventListener('DOMContentLoaded', wowInit);
}

if (('complete' === document.readyState || 'loading' !== document.readyState) && !document.documentElement.doScroll) {
	hwcpaMobileMenu();
} else {
	document.addEventListener('DOMContentLoaded', hwcpaMobileMenu);
}

function hwcpaMobileMenu() {
	const subMenuParentItem = document.querySelectorAll('.mobile-menu li.menu-item-has-children, .utility-navigation li.menu-item-has-children');

	subMenuParentItem.forEach((subMenuParent) => {
		const menuItem = subMenuParent.querySelector('a');

		menuItem.innerHTML += '<button type="button" aria-expanded="false" class="parent-indicator caret-down" aria-label="Open submenu"><span class="down-arrow"></span></button>';

		const subMenuTrigger = document.querySelectorAll('.parent-indicator');

		subMenuTrigger.forEach((trigger) => {
			trigger.addEventListener('click', toggleSubmenu);
		});
	});

	function toggleSubmenu(event) {
		event.preventDefault();

		const targetElement = event.target, targetParent = targetElement.parentNode.closest('.menu-item-has-children'),
			subMenu = targetParent.querySelector('ul.sub-menu');

		closeAllSubmenus(targetParent);
		maybeOpenSubmenu(targetParent, subMenu);
	}

	function maybeOpenSubmenu(parent, subMenu) {
		if (parent.classList.contains('is-visible')) {
			closeSubmenu(parent, subMenu);
			return;
		}

		parent.classList.add('is-visible');
		parent
			.querySelector('.parent-indicator')
			.setAttribute('aria-expanded', true);

		subMenu.classList.add('is-visible', 'animated', 'slideInLeft');
	}

	function closeSubmenu(parent, subMenu) {
		parent.classList.remove('is-visible');
		parent
			.querySelector('.parent-indicator')
			.setAttribute('aria-expanded', false);
		subMenu.classList.remove('is-visible', 'animated', 'slideInLeft');
	}

	function closeAllSubmenus(targetParent) {
		const submenuSiblings = getSiblings(targetParent);

		submenuSiblings.forEach((sibling) => {
			sibling.classList.remove('is-visible');

			if (sibling.querySelector('.parent-indicator')) {
				sibling
					.querySelector('.parent-indicator')
					.setAttribute('aria-expanded', false);
			}

			if (sibling.querySelector('.sub-menu')) {
				sibling
					.querySelector('.sub-menu')
					.classList.remove('is-visible', 'animated', 'slideInLeft');
			}
		});
	}

	const getSiblings = function (element) {
		const siblings = [];
		let sibling = element.parentNode.firstChild;

		while (sibling) {
			if (1 === sibling.nodeType && sibling !== element) {
				siblings.push(sibling);
			}

			sibling = sibling.nextSibling;
		}

		return siblings;
	};
}

( function () {
	const subMenuParentItem = document.querySelectorAll(
		'.main-navigation .menu-item-has-children'
	);

	document.addEventListener( 'DOMContentLoaded', addDownArrow );
	document.addEventListener( 'DOMContentLoaded', toggleFocusClass );

	function addDownArrow() {
		subMenuParentItem.forEach( ( parentItem ) => {
			const menuItem = parentItem.querySelector( 'a' );
			menuItem.innerHTML +=
				'<svg height="16" width="28" class="svg-icon caret-down ms-2" aria-hidden="true" role="img"><use xlink:href="#icon-caret-down"></use></svg>';
		} );
	}

	function toggleFocusClass() {
		subMenuParentItem.forEach( ( parentItem ) => {
			parentItem.addEventListener( 'focusin', toggleIn );
			parentItem.addEventListener( 'focusout', toggleOut );
		} );
	}

	function toggleIn( event ) {
		const parentMenuItems = getParents(
			event.target.parentNode,
			'.menu-item-has-children'
		);
		parentMenuItems.forEach( ( parentItem ) => {
			parentItem.classList.add( 'focus' );
		} );
	}

	function toggleOut( event ) {
		const parentMenuItems = getParents(
			event.target.parentNode,
			'.menu-item-has-children'
		);
		parentMenuItems.forEach( ( parentItem ) => {
			parentItem.classList.remove( 'focus' );
		} );
	}

	const getParents = function ( elem, selector ) {
		if ( ! Element.prototype.matches ) {
			Element.prototype.matches =
				Element.prototype.matchesSelector ||
				Element.prototype.mozMatchesSelector ||
				Element.prototype.msMatchesSelector ||
				Element.prototype.oMatchesSelector ||
				Element.prototype.webkitMatchesSelector ||
				function ( s ) {
					const matches = (
						this.document || this.ownerDocument
					).querySelectorAll( s );
					let i = matches.length;
					while ( 0 >= --i && matches.item( i ) !== this ) {}
					return -1 > i;
				};
		}

		const parents = [];

		for ( ; elem && elem !== document; elem = elem.parentNode ) {
			if ( selector ) {
				if ( elem.matches( selector ) ) {
					parents.push( elem );
				}
			} else {
				parents.push( elem );
			}
		}

		return parents;
	};
} )();

if (('complete' === document.readyState || 'loading' !== document.readyState) && !document.documentElement.doScroll) {
	hwcpaOffCanvas();
} else {
	document.addEventListener('DOMContentLoaded', hwcpaOffCanvas);
}

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

	function closeOnEscape(event) {
		if (27 === event.keyCode) {
			closeOffCanvas();
		}
	}

	function closeOffCanvas() {
		offCanvasContainer.classList.remove('is-visible');
		offCanvasOpen.classList.remove('is-visible');
		offCanvasClose.classList.remove('is-visible');
		offCanvasScreen.classList.remove('is-visible', 'visually-visible');
		offCanvasScreen.classList.add('visually-hidden');

		offCanvasContainer.setAttribute('aria-hidden', true);
		offCanvasOpen.setAttribute('aria-expanded', false);
		offCanvasClose.setAttribute('aria-expanded', false);
	}

	function toggleOffCanvas() {
		if ('false' === offCanvasContainer.getAttribute('aria-hidden')) {
			closeOffCanvas();
		} else {
			openOffCanvas();
		}
	}

	function openOffCanvas() {
		offCanvasContainer.classList.add('is-visible');
		offCanvasOpen.classList.add('is-visible');
		offCanvasClose.classList.add('is-visible');
		offCanvasScreen.classList.remove('visually-hidden');
		offCanvasScreen.classList.add('is-visible', 'visually-visible');

		offCanvasContainer.setAttribute('aria-hidden', false);
		offCanvasOpen.setAttribute('aria-expanded', true);
		offCanvasClose.setAttribute('aria-expanded', true);
	}
}
