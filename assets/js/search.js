/* Search overlay with live results from /wp-json/telson/v1/search. */
( function () {
	'use strict';

	var overlay = document.getElementById( 'search-overlay' );
	var data = window.telsonData;
	if ( ! overlay || ! data || ! window.Telson ) {
		return;
	}

	var input = overlay.querySelector( '[data-search-input]' );
	var results = overlay.querySelector( '[data-search-results]' );
	var timer = null;
	var controller = null;

	document.querySelectorAll( '[data-search-open]' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			window.Telson.openDialog( overlay, { focus: '[data-search-input]' } );
		} );
	} );

	overlay.addEventListener( 'click', function ( event ) {
		if ( event.target === overlay || event.target.closest( '[data-search-close]' ) ) {
			window.Telson.closeDialog( overlay );
		}
	} );

	function group( title, items, withPrice ) {
		if ( ! items.length ) {
			return null;
		}
		var wrap = document.createElement( 'div' );
		wrap.className = 'search-results__group';
		var heading = document.createElement( 'h2' );
		heading.className = 'search-results__title';
		heading.textContent = title;
		wrap.appendChild( heading );

		items.forEach( function ( item ) {
			var link = document.createElement( 'a' );
			link.className = 'search-results__item';
			link.href = item.url;
			if ( withPrice && item.image ) {
				var img = document.createElement( 'img' );
				img.src = item.image;
				img.alt = '';
				img.width = 56;
				img.height = 56;
				link.appendChild( img );
			}
			var name = document.createElement( 'span' );
			name.textContent = item.title;
			link.appendChild( name );
			if ( withPrice && item.price ) {
				var price = document.createElement( 'span' );
				price.className = 'search-results__price';
				price.textContent = item.price;
				link.appendChild( price );
			}
			wrap.appendChild( link );
		} );
		return wrap;
	}

	function render( payload ) {
		results.textContent = '';
		var products = group( data.i18n.products, payload.products || [], true );
		var content = group( data.i18n.pages, payload.content || [], false );
		if ( ! products && ! content ) {
			var empty = document.createElement( 'p' );
			empty.textContent = data.i18n.noResults;
			results.appendChild( empty );
			return;
		}
		if ( products ) {
			results.appendChild( products );
		}
		if ( content ) {
			results.appendChild( content );
		}
	}

	function search( term ) {
		if ( controller ) {
			controller.abort();
		}
		controller = new AbortController();
		results.textContent = data.i18n.searching;
		fetch( data.restUrl + 'search?q=' + encodeURIComponent( term ), { signal: controller.signal, credentials: 'same-origin' } )
			.then( function ( response ) {
				return response.ok ? response.json() : Promise.reject( response );
			} )
			.then( render )
			.catch( function ( error ) {
				if ( error && error.name === 'AbortError' ) {
					return;
				}
				results.textContent = data.i18n.noResults;
			} );
	}

	input.addEventListener( 'input', function () {
		window.clearTimeout( timer );
		var term = input.value.trim();
		if ( term.length < 2 ) {
			results.textContent = '';
			return;
		}
		timer = window.setTimeout( function () {
			search( term );
		}, 250 );
	} );
}() );
