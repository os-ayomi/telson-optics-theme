/* Cart drawer, AJAX add-to-cart on product pages, quantity steppers.
   WooCommerce remains the source of truth: fragments refresh the drawer and header count. */
( function ( $ ) {
	'use strict';

	var drawer = document.getElementById( 'cart-drawer' );
	var data = window.telsonData || { i18n: {} };
	if ( ! window.Telson ) {
		return;
	}

	function openCart() {
		if ( drawer ) {
			window.Telson.openDialog( drawer );
		}
	}

	document.querySelectorAll( '[data-cart-open]' ).forEach( function ( button ) {
		button.addEventListener( 'click', openCart );
	} );

	if ( drawer ) {
		drawer.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '[data-cart-close]' ) ) {
				window.Telson.closeDialog( drawer );
			}
		} );
	}

	// Loop and "Pairs well with" buttons use WooCommerce's own AJAX add to cart.
	$( document.body ).on( 'added_to_cart', function () {
		openCart();
	} );

	/* Quantity steppers */
	function enhanceQuantities( scope ) {
		( scope || document ).querySelectorAll( '.quantity' ).forEach( function ( wrap ) {
			var input = wrap.querySelector( 'input.qty' );
			if ( ! input || wrap.querySelector( '.quantity__btn' ) || input.type === 'hidden' ) {
				return;
			}

			function step( direction ) {
				var min = parseFloat( input.min ) || 0;
				var max = parseFloat( input.max );
				var stepBy = parseFloat( input.step ) || 1;
				var next = ( parseFloat( input.value ) || 0 ) + direction * stepBy;
				next = Math.max( min, isNaN( max ) ? next : Math.min( max, next ) );
				input.value = next;
				input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
			}

			function makeButton( label, text, direction ) {
				var button = document.createElement( 'button' );
				button.type = 'button';
				button.className = 'quantity__btn';
				button.textContent = text;
				button.setAttribute( 'aria-label', label );
				button.addEventListener( 'click', function () {
					step( direction );
				} );
				return button;
			}

			wrap.insertBefore( makeButton( data.i18n.decrease, '−', -1 ), input );
			wrap.appendChild( makeButton( data.i18n.increase, '+', 1 ) );
		} );
	}

	enhanceQuantities();
	$( document.body ).on( 'updated_cart_totals updated_checkout wc_fragments_refreshed', function () {
		enhanceQuantities();
	} );

	/* AJAX add to cart from the single product form (simple + variable products). */
	var form = document.querySelector( 'form.cart' );
	if ( ! form || form.classList.contains( 'grouped_form' ) || form.querySelector( '.single_add_to_cart_button' ) === null ) {
		return;
	}
	if ( form.action && form.action.indexOf( window.location.host ) === -1 ) {
		return;
	}

	var message = document.createElement( 'div' );
	message.setAttribute( 'role', 'alert' );
	message.hidden = true;
	form.parentNode.insertBefore( message, form );

	form.addEventListener( 'submit', function ( event ) {
		var button = form.querySelector( '.single_add_to_cart_button' );
		if ( ! button || button.disabled || button.classList.contains( 'disabled' ) ) {
			return;
		}
		event.preventDefault();

		var body = new FormData( form );
		if ( ! body.has( 'add-to-cart' ) && button.name ) {
			body.append( button.name, button.value );
		}
		button.classList.add( 'loading' );
		button.setAttribute( 'aria-busy', 'true' );

		fetch( form.action || window.location.href, { method: 'POST', body: body, credentials: 'same-origin' } )
			.then( function ( response ) {
				return response.text();
			} )
			.then( function ( html ) {
				var doc = new DOMParser().parseFromString( html, 'text/html' );
				var error = doc.querySelector( '.woocommerce-error' );
				if ( error ) {
					message.className = 'woocommerce-error';
					message.textContent = error.textContent.trim();
					message.hidden = false;
					return;
				}
				message.hidden = true;
				$( document.body ).one( 'wc_fragments_refreshed', openCart );
				$( document.body ).trigger( 'wc_fragment_refresh' );
			} )
			.catch( function () {
				form.submit();
			} )
			.finally( function () {
				button.classList.remove( 'loading' );
				button.removeAttribute( 'aria-busy' );
			} );
	} );
}( window.jQuery ) );
