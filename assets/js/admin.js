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
			this.vars = {};
			this.els.$startDate = $( '[name="woo_store_vacation_options[start_date]"]' );
			this.els.$endDate = $( '[name="woo_store_vacation_options[end_date]"]' );
			this.vars.dayInSeconds = 24 * 60 * 60 * 1000;
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
			// Add delete button to datepicker.
			this.addDeleteButtonDatepicker();

			// Common options for datepickers.
			const commonDatepickerOptions = {
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				buttonImageOnly: true,
				numberOfMonths: 1,
				showOn: 'focus',
				dateFormat: 'yy-mm-dd',
				beforeShowDay( date ) {
					const startDate = admin.els.$startDate.datepicker( 'getDate' );
					const endDate = admin.els.$endDate.datepicker( 'getDate' );
					if ( startDate && endDate && date >= startDate && date <= endDate ) {
						return [ true, 'highlight', '' ];
					}
					return [ true, '', '' ];
				},
			};

			this.els.$startDate.datepicker( {
				...commonDatepickerOptions,
				beforeShow() {
					const maxDate = admin.els.$endDate.val();
					if ( maxDate ) {
						$( this ).datepicker(
							'option',
							'maxDate',
							new Date( Date.parse( maxDate ) - admin.vars.dayInSeconds )
						);
					}
				},
				onClose( selectedDate ) {
					const minDate = new Date( Date.parse( selectedDate ) + admin.vars.dayInSeconds );
					admin.els.$endDate.datepicker( 'option', 'minDate', minDate );
				},
			} );

			this.els.$endDate.datepicker( {
				...commonDatepickerOptions,
				minDate: '+1D',
				beforeShow() {
					const minDate = admin.els.$startDate.val();
					if ( minDate ) {
						$( this ).datepicker(
							'option',
							'minDate',
							new Date( Date.parse( minDate ) + admin.vars.dayInSeconds )
						);
					}
				},
				onClose( selectedDate ) {
					const maxDate = new Date( Date.parse( selectedDate ) - admin.vars.dayInSeconds );
					admin.els.$startDate.datepicker( 'option', 'maxDate', maxDate );

					// Check if endDate has passed today's date
					const today = new Date();

					const endDate = new Date( selectedDate );
					endDate.setHours( 0, 0, 0, 0 );

					// If endDate is less than today's date, show error color.
					$( this ).toggleClass( 'end-date-error', endDate && today > endDate );
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
