<?php

class LoyaltyProgramWebhook {
    
    public function add_webhook() {
		add_action('admin_post_nopriv_get_data_from_sendpulse',  [ $this, 'process_data_form_tyepform_webhook' ], 10);

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-loyalty-program-sendpulse-api.php';
		$sendPulse = new SendPulseApi;
		$webhook = home_url('/') . 'wp-admin/admin-post.php?action=get_data_from_sendpulse';
		$sendPulse->add_new_webhook($webhook);
	}

	public function process_data_form_tyepform_webhook() {
		$request = file_get_contents('php://input'); // get data from webhoook
		$data = json_decode($request, true);

		if(isset($data[0]->email) and !empty($data[0]->email)){
			$email = $data[0]->email;
			$task_id = $data[0]->task_id;
			$this->add_new_promocode($email, $task_id);
		}
	}

	public function add_new_promocode($email, $task_id) {
		if ( $user = get_user_by('email', $email) ):

			$customer_id = $user->ID;
			$args = array(
				'meta_key' => '_id_email_sendpulse',
				'meta_value' => $task_id,
				'post_type' => 'loyalty_program',
				'post_status' => 'publish',
				'posts_per_page' => -1
			);
			$posts = get_posts($args);

			if($posts) {
				$id_coupon = $posts[0]->ID;

				$amount = get_post_meta($id_coupon, 'discount_amount_key', true);


				$name_celebrate = $posts[0]->post_name;
				$all_string = $name_celebrate . time();
				$key_hash = strtolower(substr(sha1($all_string), 0, 10));
				
				$discount_type = 'percent';
				$coupon_code = 'DT' . $customer_id . 'EE' . $key_hash;
				$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => '',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type' => 'shop_coupon' );

			
				if(empty($amount)) {
					$amount = "10";
				}


				$customer_email = $user->user_email;

				$new_coupon_id = wp_insert_post( $coupon );

				$old_date = get_post_meta(  $customer_id, 'test_date_date' . $posts[0]->post_name, true);

				$new_date = strtotime($old_date . "+1 year");

				$new_date_str = (string)date('d.m.Y', $new_date );

				update_post_meta(  $customer_id, 'test_date_date' . $posts[0]->post_name, $new_date_str);

				$date_exp = strtotime($new_date_str . "+1 days");
				$date_start = strtotime($new_date_str . "-6 days");

				$data_array = array("email" => $customer_email);
				$data_array['variables'] = array();

				if ( $new_coupon_id ) {

					update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
					update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
					update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
										
					update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
					update_post_meta( $new_coupon_id, 'usage_limit', '1' );
					update_post_meta( $new_coupon_id, 'expiry_date', $date_exp );
					update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
					update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
					update_post_meta( $new_coupon_id, 'customer_email', array($customer_email) );

					update_post_meta( $new_coupon_id, 'date_expires', $date_exp );
					update_post_meta( $new_coupon_id, '_wt_coupon_start_date', date('Y-m-d', $date_start) );

					update_post_meta( $new_coupon_id, '_user_id', $customer_id );
					
					$data_array['variables']['coupon_' . $posts[0]->post_name] = $new_coupon_id;
				} else {
				
					update_user_meta( $customer_id, '_coupon_error', 'create error' );
				}

			}

		endif;
	}
}