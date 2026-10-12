/* Shop: mobile filter drawer and dual-handle price range. */
( function () {
	'use strict';

	var panel = document.querySelector( '[data-shop-filters]' );
	if ( ! panel ) {
		return;
	}

	var backdrop = document.querySelector( '.shop-filters__backdrop' );
	function setOpen( open ) {
		panel.classList.toggle( 'is-open', open );
		if ( backdrop ) {
			backdrop.hidden = ! open;
		}
		document.body.classList.toggle( 'is-locked', open );
	}

	document.querySelectorAll( '[data-filters-open]' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			setOpen( true );
			var first = panel.querySelector( 'summary, input, button' );
			if ( first ) {
				first.focus();
			}
		} );
	} );

	document.querySelectorAll( '[data-filters-close]' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			setOpen( false );
		} );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && panel.classList.contains( 'is-open' ) ) {
			setOpen( false );
		}
	} );

	var range = panel.querySelector( '[data-price-range]' );
	if ( range ) {
		var min = range.querySelector( '[data-price-min]' );
		var max = range.querySelector( '[data-price-max]' );
		var minOut = range.querySelector( '[data-price-min-out]' );
		var maxOut = range.querySelector( '[data-price-max-out]' );
		var bounds = { min: Number( range.dataset.min ), max: Number( range.dataset.max ) };

		var sync = function ( source ) {
			var low = Number( min.value );
			var high = Number( max.value );
			if ( low > high ) {
				if ( source === min ) {
					min.value = high;
				} else {
					max.value = low;
				}
			}
			minOut.textContent = min.value;
			maxOut.textContent = max.value;
		};

		min.addEventListener( 'input', function () {
			sync( min );
		} );
		max.addEventListener( 'input', function () {
			sync( max );
		} );

		// Untouched bounds are not sent, keeping URLs clean and WooCommerce unfiltered.
		panel.querySelector( 'form' ).addEventListener( 'submit', function () {
			if ( Number( min.value ) === bounds.min ) {
				min.disabled = true;
			}
			if ( Number( max.value ) === bounds.max ) {
				max.disabled = true;
			}
		} );
	}
}() );
