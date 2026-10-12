/* Dealer directory: interactive Google Map + searchable sidebar list. */
( function () {
	'use strict';

	var root = document.getElementById( 'dealer-locator' );
	if ( ! root ) {
		return;
	}

	var dataEl = document.getElementById( 'dealer-locator-data' );
	var dealers = [];
	try {
		dealers = dataEl ? JSON.parse( dataEl.textContent ) : [];
	} catch ( e ) {
		dealers = [];
	}

	var list = root.querySelector( '[data-dealer-list]' );
	var countEl = root.querySelector( '[data-dealer-count]' );
	var searchInput = root.querySelector( '[data-dealer-search]' );
	var items = [];
	var map = null;
	var markers = [];
	var infoWindow = null;

	function pinIcon() {
		return '<svg viewBox="0 0 20 20" aria-hidden="true"><path d="M10 0C5.8 0 2.5 3.3 2.5 7.4c0 5.6 6.6 12 7 12.4.3.3.7.3 1 0 .4-.4 7-6.8 7-12.4C17.5 3.3 14.2 0 10 0zm0 10.5c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3z"/></svg>';
	}

	function renderList( filtered ) {
		list.innerHTML = '';
		if ( ! filtered.length ) {
			var empty = document.createElement( 'li' );
			empty.className = 'dealer-locator__empty';
			empty.textContent = 'No dealers match your search.';
			list.appendChild( empty );
			if ( countEl ) {
				countEl.textContent = '0 dealers';
			}
			return;
		}

		if ( countEl ) {
			countEl.textContent = filtered.length + ( 1 === filtered.length ? ' dealer' : ' dealers' );
		}

		filtered.forEach( function ( dealer ) {
			var li = document.createElement( 'li' );
			li.className = 'dealer-locator__item';
			li.setAttribute( 'data-dealer-id', dealer.id );

			var html = '<p class="dealer-locator__item-name">' + pinIcon() + '<span>' + dealer.name + '</span></p>';
			if ( dealer.address ) {
				html += '<p class="dealer-locator__item-address">' + dealer.address + '</p>';
			}
			if ( dealer.phone ) {
				html += '<p class="dealer-locator__item-phone">' + dealer.phone + '</p>';
			}
			if ( dealer.lat && dealer.lng ) {
				html += '<a class="btn btn--outline dealer-locator__item-link" target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent( dealer.lat + ',' + dealer.lng ) + '">Get Directions</a>';
			} else if ( dealer.address ) {
				html += '<a class="btn btn--outline dealer-locator__item-link" target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent( dealer.address.replace( /<br\s*\/?>/gi, ', ' ) ) + '">Get Directions</a>';
			}
			li.innerHTML = html;

			li.addEventListener( 'click', function () {
				focusDealer( dealer );
			} );

			list.appendChild( li );
		} );
	}

	function focusDealer( dealer ) {
		list.querySelectorAll( '.dealer-locator__item' ).forEach( function ( el ) {
			el.classList.toggle( 'is-active', el.getAttribute( 'data-dealer-id' ) === String( dealer.id ) );
		} );

		if ( ! map || ! dealer.lat || ! dealer.lng ) {
			return;
		}

		var pos = { lat: parseFloat( dealer.lat ), lng: parseFloat( dealer.lng ) };
		map.panTo( pos );
		map.setZoom( Math.max( map.getZoom(), 11 ) );

		var marker = markers.filter( function ( m ) {
			return m.dealerId === dealer.id;
		} )[ 0 ];

		if ( marker && infoWindow ) {
			infoWindow.setContent( '<strong>' + dealer.name + '</strong><br>' + ( dealer.address || '' ) );
			infoWindow.open( { map: map, anchor: marker } );
		}
	}

	function applyFilter() {
		var query = ( searchInput.value || '' ).toLowerCase().trim();
		var filtered = ! query
			? dealers
			: dealers.filter( function ( dealer ) {
				return ( dealer.name + ' ' + ( dealer.address || '' ) ).toLowerCase().indexOf( query ) !== -1;
			} );
		renderList( filtered );
	}

	if ( searchInput ) {
		searchInput.addEventListener( 'input', applyFilter );
	}

	renderList( dealers );

	window.telsonInitDealerMap = function () {
		var mapEl = root.querySelector( '[data-dealer-map]' );
		if ( ! mapEl || typeof google === 'undefined' ) {
			return;
		}

		var withCoords = dealers.filter( function ( dealer ) {
			return dealer.lat && dealer.lng;
		} );

		var center = withCoords.length
			? { lat: parseFloat( withCoords[ 0 ].lat ), lng: parseFloat( withCoords[ 0 ].lng ) }
			: { lat: 49.8951, lng: -97.1384 };

		map = new google.maps.Map( mapEl, {
			center: center,
			zoom: withCoords.length ? 5 : 3,
			mapId: 'TELSON_DEALER_MAP',
		} );

		infoWindow = new google.maps.InfoWindow();

		var bounds = new google.maps.LatLngBounds();

		withCoords.forEach( function ( dealer ) {
			var pos = { lat: parseFloat( dealer.lat ), lng: parseFloat( dealer.lng ) };
			var marker = new google.maps.Marker( {
				position: pos,
				map: map,
				title: dealer.name,
			} );
			marker.dealerId = dealer.id;
			marker.addListener( 'click', function () {
				focusDealer( dealer );
			} );
			markers.push( marker );
			bounds.extend( pos );
		} );

		if ( withCoords.length > 1 ) {
			map.fitBounds( bounds );
		}
	};
} )();
