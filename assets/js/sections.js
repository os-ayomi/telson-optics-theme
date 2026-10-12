/* Section behaviours: scroll-snap carousels. */
( function () {
	'use strict';

	document.querySelectorAll( '[data-carousel]' ).forEach( function ( carousel ) {
		var track = carousel.querySelector( '[data-carousel-track]' );
		if ( ! track ) {
			return;
		}
		var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		function scrollByPage( direction ) {
			var slide = track.querySelector( '.carousel__slide' );
			if ( ! slide ) {
				return;
			}
			var gap = parseFloat( window.getComputedStyle( track ).columnGap ) || 0;
			track.scrollBy( { left: direction * ( slide.getBoundingClientRect().width + gap ), behavior: reduceMotion ? 'auto' : 'smooth' } );
		}

		var prev = carousel.querySelector( '[data-carousel-prev]' );
		var next = carousel.querySelector( '[data-carousel-next]' );
		if ( prev ) {
			prev.addEventListener( 'click', function () {
				scrollByPage( -1 );
			} );
		}
		if ( next ) {
			next.addEventListener( 'click', function () {
				scrollByPage( 1 );
			} );
		}
	} );
}() );
