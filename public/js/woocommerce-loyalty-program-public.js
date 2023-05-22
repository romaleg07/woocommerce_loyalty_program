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

	$('.notification_dates-item').on('click', function(e) {
		const input = $(this).find('.input-date_with_datepicker')

		if(!input.is(e.target)) {
			const checkbox = $(this).find('input[type="checkbox"]')
			checkbox.prop("checked", !checkbox.prop("checked"));
			$(this).toggleClass('checked')
		}
		
	})

	$('#add_new_date_notification').on('click', function() {
		let date_arr = {}
		const wrapper_date = $('#add_notification_dates')
		const name_date = $('#add_date_new_firs_name').val() || '-'
		const last_name_date = $('#add_date_new_last_name').val() || '-'
		const button = $('a[href="#add_notification_dates"]')
		let date
		let date_slug

		date_arr['name'] = name_date
		date_arr['last_name'] = last_name_date

		$('.notification_dates-item').each(function() {
			if($(this).find('input[type="checkbox"]').is(':checked')) {
				date = $(this).find('.input-date_with_datepicker').val()
				date_slug = $(this).find('input[type="checkbox"]').val()
				if(date == '') {
					$(this).find('.input-date_with_datepicker').addClass('empty')
					return false
				} else {
					date_arr[date_slug] = date
				}

				let visible_date = date.split('.')
				visible_date = `${visible_date[0]}.${visible_date[1]}`

				let html = `<div class="item_added_dates" class=""><span class="name">${name_date} ${last_name_date}</span><span class="date">${visible_date}</span><input type="hidden" value="${date}" name="not_dates[]"><input type="hidden" name="not_dates_name[] value="${name_date} ${last_name_date}"><span class="delete">x</span></div>`
				button.before(html)
			} else {
				$(this).find('.input-date_with_datepicker').removeClass('empty')
			}
			
		})

		$('#add_notification_dates input').each(function() {
			if($(this).attr('type') == 'checkbox') {
				$(this).removeAttr("checked");
				$(this).parent().removeClass('checked')
			} else if($(this).attr('type') == 'text') {
				$(this).val('')
			}
		})
		$('#add_notification_dates').magnificPopup('close')

		return false;
	})
	

})( jQuery );
