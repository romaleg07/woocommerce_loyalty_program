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
		add_action( 'wp_ajax_get_users_with_coupons', [ $this, 'ajax_get_users_with_coupons'] ); 		
		add_action( 'wp_ajax_get_coupon_count', [ $this, 'ajax_get_coupon_count'] );

		add_action( 'edit_user_profile', [ $this, 'add_custom_meta_fields_admin'] );
		// add_action('add_meta_boxes', [ $this, 'add_custom_meta_boxes']);
		add_action('save_user_profile', [ $this, 'save_custom_meta_fields_admin']);
		// add_action( 'edit_user_profile_update', 'true_save_profile_fields' );
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
			'role__not_in' => array('administrator'), // Исключаем администраторов, если нужно
			'meta_query' => array(
				array(
					'key' => '_coupons_count',
					'compare' => 'EXISTS'
				)
			)
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

	public function ajax_get_users_with_coupons() {

		$page = 1;
		if(isset($_POST['page'])) {
			$page = $_POST['page'];
		}
		$per_page = 10;

		$args = array(
			'meta_key' => '_coupons_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'paged' => $page,
			'posts_per_page' => $per_page,
			'meta_query' => array(
				array(
					'key' => '_coupons_count',
					'compare' => 'EXISTS'
				)
			)
		);
	
		$user_query = new WP_User_Query($args);
		$users = $user_query->get_results();


		foreach($users as $user) {
			$id = $user->data->ID;
			
			$coupones = get_posts( array(
				'post_type' => 'shop_coupon',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'meta_key'    => '_used_by',
				'meta_value'  => $id,
			) );
			if(get_user_meta($id, '_coupons_count', true)) {
				$user->data->all_coupons = get_user_meta($id, '_coupons_count', true);
			} else {
				$user->data->all_coupons = 0;
			}

			if(get_user_meta($id, 'first_name', true)) {
				$user->data->first_name = get_user_meta($id, 'first_name', true);
			} else {
				$user->data->first_name = '';
			}
			if(get_user_meta($id, 'last_name', true)) {
				$user->data->last_name = get_user_meta($id, 'last_name', true);
			} else {
				$user->data->last_name = '';
			}

			$user->data->used_coupons = count($coupones);
		}
	
		wp_send_json($users);
	
		wp_die();
	}

	public function ajax_get_coupon_count() {
		$period = 'all';
		if(isset($_POST['period'])) {
			$period = $_POST['period'];
		}

		global $wpdb;
	
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
			SELECT COUNT(ID)
			FROM $wpdb->posts
			INNER JOIN $wpdb->postmeta
				ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
			WHERE $wpdb->posts.post_type = 'shop_coupon'
				AND $wpdb->postmeta.meta_key = '_coupon_from_loyalty_program'
				AND $wpdb->postmeta.meta_value = 1
				$where_clause
		";
	
		$count = $wpdb->get_var($query);
	
		wp_send_json($count);
	
		wp_die();
	}


	public function add_custom_meta_fields_admin($user) {
		$args = array(
			'post_type' => 'loyalty_program',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);
		$dates = get_posts($args);
		foreach($dates as $date) {
			$id = $date->ID;
			$celeb_date = get_post_meta($id, '_date_discount', true);
			$is_personal_date = get_post_meta($id, '_is_personal_date', true);

			echo '<h3 id="loyalty">Loyalty fields</h3>';
 
			// поля в профиле находятся в рамметке таблиц <table>
			echo '<table class="form-table">';
		
			// добавляем поле город
			$user_date = get_the_author_meta( $date->post_name . '_date', $user->ID );
			
			echo '<tr><th><label for="city">Date for "'. $date->post_title.'"</label></th>
			<td><input type="text" name="'. $date->post_name.'" id="'. $date->post_name.'" value="' . esc_attr( $user_date ) . '" class="addDatepicker" /></td>
			</tr>';

			$user_name_celebrate = get_the_author_meta( $date->post_name . '_name_celebrate', $user->ID );

			echo '<tr><th><label for="city">Name for "'. $date->post_title.'"</label></th>
			<td><input type="text" name="'. $date->post_name.'_name_celebrate" id="'. $date->post_name.'_name_celebrate" value="' . esc_attr( $user_name_celebrate ) . '" class="addDatepicker" /></td>
			</tr>';

			if($is_personal_date) {
				$user_date_custom_name = get_the_author_meta( $date->post_name . '_custom_name', $user->ID );
				echo '<tr><th><label for="city">Celebration name for "'. $date->post_title.'"</label></th>
				<td><input type="text" name="'. $date->post_name.'_custom_name" id="'. $date->post_name.'_custom_name" value="' . esc_attr( $user_date_custom_name ) . '" class="regular-text" /></td>
				</tr>';
			}
		
			echo '</table>';
			
		}
	}

	public function save_custom_meta_fields_admin($user_id) {
		$args = array(
			'post_type' => 'loyalty_program',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);
		$dates = get_posts($args);

		$customer_data = get_userdata( $user_id );
		$customer_email = $customer_data->user_email;
		$data_array = array("email" => $customer_email);
		$data_array['variables'] = array();

		foreach($dates as $date) {
			if (isset($_POST[$date->post_name])) {
				$date_old = $_POST[$date->post_name];
				$date_old =  explode('/', $date_old);
				$new_date = $date[2].'-'.$date[1].'-'.$date[0];
				
				if(get_user_meta($user_id, $date->post_name.'_date', true)) {
					if(get_user_meta($user_id, $date->post_name.'_date', true) != $new_date) {
						$old_promocode = get_user_meta($user_id, 'coupon_for_' . $date->post_name, true);
						$coupon_id = wc_get_coupon_id_by_code( $old_promocode);
						wp_trash_post($coupon_id);
						update_user_meta($user_id, $date->post_name.'_date', $new_date);
						$promocode = $this->generate_promocode($date->post_name, $new_date, $user_id, true);
					}
				} else {
					$promocode = $this->generate_promocode($date->post_name, $new_date, $user_id, false);
				}
				$data_array['variables'][$date->post_name] = $new_date;
				$data_array['variables']['coupon_'.$date->post_name] = $promocode;
			} else {
				$data_array['variables'][$date->post_name] = '0000-01-01';
				$data_array['variables']['coupon_'.$date->post_name] = '';
			}

			if (isset($_POST[$date->post_name . '_name_celebrate'])) {
				$name_celebrate = $_POST[$date->post_name . '_name_celebrate'];
				update_user_meta($user_id, $date->post_name.'_name_celebrate', $name_celebrate);
				$data_array['variables']['name_'.$date->post_name] = $name_celebrate;
			} else {
				$data_array['variables']['name_'.$date->post_name] = '';
			}

			if (isset($_POST[$date->post_name .'_custom_name'])) {
				$custom_name = $_POST[$date->post_name .'_custom_name'];
				update_user_meta($user_id, $date->post_name.'_custom_name', $custom_name);
				$data_array['variables']['custom_name_'.$date->post_name] = $custom_name;
			} else {
				$data_array['variables']['custom_name_'.$date->post_name] = '';
			}

		}
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
	
	
}
