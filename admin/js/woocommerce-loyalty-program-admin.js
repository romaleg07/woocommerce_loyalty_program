(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	$('.addDatepicker').datepicker({ dateFormat: 'dd/mm/yy' });

	if($('.statistic-wrapper').length) {
		get_users_with_cupons(1);
		get_registered_users_count('all');
		get_registered_users_count_period('all');
		get_activated_coupons_count('all');
		get_coupon_count('all');

		// const data = {
		// 	action: 'get_statistics',
		// };

		// jQuery.post( '/wp-admin/admin-ajax.php', data, function( response ){
		// 	console.log(response);

		// 	$('.statistic-wrapper').removeClass('loading');
		// } );

	}

	$('.statistic-period').on('click', function() {
		$('.statistic-wrapper').addClass('loading');
		let period = $(this).data('period');
		get_registered_users_count_period(period);
		get_activated_coupons_count(period);
		get_coupon_count(period);
		return false;
	})

	$('#load_page').on('click', function() {
		$('.statistic-wrapper').addClass('loading');
		let page = $('#users_page_loyalty').val()
		get_users_with_cupons(page);
		return false;
	})

	function get_users_with_cupons($page) {

		const data4 = {
			action: 'get_users_with_coupons',
			page: $page
		};

		jQuery.post( '/wp-admin/admin-ajax.php', data4, function( response ){
			let html = '';
			for (let user of response) {
				html += `<tr>`
				html += `<td><a href="/wp-admin/user-edit.php?user_id=${user.ID}" target="_blank">${user.data.first_name} ${user.data.last_name}(${user.data.user_email})</a></td>`
				html += `<td>${user.data.all_coupons}</td>`
				html += `<td>${user.data.used_coupons}</td>`
				html += `</tr>`
			}

			$('#users-with-coupons tbody').html(html)
			$('.statistic-wrapper').removeClass('loading');
		} );
	}

	function get_registered_users_count($period) {
		const data = {
			action: 'get_registered_users_count',
			period: $period
		};

		jQuery.post( '/wp-admin/admin-ajax.php', data, function( response ){
			$('#all_users_count').html(response)
			create_pagination(response)
		} );
	}

	function get_registered_users_count_period($period) {
		const data = {
			action: 'get_registered_users_count',
			period: $period
		};

		jQuery.post( '/wp-admin/admin-ajax.php', data, function( response ){
			$('#all_users_count_new').html(response)
		} );
	}

	function get_activated_coupons_count($period) {
		const data = {
			action: 'get_activated_coupons_count',
			period: $period
		};

		jQuery.post( '/wp-admin/admin-ajax.php', data, function( response ){
			$('#activated_coupons_count').html(response)
		} );
	}

	function get_coupon_count($period) {
		const data = {
			action: 'get_coupon_count',
			period: $period
		};

		jQuery.post( '/wp-admin/admin-ajax.php', data, function( response ){
			$('#all_coupon_count').html(response)
			$('.statistic-wrapper').removeClass('loading');
		} );
	}

	function create_pagination(users) {
		let x = users, y = 10;
		let pages = Math.floor(x/y + 1)
		$('#pages').html(pages)
		$('#users_page_loyalty').attr('min', 1)
		$('#users_page_loyalty').attr('max', pages)
	}
 
})( jQuery );
