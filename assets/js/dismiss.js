/* global jQuery, ajaxurl, woo_store_vacation_params */

( function ( wp, $, ajaxurl, l10n ) {
	'use strict';

	if ( ! wp ) {
		return;
	}

	const dismiss = {
		/**
		 * Cache.
		 *
		 * @since 1.3.6
		 */
		cache() {
			this.vars = {};
			this.vars.rate = '#woo-store-vacation-dismiss-rate .notice-dismiss';
			this.vars.rate += ', #woo-store-vacation-dismiss-rate .notice-dismiss-later';
			this.vars.rated = '#woo-store-vacation-dismiss-rate .notice-dismiss-rated';
			this.vars.upsell = '#woo-store-vacation-dismiss-upsell .notice-dismiss';
		},

		/**
		 * Initialize.
		 *
		 * @since 1.3.6
		 */
		init() {
			this.cache();
			this.bindEvents();
		},

		/**
		 * Bind events.
		 *
		 * @since 1.3.6
		 */
		bindEvents() {
			$( document.body )
				.on( 'click', this.vars.rate, ( event ) => this.handleOnDismiss( event, 'rate' ) )
				.on( 'click', this.vars.rated, ( event ) => this.handleOnDismiss( event, 'rated' ) )
				.on( 'click', this.vars.upsell, ( event ) => this.handleOnDismiss( event, 'upsell' ) );
		},

		/**
		 * Handle on dismiss.
		 *
		 * @since 1.3.6
		 *
		 * @param {Object} event  Event object.
		 * @param {string} action Action to perform.
		 */
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
					_ajax_nonce: l10n.dismiss_nonce,
					action: `woo_store_vacation_dismiss_${ action }`,
				},
			} ).always( () => {
				$this.closest( 'div.notice:visible' ).slideUp();
			} );
		},
	};

	dismiss.init();
} )( window.wp, jQuery, ajaxurl, woo_store_vacation_params );
