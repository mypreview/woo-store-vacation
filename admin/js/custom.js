(function($) {
    $(function() {
		$( '.woo-store-vacation-datepicker' ).datepicker({
                dateFormat : 'dd-mm-yy',
                minDate: 1,
        });
        $('.woo-store-vacation-color-field').wpColorPicker();
    }); // end of document ready
})(jQuery); // end of jQuery name space