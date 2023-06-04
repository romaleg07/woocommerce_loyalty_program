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

		add_action( 'wp_ajax_get_statistics', [ $this, 'ajax_get_statistics'] ); 
		add_action( 'wp_ajax_get_registered_users_count', [ $this, 'ajax_get_registered_users_count'] ); 
		add_action( 'wp_ajax_get_activated_coupons_count', [ $this, 'ajax_get_activated_coupons_count'] ); 
		
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

	public function ajax_get_statistics() {
		$page = 1;
		if(isset($_POST['page'])) {
			$page = $_POST['page'];
		}
		$data = array();

		$coupones = get_posts( array(
			'post_type' => 'shop_coupon',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_key'    => '_coupon_from_loyalty_program',
			'meta_value'  => 1,
		) );

		$data['coupones'] = $coupones;
		$data['count_users'] = count_users( $strategy );
		wp_send_json($coupones);
		// echo json_encode($posts);

		wp_die();
	}

	public function ajax_get_registered_users_count() {
		$args = array(
			'fields' => 'count',
			'role__not_in' => array('administrator') // Исключаем администраторов, если нужно
		);

		$period = 'all';
		if(isset($_POST['period'])) {
			$period = $_POST['period'];
		}
	
		switch ($period) {
			case 'month':
				$args['date_query'] = array(
					array(
						'after' => '1 month ago'
					)
				);
				break;
			case 'week':
				$args['date_query'] = array(
					array(
						'after' => '1 week ago'
					)
				);
				break;
			default:
				break;
		}
	
		$user_query = new WP_User_Query($args);
		$count = $user_query->get_total();
		wp_send_json($count);
	
		wp_die();
	}

	public function ajax_get_activated_coupons_count() {
		global $wpdb;

		$period = 'all';
		if(isset($_POST['period'])) {
			$period = $_POST['period'];
		}
	
		$where_clause = '';
	
		switch ($period) {
			case 'month':
				$where_clause = "AND DATE_ADD(post_date, INTERVAL 1 MONTH) >= NOW()";
				break;
			case 'week':
				$where_clause = "AND DATE_ADD(post_date, INTERVAL 1 WEEK) >= NOW()";
				break;
			default:
				break;
		}
	
		$query = "
			SELECT COUNT(DISTINCT ID)
			FROM $wpdb->posts
			INNER JOIN $wpdb->postmeta
				ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
			WHERE $wpdb->posts.post_type = 'shop_coupon'
				AND $wpdb->postmeta.meta_key = 'usage_count'
				AND $wpdb->postmeta.meta_value > 0
				$where_clause
		";
	
		$count = $wpdb->get_var($query);
	
		wp_send_json($count);
	
		wp_die();
	}
	
}
