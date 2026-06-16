/**
 * Front-end interactivity for the chisto-stroy/landing block.
 * Loaded only on the front end via "viewScript" in block.json.
 */
( function () {
	'use strict';

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	ready( function () {
		const roots = document.querySelectorAll( '.cs-landing' );
		roots.forEach( initLanding );
	} );

	function initLanding( root ) {
		initBurger( root );
		initCompare( root );
		initReveal( root );
		initForm( root );
	}

	/* Mobile burger menu */
	function initBurger( root ) {
		const burger = root.querySelector( '#csBurger' );
		const menu = root.querySelector( '#csMobileMenu' );
		if ( ! burger || ! menu ) {
			return;
		}
		const toggle = ( open ) => {
			const isOpen =
				open !== undefined ? open : ! menu.classList.contains( 'open' );
			menu.classList.toggle( 'open', isOpen );
			burger.classList.toggle( 'is-active', isOpen );
			burger.setAttribute( 'aria-expanded', String( isOpen ) );
		};
		burger.addEventListener( 'click', () => toggle() );
		menu.querySelectorAll( 'a' ).forEach( ( link ) =>
			link.addEventListener( 'click', () => toggle( false ) )
		);
	}

	/* Before / after comparison slider */
	function initCompare( root ) {
		root.querySelectorAll( '[data-compare]' ).forEach( ( box ) => {
			const before = box.querySelector( '.pane.before' );
			const handle = box.querySelector( '.handle' );
			if ( ! before || ! handle ) {
				return;
			}

			const set = ( pct ) => {
				const v = Math.max( 0, Math.min( 100, pct ) );
				before.style.width = v + '%';
				handle.style.left = v + '%';
				box.setAttribute( 'aria-valuenow', String( Math.round( v ) ) );
			};

			const fromEvent = ( e ) => {
				const rect = box.getBoundingClientRect();
				const x = ( e.touches ? e.touches[ 0 ].clientX : e.clientX ) - rect.left;
				set( ( x / rect.width ) * 100 );
			};

			let dragging = false;
			const start = ( e ) => {
				dragging = true;
				fromEvent( e );
			};
			const move = ( e ) => {
				if ( dragging ) {
					fromEvent( e );
				}
			};
			const end = () => {
				dragging = false;
			};

			box.addEventListener( 'mousedown', start );
			window.addEventListener( 'mousemove', move );
			window.addEventListener( 'mouseup', end );
			box.addEventListener( 'touchstart', start, { passive: true } );
			box.addEventListener( 'touchmove', move, { passive: true } );
			box.addEventListener( 'touchend', end );

			box.addEventListener( 'keydown', ( e ) => {
				const cur = parseFloat( box.getAttribute( 'aria-valuenow' ) ) || 50;
				if ( e.key === 'ArrowLeft' ) {
					set( cur - 4 );
				} else if ( e.key === 'ArrowRight' ) {
					set( cur + 4 );
				}
			} );

			set( 50 );
		} );
	}

	/* Reveal-on-scroll animation */
	function initReveal( root ) {
		const items = root.querySelectorAll( '.reveal' );
		if ( ! items.length ) {
			return;
		}
		if ( ! ( 'IntersectionObserver' in window ) ) {
			items.forEach( ( el ) => el.classList.add( 'in' ) );
			return;
		}
		const io = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'in' );
						io.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.12 }
		);
		items.forEach( ( el ) => io.observe( el ) );
	}

	/* Lead form: fake submit -> success state */
	function initForm( root ) {
		root.querySelectorAll( 'form[data-lead]' ).forEach( ( form ) => {
			const box = form.parentElement;
			const ok = box ? box.querySelector( '.form-ok' ) : null;
			form.addEventListener( 'submit', ( e ) => {
				e.preventDefault();
				if ( ! form.checkValidity() ) {
					form.reportValidity();
					return;
				}
				// TODO: replace with a real endpoint (admin-ajax / REST / CRM).
				if ( ok ) {
					form.hidden = true;
					ok.hidden = false;
				}
			} );
		} );
	}
} )();
