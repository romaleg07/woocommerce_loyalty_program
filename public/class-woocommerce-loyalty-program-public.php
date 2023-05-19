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

		// wp_enqueue_style('jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, null ); 
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

		// wp_enqueue_script('jquery-ui-datepicker');
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
								<label for="add_date_new_firs_name"><?php echo __( 'First Name', 'woocommerce-loyalty-program' );?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="add_date_new_firs_name" id="add_date_new_firs_name" value="">
							</p>
							<p class="form-row form-row-last">
								<label for="add_date_new_firs_name"><?php echo __( 'Last Name', 'woocommerce-loyalty-program' );?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="add_date_new_last_name" id="add_date_new_firs_name" value="">
							</p>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_email">Email&nbsp;<span class="required">*</span></label>
								<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="">
							</p>

							<p class="add_notification_date--info">
								<?php echo __( 'Mark the events you want to be notified about. You will receive an email notification a few days before the event. By mail with the offer of the desired package.', 'woocommerce-loyalty-program' );?>
							</p>

							<?php if(!empty($posts)) { ?>
								<div class="notification_dates-wrapper">
								<?php foreach($posts as $date): ?>
									<div class="notification_dates-item">
										<input type="checkbox" name="date_notify">
									</div>
								<?php endforeach; ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php
	
		foreach($posts as $post) {
			$field_name = $post->post_name;
			$field_label = $post->post_title;
			$field_id =  $post->post_name;
			$field_required = false; // Можете изменить на false, если поле не обязательное
	
			woocommerce_form_field( $field_name, array(
				'type' => 'date',
				'class' => array( 'form-row-wide datePicker_wrapper' ),
				'label' => $field_label,
				'required' => $field_required,
				'id' => $field_id,
			) );
			
		}
	}

	public function save_gate_fields_on_registration( $customer_id ) {    
		$posts = get_posts( array(
			'post_type' =>  $this->loyalty_program_post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
		) );
		$customer_data = get_userdata( $customer_id );
		$customer_email = $customer_data->user_email;


		$notification_flag = $_POST[ 'notification_flag' ];
		update_user_meta( $customer_id, 'notification_flag', $notification_flag );

		foreach($posts as $post) {
			$field_name = $post->post_name;
			if ( isset( $_POST[ $field_name ] ) and !empty(  $_POST[ $field_name ] ) ) {
				$date_celeb = $_POST[ $field_name ];
				$meta_key = $field_name;
				$meta_value = sanitize_text_field( $_POST[ $field_name ] );
	
				update_user_meta( $customer_id, $meta_key, $meta_value );
				
				$amount = get_post_meta($post->ID, 'discount_amount_key', true);
				if(empty($amount)) {
					$amount = "5";
				}
				$discount_type = 'percent';
				$coupon_code = 'DATE' . $customer_id . 'EE' . $post->ID;

				$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => '',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type' => 'shop_coupon' );
					
				$new_coupon_id = wp_insert_post( $coupon );

				$date_exp = strtotime($date_celeb . "+1 days");
				$date_start = strtotime($date_celeb . "-3 days");

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
					
					
				} else {
				
					update_user_meta( $customer_id, '_coupon_error', 'create error' );
				}
			}
		}
	}





}
