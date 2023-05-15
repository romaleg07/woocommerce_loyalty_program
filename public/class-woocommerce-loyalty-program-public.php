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
	public function __construct( $woocommerce_loyalty_program, $version ) {

		$this->woocommerce_loyalty_program = $woocommerce_loyalty_program;
		$this->version = $version;

		add_action( 'woocommerce_register_form', 'add_custom_fields_to_registration_form' );
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


	public function add_custom_fields_to_registration_form() {
		$custom_post_type = 'loyalty_program'; // Замените на фактический идентификатор вашего кастомного типа записей
		
		$posts = get_posts( array(
			'post_type' => $custom_post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
		) );
	
		$count = count( $posts );
	
		for ( $i = 1; $i <= $count; $i++ ) {
			$field_name = 'custom_field_' . $i;
			$field_label = 'Custom Field ' . $i;
			$field_id = 'custom_field_' . $i;
			$field_required = false; // Можете изменить на false, если поле не обязательное
	
			woocommerce_form_field( $field_name, array(
				'type' => 'text',
				'class' => array( 'form-row-wide' ),
				'label' => $field_label,
				'required' => $field_required,
				'id' => $field_id,
			) );
		}
	}



}
