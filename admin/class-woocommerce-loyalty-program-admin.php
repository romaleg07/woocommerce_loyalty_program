<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Woocommerce_Loyalty_Program
 * @subpackage Woocommerce_Loyalty_Program/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Loyalty_Program
 * @subpackage Woocommerce_Loyalty_Program/admin
 * @author     Romaleg
 */

class Woocommerce_Loyalty_Program_Admin {

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
	 * @param      string    $woocommerce_loyalty_program       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $woocommerce_loyalty_program, $version ) {

		$this->woocommerce_loyalty_program = $woocommerce_loyalty_program;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		wp_enqueue_style( $this->woocommerce_loyalty_program, plugin_dir_url( __FILE__ ) . 'css/woocommerce-loyalty-program-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script( $this->woocommerce_loyalty_program, plugin_dir_url( __FILE__ ) . 'js/woocommerce-loyalty-program-admin.js', array( 'jquery' ), $this->version, true );

	}

	/**
     * Register the page name on the admin menu
     *
     */
    public function reloadly_products_custom_menu_item() {

		add_menu_page(
			'Loyalty program by dates > Settings',
			__( 'Loyalty program', 'woocommerce-loyalty-program' ),
			'manage_options',
			'loyalty_program',
			array($this, "loyalty_program_admin_page") ,
			plugin_dir_url( __FILE__ ) . "img/logo20.png",
			70
		);
		
		add_submenu_page(
			'loyalty_program',
			__( 'Settings', 'woocommerce-loyalty-program' ),
			__( 'Settings', 'woocommerce-loyalty-program' ),
			'manage_options',
			'loyalty_program_settings',
			array($this, "loyalty_program_settings") 
		);
		add_submenu_page(
			'loyalty_program',
			__( 'Statistics', 'woocommerce-loyalty-program' ),
			__( 'Statistics', 'woocommerce-loyalty-program' ),
			'manage_options',
			'loyalty_program_statistics',
			array($this, "loyalty_program_statistics") 
		);

		// add_submenu_page(
		// 	'loyalty_program',
		// 	__( 'Add Date', 'woocommerce-loyalty-program' ),
		// 	__( 'Add Date', 'woocommerce-loyalty-program' ),
		// 	'manage_options',
		// 	'loyalty_program_add_date',
		// 	'my_custom_submenu_page_callback'
		// );

		// add_submenu_page(
		// 	'loyalty_program',
		// 	__( 'Add Date', 'woocommerce-loyalty-program' ),
		// 	__( 'Add Date', 'woocommerce-loyalty-program' ),
		// 	'manage_options',
		// 	'loyalty_program_add_date',
		// 	'my_custom_submenu_page_callback'
		// );
	}


	public function loyalty_program_admin_page() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/woocommerce-loyalty-program-admin-display.php';
	}

	public function loyalty_program_settings() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/woocommerce-loyalty-program-admin-display-settings.php';
	}

	public function loyalty_program_statistics() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/woocommerce-loyalty-program-admin-display-statistics.php';
	}


}
