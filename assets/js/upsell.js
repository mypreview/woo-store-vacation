/* global jQuery, ajaxurl, wsvVars */

( function ( wp, $ ) {
	'use strict';

	if ( ! wp ) {
		return;
	}

	const wsvUpsell = {
		cache() {
			this.vars = {};
			this.vars.rate = '#woo-store-vacation-dismiss-rate .notice-dismiss';
			this.vars.rate += ', #woo-store-vacation-dismiss-rate .notice-dismiss-later';
			this.vars.rated = '#woo-store-vacation-dismiss-rate .notice-dismiss-rated';
			this.vars.upsell = '#woo-store-vacation-dismiss-upsell .notice-dismiss';
		},

		init() {
			this.cache();
			this.bindEvents();
		},

		bindEvents() {
			$( document.body )
				.on( 'click', this.vars.rate, ( event ) => this.handleOnDismiss( event, 'rate' ) )
				.on( 'click', this.vars.rated, ( event ) => this.handleOnDismiss( event, 'rated' ) )
				.on( 'click', this.vars.upsell, ( event ) => this.handleOnDismiss( event, 'upsell' ) );
		},

		handleOnDismiss( event, action ) {
			const $this = $( event.target );

			if ( ! $this.attr( 'href' ) ) {
				event.preventDefault();
			}

			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				dataType: 'json',
				data: {
					_ajax_nonce: wsvVars.dismiss_nonce,
					action: `woo_store_vacation_dismiss_${ action }`,
				},
			} ).always( () => {
				$this.closest( 'div.notice:visible' ).slideUp();
			} );
		},
	};

	wsvUpsell.init();
} )( window.wp, jQuery );
