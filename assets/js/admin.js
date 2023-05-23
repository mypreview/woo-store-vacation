/* global jQuery */

( function ( wp, $ ) {
	'use strict';

	if ( ! wp ) {
		return;
	}

	const { __ } = wp.i18n;
	const admin = {
		/**
		 * Cache.
		 *
		 * @since 1.0.0
		 */
		cache() {
			this.els = {};
			this.els.$startDate = $( '[name="woo_store_vacation_options[start_date]"]' );
			this.els.$endDate = $( '[name="woo_store_vacation_options[end_date]"]' );
		},

		/**
		 * Initialize.
		 *
		 * @since 1.0.0
		 */
		init() {
			this.cache();
			this.datePickers();
		},

		/**
		 * Datepickers.
		 *
		 * @since 1.0.0
		 */
		datePickers() {
			this.addDeleteButtonDatepicker();

			this.els.$startDate.datepicker( {
				changeMonth: true,
				showButtonPanel: true,
				dateFormat: 'yy-mm-dd',
				onClose( selectedDate ) {
					const minDate = new Date( Date.parse( selectedDate ) );
					minDate.setDate( minDate.getDate() + 1 );
					admin.els.$endDate.datepicker( 'option', 'minDate', minDate );
				},
			} );

			this.els.$endDate.datepicker( {
				minDate: '+1D',
				changeMonth: true,
				showButtonPanel: true,
				dateFormat: 'yy-mm-dd',
				onClose( selectedDate ) {
					const maxDate = new Date( Date.parse( selectedDate ) );
					maxDate.setDate( maxDate.getDate() - 1 );
					admin.els.$startDate.datepicker( 'option', 'maxDate', maxDate );
				},
			} );

			// Override the _goToToday method outside the library itself.
			const oldGoToToday = $.datepicker._gotoToday;
			$.datepicker._gotoToday = function ( id ) {
				oldGoToToday.call( this, id );
				this._selectDate( id );
			};
		},

		/**
		 * Add delete button to datepicker.
		 *
		 * @since 1.0.0
		 */
		addDeleteButtonDatepicker() {
			const oldFn = $.datepicker._updateDatepicker;

			$.datepicker._updateDatepicker = function ( inst ) {
				oldFn.call( this, inst );
				const buttonPane = $( this ).datepicker( 'widget' ).find( '.ui-datepicker-buttonpane' );
				$(
					`<button type="button" class="ui-datepicker-clean ui-state-default ui-priority-primary ui-corner-all">
						${ __( 'Delete', 'woo-store-vacation' ) }
					</button>`
				)
					.appendTo( buttonPane )
					.on( 'click', function () {
						$.datepicker._clearDate( inst.input );
					} );
			};
		},
	};

	admin.init();
} )( window.wp, jQuery );
