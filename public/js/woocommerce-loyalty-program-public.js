(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	
	$('.datePicker_wrapper  input').datepicker();

	$('.notification_dates-item').on('click', function() {
		const checkbox = $(this).find('input[type="checkbox"]')
		checkbox.prop("checked", !checkbox.prop("checked"));
		$(this).toggleClass('checked')
	})

	$('#add_new_date_notification').on('click', function() {
		const wrapper_date = $('#add_notification_dates')
		const name_date = $('#add_date_new_firs_name').val()
		$('#add_date_new_firs_name').val('')
		const last_name_date = $('#add_date_new_last_name').val()
		$('#add_date_new_last_name').val('')

		$('.notification_dates-item').each(function() {
			if($(this).find('input[type="checkbox"]').is(':checked')) {

			}
		})

		return false;
	})
	

})( jQuery );
