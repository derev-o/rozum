/**
 * bookshelf.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2014, Codrops
 * http://www.codrops.com
 */
(function() {

	var supportAnimations = 'WebkitAnimation' in document.body.style ||
			'MozAnimation' in document.body.style ||
			'msAnimation' in document.body.style ||
			'OAnimation' in document.body.style ||
			'animation' in document.body.style,
		animEndEventNames = {
			'WebkitAnimation' : 'webkitAnimationEnd',
			'OAnimation' : 'oAnimationEnd',
			'msAnimation' : 'MSAnimationEnd',
			'animation' : 'animationend'
		},
		// animation end event name
		animEndEventName = animEndEventNames[ Modernizr.prefixed( 'animation' ) ],
		scrollWrap = document.getElementById( 'scroll-wrap' ),
		docscroll = 0,
		books = document.querySelectorAll( '#bookshelf > figure' );

	function scrollY() {
		return window.pageYOffset || window.document.documentElement.scrollTop;
	}

	function Book( el ) {
		this.el = el;
		this.book = this.el.querySelector( '.book' );
		this.ctrls = this.el.querySelector( '.buttons' );		
		this.book_info = this.el.querySelector( '.book_info' );
		this.details = this.el.querySelector( '.details' );
		// create the necessary structure for the books to rotate in 3d
		this._layout();

		this.bbWrapper = document.getElementById( this.book.getAttribute( 'data-book' ) );

		this._initEvents();
	}

	Book.prototype._layout = function() {
		if( Modernizr.csstransforms3d ) {
			/*this.book.innerHTML = '<div class="cover"><div class="front"></div><div class="inner inner-left"></div></div><div class="inner inner-right"></div>';*/
			var perspective = document.createElement( 'div' );
			perspective.className = 'perspective';
			perspective.appendChild( this.book );
			this.el.insertBefore( perspective, this.ctrls );
		}

		//this.closeDetailsCtrl = document.createElement( 'span' )
		//this.closeDetailsCtrl.className = 'close-details';
		document.getElementById( 'scroll-wrap' )
        //this.details.appendChild( this.closeDetailsCtrl );
	}

	Book.prototype._initEvents = function() {
		var self = this;
		if( !this.ctrls ) return;
		if( !this.book_info ) return;
  
document.querySelector("#bookshelf").addEventListener( 'click', function( ev ) {
            if(
                /*(ev.target != self.ctrls.querySelector( 'a:nth-child(2)' )) 
                && */
                (ev.target != self.book_info.querySelector( '.front' ))
            )
            { 
                self._hideDetails();
            }
});
       
/*
this.ctrls.querySelector( 'a:nth-child(2)' ).addEventListener( 'click', function( ev ) { ev.preventDefault(); self._showDetails(); } );
*/
        
this.book_info.querySelector( 'a' ).addEventListener( 'click', function( ev ) { ev.preventDefault(); self._showDetails();  } );
        
//this.closeDetailsCtrl.addEventListener( 'click', function() { self._hideDetails(); } );

}

	Book.prototype._showDetails = function() {
		classie.add( this.el, 'details-open' );
	}

	Book.prototype._hideDetails = function() {
        classie.remove( this.el, 'details-open' );
	}
	
	function init() {
		[].slice.call( books ).forEach( function( el ) {
			new Book( el );
		} );
	}

	init();

})();