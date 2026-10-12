/* Telson Optics core: dialog helper, navigation, announcement bar, age gate. */
( function () {
	'use strict';

	var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])';
	var mobileQuery = window.matchMedia( '(max-width: 1024px)' );
	var openDialogs = [];

	function safeStorage( type ) {
		try {
			return window[ type ];
		} catch ( e ) {
			return null;
		}
	}

	function trapFocus( event ) {
		var current = openDialogs[ openDialogs.length - 1 ];
		if ( ! current ) {
			return;
		}
		if ( event.key === 'Escape' && ! current.persistent ) {
			closeDialog( current.el );
			return;
		}
		if ( event.key !== 'Tab' ) {
			return;
		}
		var nodes = Array.prototype.filter.call( current.el.querySelectorAll( FOCUSABLE ), function ( node ) {
			return node.offsetParent !== null;
		} );
		if ( ! nodes.length ) {
			return;
		}
		var first = nodes[ 0 ];
		var last = nodes[ nodes.length - 1 ];
		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	}

	function openDialog( el, options ) {
		if ( ! el || ! el.hidden ) {
			return;
		}
		options = options || {};
		el.hidden = false;
		openDialogs.push( { el: el, trigger: document.activeElement, persistent: !! options.persistent } );
		document.body.classList.add( 'is-locked' );
		var target = el.querySelector( options.focus || '[data-autofocus]' ) || el.querySelector( FOCUSABLE );
		if ( target ) {
			target.focus();
		}
		el.dispatchEvent( new CustomEvent( 'telson:open', { bubbles: true } ) );
	}

	function closeDialog( el ) {
		var index = openDialogs.findIndex( function ( entry ) {
			return entry.el === el;
		} );
		if ( index === -1 ) {
			return;
		}
		var entry = openDialogs.splice( index, 1 )[ 0 ];
		el.hidden = true;
		if ( ! openDialogs.length ) {
			document.body.classList.remove( 'is-locked' );
		}
		if ( entry.trigger && entry.trigger.focus ) {
			entry.trigger.focus();
		}
		el.dispatchEvent( new CustomEvent( 'telson:close', { bubbles: true } ) );
	}

	document.addEventListener( 'keydown', trapFocus );
	window.Telson = { openDialog: openDialog, closeDialog: closeDialog, storage: safeStorage };

	/* Navigation */
	var nav = document.getElementById( 'primary-nav' );
	var navToggle = document.querySelector( '.nav-toggle' );

	function setNav( open ) {
		if ( ! nav || ! navToggle ) {
			return;
		}
		nav.classList.toggle( 'is-open', open );
		navToggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		document.body.classList.toggle( 'is-locked', open && mobileQuery.matches );
	}

	if ( nav && navToggle ) {
		navToggle.addEventListener( 'click', function () {
			setNav( navToggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );
		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
				setNav( false );
				navToggle.focus();
			}
		} );
		mobileQuery.addEventListener( 'change', function () {
			setNav( false );
		} );

		nav.addEventListener( 'click', function ( event ) {
			var button = event.target.closest( '.submenu-toggle' );
			if ( ! button ) {
				return;
			}
			var item = button.closest( 'li' );
			var open = ! item.classList.contains( 'is-open' );
			item.classList.toggle( 'is-open', open );
			button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );
	}

	/* Announcement bar */
	var bar = document.querySelector( '[data-announcement]' );
	var session = safeStorage( 'sessionStorage' );
	if ( bar ) {
		try {
			if ( session && session.getItem( 'telsonAnnouncementClosed' ) ) {
				bar.hidden = true;
			}
		} catch ( e ) {}
		var closeBar = bar.querySelector( '[data-announcement-close]' );
		if ( closeBar ) {
			closeBar.addEventListener( 'click', function () {
				bar.hidden = true;
				try {
					session.setItem( 'telsonAnnouncementClosed', '1' );
				} catch ( e ) {}
			} );
		}
	}

	/* Age gate */
	var gate = document.getElementById( 'age-gate' );
	var data = window.telsonData || {};
	if ( gate && data.ageGate ) {
		var cookieName = 'telson_age_ok';
		var confirmed = document.cookie.split( '; ' ).some( function ( part ) {
			return part.indexOf( cookieName + '=1' ) === 0;
		} );
		if ( ! confirmed ) {
			openDialog( gate, { persistent: true, focus: '[data-age-confirm]' } );
		}
		var yes = gate.querySelector( '[data-age-confirm]' );
		var no = gate.querySelector( '[data-age-deny]' );
		if ( yes ) {
			yes.addEventListener( 'click', function () {
				var expires = new Date( Date.now() + ( data.ageDays || 30 ) * 864e5 ).toUTCString();
				document.cookie = cookieName + '=1; expires=' + expires + '; path=/; SameSite=Lax';
				closeDialog( gate );
			} );
		}
		if ( no ) {
			no.addEventListener( 'click', function () {
				window.location.href = data.ageExit || 'https://www.google.com/';
			} );
		}
	}
}() );
