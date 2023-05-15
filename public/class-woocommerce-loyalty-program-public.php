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

		wp_enqueue_script( $this->woocommerce_loyalty_program, plugin_dir_url( __FILE__ ) . 'js/woocommerce-loyalty-program-public.js', array( 'jquery' ), $this->version, false );

	}


	public function add_date_fields_to_registration_form() {
		$posts = get_posts( array(
			'post_type' => $this->loyalty_program_post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
		) );
	
		foreach($posts as $post) {
			$field_name = $post->post_name;
			$field_label = $post->post_title;
			$field_id =  $post->post_name;
			$field_required = false; // Можете изменить на false, если поле не обязательное
	
			woocommerce_form_field( $field_name, array(
				'type' => 'date',
				'class' => array( 'form-row-wide' ),
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
				$coupon_code = 'DATE' . $customer_id . '&' . $post->ID;

				$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => '',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type' => 'shop_coupon' );
					
				$new_coupon_id = wp_insert_post( $coupon );

				$date_exp = strtotime($date_celeb . "+1 days");

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

					$tomorrow = date('m-d-Y',strtotime($date1 . "+1 days"));
				
				} else {
				
					update_user_meta( $customer_id, '_coupon_error', 'create error' );
				}
			}
		}
	}


}
