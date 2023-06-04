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
		const input = $(this).find('.input-text')

		if(!input.is(e.target)) {
			const checkbox = $(this).find('input[type="checkbox"]')
			checkbox.prop("checked", !checkbox.prop("checked"));
			$(this).toggleClass('checked')
			if ($(this).hasClass('personal-date')) {
				$(this).find('.date-name-wrapper').slideToggle()
			}
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

		let len_items = $('.notification_dates-item').length
		$('.notification_dates-item').each(function(index) {
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

				let generated_class = `${date_slug}${name_date.replaceAll(/\s/g,'')}${last_name_date.replaceAll(/\s/g,'')}`

				if($(`.${date_slug}`).length) {
					$(`.${date_slug}`).remove()
				}
				
				let custom_date_name = ''
				if ($(this).hasClass('personal-date')) {
					custom_date_name = $(this).find('.date-name-wrapper input').val()
				}

				let html = `<div class="item_added_dates ${date_slug}"><span class="name">${name_date} ${last_name_date}</span><span class="date">${visible_date}</span><input type="hidden" value="${date_slug}||${date}||${custom_date_name}" name="notify_dates[]"><input type="hidden" name="notify_dates_name[]" value="${name_date} ${last_name_date}"><span class="delete"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></div>`
				button.before(html)
			} else {
				$(this).find('.input-date_with_datepicker').removeClass('empty')
			}

			if(index === (len_items - 1)) {
				$('#add_notification_dates').magnificPopup('close')
				$('.personal-date .date-name-wrapper').slideUp()
				$('#add_notification_dates input').each(function() {
					if($(this).attr('type') == 'checkbox') {
						$(this).removeAttr("checked");
						$(this).parent().removeClass('checked')
					} else if($(this).attr('type') == 'text') {
						$(this).val('')
					}
				})
			}
			
		})

		$('.item_added_dates .delete').on('click', function() {
			$(this).parent().remove()
		})

		return false;
	})
	
	$('#add_new_date_notification_pk').on('click', function() {
		let date_arr = {}
		const name_date = $('#add_date_new_firs_name').val() || '-'
		const last_name_date = $('#add_date_new_last_name').val() || '-'
		const button = $('a[href="#add_notification_dates"]')
		let date
		let date_slug
		let user_id = $('#current_user_id').val();

		date_arr['name'] = name_date
		date_arr['last_name'] = last_name_date
		date_arr['user_id'] = user_id

		let len_items = $('.notification_dates-item').length
		$('.notification_dates-item').each(function(index) {
			if($(this).find('input[type="checkbox"]').is(':checked')) {
				date = $(this).find('.input-date_with_datepicker').val()
				date_slug = $(this).find('input[type="checkbox"]').val()
				if(date == '') {
					$(this).find('.input-date_with_datepicker').addClass('empty')
					return false
				} else {
					let api_date = date.split('.')
					date_arr[`datesend_${date_slug}`] = `${api_date[2]}-${api_date[1]}-${api_date[0]}`
				}

				let visible_date = date.split('.')
				visible_date = `${visible_date[0]}.${visible_date[1]}`

				let generated_class = `${date_slug}${name_date.replaceAll(/\s/g,'')}${last_name_date.replaceAll(/\s/g,'')}`

				let html = `<div class="item ${date_slug}"><div class="name">${name_date} ${last_name_date}</div>`
				html += `<div class="date ">${visible_date}</div>`
				html += `<div class="change-delete"><input type="hidden" class="date_slug" value="${date_slug}"><a href="#" class="change_date_pa">Change</a>`
				html += `<a href="#" class="delete_date_pa"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></a>`
				html += `</div></div>`

				if($(`.${date_slug}`).length) {
					$(`.${date_slug}`).replaceWith(html)
				} else {
					button.before(html)
				}

				let custom_date_name = ''
				if ($(this).hasClass('personal-date')) {
					date_arr[`custom_name_${date_slug}`] = $(this).find('.date-name-wrapper input').val()
				}

			} else {
				$(this).find('.input-date_with_datepicker').removeClass('empty')

			}

			if(index === (len_items - 1)) {
				$('#add_notification_dates').magnificPopup('close')
				$('.personal-date .date-name-wrapper').slideUp()
				$('#add_notification_dates input').each(function() {
					if($(this).attr('type') == 'checkbox') {
						$(this).removeAttr("checked");
						$(this).parent().removeClass('checked')
					} else if($(this).attr('type') == 'text') {
						$(this).val('')
					}
				})
			}
			
		})
		
		// измеряем длинну массива, чтобы узнать было ли что-то вырано
		if(Object.keys(date_arr).length > 3) {
			date_arr['action'] = 'add_new_date';
			console.log(date_arr);
			$.post( '/wp-admin/admin-ajax.php', date_arr, function( response ){
				console.log(response);
			} );
		}

		return false;
	})

	$(document).on('click', '.personal_account_dates_wrapper .delete_date_pa', function(){
		$(this).parent().parent().remove()

		let user_id = $('#current_user_id').val()
		let date_slug = $(this).siblings('.date_slug').val()
		const data = {
			action: 'remove_date',
			user_id: user_id,
			date_slug: date_slug
		};

		$.post( '/wp-admin/admin-ajax.php', data, function( response ){
			// console.log(response);
		} );
	})
	

})( jQuery );
