<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Woocommerce_Loyalty_Program
 * @subpackage Woocommerce_Loyalty_Program/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Loyalty_Program
 * @subpackage Woocommerce_Loyalty_Program/public
 * @author     Romaleg
 */
class Woocommerce_Loyalty_Program_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $woocommerce_loyalty_program    The ID of this plugin.
	 */
	private $woocommerce_loyalty_program;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $woocommerce_loyalty_program       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public $loyalty_program_post_type;
	public function __construct( $woocommerce_loyalty_program, $version ) {
		$this->loyalty_program_post_type = 'loyalty_program';
		$this->woocommerce_loyalty_program = $woocommerce_loyalty_program;
		$this->version = $version;
		add_action( 'woocommerce_register_form', [ $this, 'add_date_fields_to_registration_form' ] );
		add_filter( 'woocommerce_created_customer', [ $this, 'save_gate_fields_on_registration' ], 10, 1 );
		add_filter ( 'woocommerce_account_menu_items', [ $this, 'add_personal_account_page'], 25 );
		add_action( 'init', [ $this, 'memorable_dates_add_endpoint'], 25 );
		add_action( 'woocommerce_account_memorable-dates_endpoint', [ $this, 'memorable_dates_content'], 25 );

		add_action( 'wp_ajax_add_new_date', [ $this, 'ajax_add_new_date'] ); 
		add_action( 'wp_ajax_nopriv_add_new_date', [ $this, 'ajax_add_new_date'] );

		add_action( 'wp_ajax_remove_date', [ $this, 'ajax_remove_date'] ); 
		add_action( 'wp_ajax_nopriv_remove_date', [ $this, 'ajax_remove_date'] );

		add_action( 'wp_ajax_change_date', [ $this, 'ajax_change_date'] ); 
		add_action( 'wp_ajax_nopriv_change_date', [ $this, 'ajax_change_date'] );

		// add_action('woocommerce_new_order', [ $this, 'count_used_coupons']);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Loyalty_Program_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Loyalty_Program_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style('jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, null ); 
		wp_enqueue_style( $this->woocommerce_loyalty_program, plugin_dir_url( __FILE__ ) . 'css/woocommerce-loyalty-program-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Loyalty_Program_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Loyalty_Program_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( $this->woocommerce_loyalty_program, plugin_dir_url( __FILE__ ) . 'js/woocommerce-loyalty-program-public.js', array( 'jquery' ), $this->version, true );

	}


	public function add_date_fields_to_registration_form() {

		$posts = get_posts( array(
			'post_type' => $this->loyalty_program_post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
		) );
		?>
			<a href="#add_notification_dates" data-open="#add_notification_dates" class="primary is-small button wp-element-button is-outline"><?php echo __( 'Add date', 'woocommerce-loyalty-program' );?></a>

			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__notification_flag">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="notification_flag" type="checkbox" id="notification_flag" value="1"> <span><?php echo __( 'send an e-mail and sms reminder about an important event. A discount code will be attached to each message, which will give you the opportunity to buy goods on our website up to 10% cheaper', 'woocommerce-loyalty-program' );?></span>
				</label>
			</p>

			<div id="add_notification_dates" class="dark text-center mfp-hide lightbox-content">
				<div class="account-container lightbox-inner">
					<div class="row row-divided row-large" id="add_user_date">
						<div class="col-1 large-12 col pb-0">
							<h3 class="uppercase"><?php echo __( 'Add date notification', 'woocommerce-loyalty-program' );?></h3>
							<p class="form-row form-row-first">
								<label for="add_date_new_firs_name"><?php echo __( 'First Name', 'woocommerce-loyalty-program' );?></label>
								<input type="text" class="input-text" name="add_date_new_firs_name" id="add_date_new_firs_name" value="">
							</p>
							<p class="form-row form-row-last">
								<label for="add_date_new_firs_name"><?php echo __( 'Last Name', 'woocommerce-loyalty-program' );?></label>
								<input type="text" class="input-text" name="add_date_new_last_name" id="add_date_new_last_name" value="">
							</p>
							<!-- <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_email">Email&nbsp;<span class="required">*</span></label>
								<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="">
							</p> -->

							<p class="add_notification_date--info">
								<?php echo __( 'Mark the events you want to be notified about. You will receive an email notification a few days before the event. By mail with the offer of the desired package.', 'woocommerce-loyalty-program' );?>
							</p>

							<?php if(!empty($posts)) { ?>
								<div class="notification_dates-wrapper">
								<?php 
									foreach($posts as $date): 
									$id = $date->ID;
									$celeb_date = get_post_meta($id, '_date_discount', true);
									$is_personal_date = get_post_meta($id, '_is_personal_date', true);
									if(!empty($celeb_date)) {
										$celeb_date = strtotime($celeb_date);
										$newformat_date = date("j, F", $celeb_date);
										$newformat_date_value = date("d.m.Y", $celeb_date);
									}
									
								?>
									<div class="notification_dates-item <?php if($is_personal_date) echo 'personal-date';?>">
										<input type="checkbox" name="date_notify" value="<?php echo $date->post_name; ?>">
										<span><?php echo $date->post_title; ?></span>
										<?php if(!empty($celeb_date)): ?>
										<div class="date-wrapper">
											<?php echo $newformat_date; ?>
											<input type="hidden" name="" class="input-date_with_datepicker" value="<?php echo $newformat_date_value; ?>" id="">
										</div>
										<?php else: ?>
										<div class="date-wrapper datePicker_wrapper chose_date">
											<input type="text" class="woocommerce-Input woocommerce-Input--text input-date_with_datepicker input-text" placeholder="<?php echo __( 'Choose date', 'woocommerce-loyalty-program' );?>" name="chosen_date_<?php echo $date->post_name; ?>" id="chosen_date_<?php echo $date->post_name; ?>" autocomplete="off">
										</div>
										<?php endif; ?>

										<?php if($is_personal_date):?>
											<div class="date-name-wrapper">
												<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php echo __( 'Enter a name', 'woocommerce-loyalty-program' );?>" name="custom_name_<?php echo $date->post_name; ?>" id="custom_name_<?php echo $date->post_name; ?>" autocomplete="off">
											</div>
										<?php endif; ?>
										
									</div>
								<?php endforeach; ?>
								<a href="#" id="add_new_date_notification" class="woocommerce-button button"><?php echo __( 'Add', 'woocommerce-loyalty-program' );?></a>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php
	}

	public function save_gate_fields_on_registration( $customer_id ) {
		// $test = json_encode($_POST);
		// update_user_meta( $customer_id, 'test', $test );
		
		$notification_flag = false;
		if(isset($_POST[ 'notification_flag' ])) {
			update_user_meta( $customer_id, 'notification_flag', true );
			$notification_flag = true;
		}

		$customer_data = get_userdata( $customer_id );
		$customer_email = $customer_data->user_email;

		// $customer_phone = get_usermeta($customer_id, 'billing_phone', true);
		// $first_name = get_usermeta($customer_id, 'first_name', true);
		// $last_name = get_usermeta($customer_id, 'last_name', true);
		// $name = trim($first_name . ' ' . $last_name);

		$data_array = array("email" => $customer_email);
		$data_array['variables'] = array();

		$coupon_count = 0;

		$data_array['variables']['language'] = get_locale();

		if(isset($_POST['billing_phone'])){
			$data_array['variables']['Phone'] = sanitize_text_field($_POST['billing_phone']);
		}
		if(isset($_POST['billing_first_name'])){
			$data_array['variables']['name'] = sanitize_text_field($_POST['billing_first_name']);
		}
		if(isset($_POST['billing_last_name'])){
			$data_array['variables']['last_name'] = sanitize_text_field($_POST['billing_last_name']);
		}

		if (isset($_POST['notify_dates']) and !empty($_POST['notify_dates']) ) {
			$notify_dates = $_POST['notify_dates'];
			$notify_dates_names = $_POST['notify_dates_name'];


			foreach($notify_dates as $key => $value) {
				$name_celebrate = $notify_dates_names[$key];
				$all_string = $customer_id . $name_celebrate . $value;
				$key_hash = substr(sha1($all_string), 0, 10);
				// update_user_meta( $customer_id, 'test_key_hash' . $key_hash,  );
				$discount_type = 'percent';
				$coupon_code = 'DT' . $customer_id . 'EE' . strtoupper($key_hash);
	
				$date_arr = explode('||', $value);
				$date_celeb = $date_arr[1];
	
				// update_user_meta( $customer_id, 'test_name_date' . $key, $date_arr[0] );
				// update_user_meta( $customer_id, 'test_date_date' . $key, $date_arr[1] );

				update_user_meta( $customer_id, $date_arr[0] . '_date', $date_arr[1] );
				
				if(!empty($date_arr[2])) {
					update_user_meta( $customer_id, $date_arr[0] . '_custom_name', $date_arr[2] );
				}
				
	
				update_user_meta( $customer_id, $date_arr[0] . '_name_celebrate', $name_celebrate );
				
				$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => '',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type' => 'shop_coupon' );
	
				$date_exp = strtotime($date_celeb . "+1 days");
				$date_start = strtotime($date_celeb . "-6 days");
	
				update_user_meta( $customer_id, '_date_coupon_exp_' . $date_arr[0], $date_exp );
				update_user_meta( $customer_id, '_date_coupon_strt_'  . $date_arr[0], $date_start );
	
				$args = array(
					'name'        => $date_arr[0],
					'post_type'   => 'loyalty_program',
					'post_status' => 'publish',
					'numberposts' => 1
				);
	
				$current_celebrate = get_posts($args);
				update_user_meta( $customer_id, $date_arr[0] . '_test', json_encode($current_celebrate) );
	
	
				$date_for_sendpulse_array = explode('.', $date_arr[1]);
				$date_for_sendpulse = $date_for_sendpulse_array[1] . '/' .$date_for_sendpulse_array[0] . '/' . $date_for_sendpulse_array[2];
				$data_array['variables'][$date_arr[0]] = $date_for_sendpulse;
	
				if($name_celebrate != '- -') {
					$data_array['variables']['name_'.$date_arr[0]] = $name_celebrate;
				}

				if(!empty($date_arr[2])) {
					$data_array['variables']['custom_name_'.$date_arr[0]] = $date_arr[2];
				}
	
				if( $current_celebrate ) {
					$new_coupon_id = wp_insert_post( $coupon );
	
					$amount = get_post_meta($current_celebrate[0]->ID, 'discount_amount_key', true);
					if(empty($amount)) {
						$amount = "10";
					}
	
					if ( $new_coupon_id ) {
	
						update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
						update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
						update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
											
						update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
						update_post_meta( $new_coupon_id, 'usage_limit', '1' );
						update_post_meta( $new_coupon_id, 'expiry_date', '' );
						update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
						update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
						update_post_meta( $new_coupon_id, 'customer_email', array($customer_email) );
		
						update_post_meta( $new_coupon_id, 'date_expires', $date_exp );
						update_post_meta( $new_coupon_id, '_wt_coupon_start_date', date('Y-m-d', $date_start) );
		
						update_post_meta( $new_coupon_id, '_user_id', $customer_id );
						update_post_meta( $new_coupon_id, '_coupon_from_loyalty_program', true );
	
						update_user_meta( $customer_id, 'coupon_for_' . $date_arr[0], $coupon_code );
						
						$data_array['variables']['coupon_' . $date_arr[0]] = $coupon_code;
						$coupon_count++;
					} else {
					
						update_user_meta( $customer_id, '_coupon_error', 'create error' );
					}
					
		
	
				} else {
					update_user_meta( $customer_id, '_coupon_error', 'celebration date notfound' . $date_arr[0] );
				}
	
			}

			update_user_meta( $customer_id, '_coupons_count', $coupon_count );
	
			if($notification_flag) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-loyalty-program-sendpulse-api.php';
				$sendPulse = new SendPulseApi;
				$sendPulse->add_new_and_change_address($data_array, $customer_id);
			}
		}
	}

	public function add_personal_account_page($menu_links) {
		$menu_links[ 'memorable-dates' ] = __('Memorable dates', 'woocommerce-loyalty-program');
		return $menu_links;
	}

	public function memorable_dates_add_endpoint() {
	
		add_rewrite_endpoint( 'memorable-dates', EP_PAGES );
	
	}
	
	public function memorable_dates_content() {
		$user_id = get_current_user_id();
		$posts = get_posts( array(
			'post_type' => $this->loyalty_program_post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
		) );
		?>
		<input type="hidden" id="current_user_id" value="<?php echo $user_id; ?>">
		<div class="personal_account_dates_wrapper">
			<?php
				foreach($posts as $post_date) {
					$field_name = $post_date->post_name;
					// 	$field_label = $post->post_title;
					// 	$field_id =  $post->post_name;

					if(get_user_meta($user_id, $field_name . '_date', true)) {
						$name_celeb = get_user_meta($user_id, $field_name . '_name_celebrate', true);
						?>
							<div class="item <?php echo $field_name; ?>">
								<div class="name"><?php echo $name_celeb; ?></div>
								<div class="date">
									<?php
										$date = get_user_meta($user_id, $field_name . '_date', true);
										$celeb_date = strtotime($date);
										$newformat_date = date("j, F", $celeb_date);
										echo $newformat_date;
									?>
								</div>
								<div class="change-delete">
									<input type="hidden" class="date_slug" value="<?php echo $field_name; ?>">
									<a href="#" class="change_date_pa">Change</a>
									<a href="#" class="delete_date_pa"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></a>
								</div>
							</div>
					<?php
					}
				}
			?>
			<a href="#add_notification_dates" data-open="#add_notification_dates" class="primary is-small button wp-element-button is-outline"><?php echo __('Add new date', 'woocommerce-loyalty-program');?> +</a>
		</div>

		<div id="add_notification_dates" class="dark text-center mfp-hide lightbox-content">
			<div class="account-container lightbox-inner">
				<div class="row row-divided row-large" id="add_user_date">
					<div class="col-1 large-12 col pb-0">
						<h3 class="uppercase"><?php echo __( 'Add date notification', 'woocommerce-loyalty-program' );?></h3>
						<p class="form-row form-row-first">
							<label for="add_date_new_firs_name"><?php echo __( 'First Name', 'woocommerce-loyalty-program' );?></label>
							<input type="text" class="input-text" name="add_date_new_firs_name" id="add_date_new_firs_name" value="">
						</p>
						<p class="form-row form-row-last">
							<label for="add_date_new_firs_name"><?php echo __( 'Last Name', 'woocommerce-loyalty-program' );?></label>
							<input type="text" class="input-text" name="add_date_new_last_name" id="add_date_new_last_name" value="">
						</p>

						<p class="add_notification_date--info">
							<?php echo __( 'Mark the events you want to be notified about. You will receive an email notification a few days before the event. By mail with the offer of the desired package.', 'woocommerce-loyalty-program' );?>
						</p>

						<?php if(!empty($posts)) { ?>
							<div class="notification_dates-wrapper">
							<?php 
								foreach($posts as $date): 
								$id = $date->ID;
								$celeb_date = get_post_meta($id, '_date_discount', true);
								$is_personal_date = get_post_meta($id, '_is_personal_date', true);
								if(!empty($celeb_date)) {
									$celeb_date = strtotime($celeb_date);
									$newformat_date = date("j, F", $celeb_date);
									$newformat_date_value = date("d.m.Y", $celeb_date);
								}
								
							?>
								<div class="notification_dates-item <?php if($is_personal_date) echo 'personal-date';?>">
									<input type="checkbox" name="date_notify" value="<?php echo $date->post_name; ?>">
									<span><?php echo $date->post_title; ?></span>
									<?php if(!empty($celeb_date)): ?>
									<div class="date-wrapper">
										<?php echo $newformat_date; ?>
										<input type="hidden" name="" class="input-date_with_datepicker" value="<?php echo $newformat_date_value; ?>" id="">
									</div>
									<?php else: ?>
									<div class="date-wrapper datePicker_wrapper chose_date">
										<input type="text" class="woocommerce-Input woocommerce-Input--text input-date_with_datepicker input-text" placeholder="<?php echo __( 'Choose date', 'woocommerce-loyalty-program' );?>" name="chosen_date_<?php echo $date->post_name; ?>" id="chosen_date_<?php echo $date->post_name; ?>" autocomplete="off">
									</div>
									<?php endif; ?>

									<?php if($is_personal_date):?>
										<div class="date-name-wrapper">
											<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php echo __( 'Enter a name', 'woocommerce-loyalty-program' );?>" name="custom_name_<?php echo $date->post_name; ?>" id="custom_name_<?php echo $date->post_name; ?>" autocomplete="off">
										</div>
									<?php endif; ?>
									
								</div>
							<?php endforeach; ?>
							<a href="#" id="add_new_date_notification_pk" class="woocommerce-button button"><?php echo __( 'Add', 'woocommerce-loyalty-program' );?></a>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	public function generate_promocode($name_celebrate, $date, $customer_id, $is_replace_promo) {

		$all_string = $customer_id . $name_celebrate . $date;
		$key_hash = substr(sha1($all_string), 0, 10);
		$discount_type = 'percent';
		$coupon_code = 'DT' . $customer_id . 'EE' . strtoupper($key_hash);
		$customer_data = get_userdata( $customer_id );
		$customer_email = $customer_data->user_email;
		$date_exp = strtotime($date . "+1 days");
		$date_start = strtotime($date . "-6 days");
		
		$coupon = array(
			'post_title' => $coupon_code,
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type' => 'shop_coupon' );

		$args = array(
			'name'        => $name_celebrate,
			'post_type'   => 'loyalty_program',
			'post_status' => 'publish',
			'numberposts' => 1
		);

		$current_celebrate = get_posts($args);

		if( $current_celebrate ) {
			$new_coupon_id = wp_insert_post( $coupon );

			$amount = get_post_meta($current_celebrate[0]->ID, 'discount_amount_key', true);
			if(empty($amount)) {
				$amount = "10";
			}

			if ( $new_coupon_id ) {

				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
									
				update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
				update_post_meta( $new_coupon_id, 'usage_limit', '1' );
				update_post_meta( $new_coupon_id, 'expiry_date', '' );
				update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
				update_post_meta( $new_coupon_id, 'customer_email', array($customer_email) );

				update_post_meta( $new_coupon_id, 'date_expires', $date_exp );
				update_post_meta( $new_coupon_id, '_wt_coupon_start_date', date('Y-m-d', $date_start) );

				update_post_meta( $new_coupon_id, '_user_id', $customer_id );
				update_post_meta( $new_coupon_id, '_coupon_from_loyalty_program', true );

				update_user_meta( $customer_id, 'coupon_for_' . $name_celebrate, $coupon_code );

				
				if(get_user_meta($customer_id, '_coupons_count', true)) {
					$coupon_count = get_user_meta($customer_id, '_coupons_count', true);
				} else {
					$coupon_count = 0;
				}
				
				if(!$is_replace_promo) {
					$coupon_count++;
				}
				update_user_meta( $customer_id, '_coupons_count', $coupon_count );
				
				return $coupon_code;
			} else {
			
				update_user_meta( $customer_id, '_coupon_error', 'create error' );
			}
			


		} else {
			update_user_meta( $customer_id, '_coupon_error', 'celebration date notfound' . $date_arr[0] );
		}
	}

	public function ajax_add_new_date() {
		$user_id = intval( $_POST['user_id'] );
		$customer_data = get_userdata( $user_id );
		$customer_email = $customer_data->user_email;
		$clean_data_array = $_POST;
		unset($clean_data_array['name']);
		unset($clean_data_array['last_name']);
		unset($clean_data_array['user_id']);
		unset($clean_data_array['action']);
		unset($clean_data_array['repeats']);
		

		$data_array = array("email" => $customer_email);
		$data_array['variables'] = array();

		$name_date_user = sanitize_text_field($_POST['name']);
		$last_name_date_user = sanitize_text_field($_POST['last_name']);
		$full_name = $name_date_user . ' ' .  $last_name_date_user;


		foreach($clean_data_array as $key => $value) {
			if (strpos($key, 'datesend_') === 0) {
				$clean_name_date = str_replace('datesend_', '', $key);
				$data_array['variables'][$clean_name_date] = $value;
				if(get_user_meta($user_id, 'coupon_for_' . $clean_name_date, true)){
					$old_promocode = get_user_meta($user_id, 'coupon_for_' . $clean_name_date, true);
					$coupon_id = wc_get_coupon_id_by_code( $old_promocode);
					wp_trash_post($coupon_id);
					
					$promocode = $this->generate_promocode($clean_name_date, $value, $user_id, true);
				} else {
					$promocode = $this->generate_promocode($clean_name_date, $value, $user_id, false);
				}
				$data_array['variables']['coupon_'.$clean_name_date] = $promocode;
				update_user_meta( $user_id, $clean_name_date . '_date', $value );
				update_user_meta( $user_id, $clean_name_date . '_name_celebrate', $full_name);

				$data_array['variables']['name_'.$clean_name_date] = $full_name;
				$date_exp = strtotime($value . "+1 days");
				$date_start = strtotime($value . "-6 days");

				update_user_meta( $user_id, '_date_coupon_exp_' . $clean_name_date, $date_exp );
				update_user_meta( $user_id, '_date_coupon_strt_'  . $clean_name_date, $date_start );

			} elseif (strpos($key, 'custom_name_') === 0) {
				$data_array['variables'][$key] = $value;
				$clean_custom_name_date = str_replace('custom_name_', '', $key);
				update_user_meta( $user_id, $clean_custom_name_date . '_custom_name', $value );
			} else {
				if($value == '- -') {
					$data_array['variables'][$key] = '';
				} else {
					$data_array['variables'][$key] = $value;
				}
			}
			
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-loyalty-program-sendpulse-api.php';
		$sendPulse = new SendPulseApi;
		$sendPulse->add_new_and_change_address($data_array, $user_id);

		wp_die();
	}

	public function ajax_remove_date() {
		$user_id = intval( $_POST['user_id'] );
		$date_slug = $_POST['date_slug'];
		$coupon_code = get_user_meta($user_id, 'coupon_for_' . $date_slug, true);
		$coupon_id = wc_get_coupon_id_by_code( $coupon_code);
		wp_trash_post($coupon_id);


		$customer_data = get_userdata( $user_id );
		$customer_email = $customer_data->user_email;
		$data_array = array("email" => $customer_email);
		$data_array['variables'] = array();

		$data_array['variables'][0]['name'] = 'name_' . $date_slug;
		$data_array['variables'][0]['value'] = '';

		$data_array['variables'][1]['name'] = 'coupon_' . $date_slug;
		$data_array['variables'][1]['value'] = '';

		if(get_user_meta($user_id, $date_slug . '_custom_name', true)) {
			$data_array['variables'][2]['name'] = 'custom_name_' . $date_slug;
			$data_array['variables'][2]['value'] = '';
		}

		$data_array['variables'][3]['name'] = $date_slug;
		$data_array['variables'][3]['value'] = '0000-01-01';

		delete_user_meta($user_id, 'coupon_for_' . $date_slug);
		delete_user_meta($user_id, $date_slug . '_date');
		delete_user_meta($user_id, $date_slug . '_test');
		delete_user_meta($user_id, $date_slug . '_name_celebrate');
		delete_user_meta($user_id, $date_slug . '_custom_name');
		delete_user_meta($user_id, '_date_coupon_strt_' . $date_slug );
		delete_user_meta($user_id, '_date_coupon_exp_' . $date_slug);

		echo json_encode($data_array);

		$coupon_count = get_user_meta($user_id, '_coupons_count', true);
		$coupon_count--;
		update_user_meta( $user_id, '_coupons_count', $coupon_count );


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-loyalty-program-sendpulse-api.php';
		$sendPulse = new SendPulseApi;
		$sendPulse->delete_address($data_array, $user_id);

		wp_die();
	}

	public function ajax_change_date() {
		
	}


	// public function count_used_coupons($order_id) {
	// 	$order = wc_get_order($order_id);
	
	// 	$used_coupons = $order->get_used_coupons();
	
	// 	if (!empty($used_coupons)) {
	// 		$user_id = $order->get_user_id();
	// 		if(get_user_meta($user_id, '_count_activate_coupons', true)) {
	// 			$coupons_count = get_user_meta($user_id, '_count_activate_coupons', true);
	// 			$coupons_count++;
	// 			update_user_meta($user_id, '_count_activate_coupons', $coupons_count);
	// 		} else {
	// 			update_user_meta($user_id, '_count_activate_coupons', 1);
	// 		}
	// 	}
	// }

}
