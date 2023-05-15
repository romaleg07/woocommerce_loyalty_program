<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Woocommerce_Loyalty_Program
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Loyalty Program
 * Plugin URI:        https://github.com/romaleg07/woocommerce_loyalty_program
 * Description:       Плагин для добавления новой программы лояльности, связанную с датами.
 * Version:           1.0.0
 * Author:            Romaleg
 * Author URI:        https://github.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-loyalty-program
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Update it as you release new versions.
 */
define( 'WOOCOMMERCE_LOYALTY_PROGRAM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-loyalty-program-activator.php
 */
function activate_woocommerce_loyalty_program() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-loyalty-program-activator.php';
	Woocommerce_Loyalty_Program_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-loyalty-program-deactivator.php
 */
function deactivate_woocommerce_loyalty_program() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-loyalty-program-deactivator.php';
	Woocommerce_Loyalty_Program_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_loyalty_program' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_loyalty_program' );


add_action( 'init', 'register_loyalty_program_post_type' );

function register_loyalty_program_post_type() {
	register_post_type('loyalty_program', array(
			'labels'				   => array(
			'name' 					   => __('Dates'),
			'singular_name'            => __('Date'),
			'add_new'                  =>  __('Add date', 'woocommerce-loyalty-program'),
			'add_new_item'             =>  __('Add new date', 'woocommerce-loyalty-program'),
			'edit_item'                =>  __('Edit date', 'woocommerce-loyalty-program'),
			'new_item'                 =>  __('New date', 'woocommerce-loyalty-program'),
			'view_item'                =>  __('Date', 'woocommerce-loyalty-program'),
			'search_items'             =>  __('Date', 'woocommerce-loyalty-program'),
			'menu_name' 			   => __( 'Loyalty program', 'woocommerce-loyalty-program' ),
		),
		'show_in_menu' => 'loyalty_program',
		'supports'     => [ 'title' ],
		'public'       => true,
		'show_in_rest' => false
	));
	
}
// Добавляем метабокс с кастомным полем
function add_discount_amount_field_meta_box() {
    add_meta_box(
        'add_discount_amount_field_meta_box', // Идентификатор метабокса
        'Discount amount', // Заголовок метабокса
        'render_discount_amount_field_meta_box', // Функция для отображения содержимого метабокса
        'loyalty_program', // Тип записей, для которого будет отображаться метабокс
        'normal', // Расположение метабокса на странице (нормальное, сайдбар и т. д.)
        'default' // Приоритет метабокса
    );
}
add_action( 'add_meta_boxes', 'add_discount_amount_field_meta_box' );

// Функция для отображения содержимого метабокса
function render_discount_amount_field_meta_box( $post ) {
    // Получаем значение кастомного поля, если оно уже было сохранено
    $discount_amount_field_value = get_post_meta( $post->ID, 'discount_amount_key', true );
    ?>

    <label for="discount_amount"><?php echo __('Discount amount (%):', 'woocommerce-loyalty-program');?></label>
    <input type="text" id="discount_amount" name="discount_amount" value="<?php echo esc_attr( $discount_amount_field_value ); ?>">

    <?php
}

// Сохраняем значение кастомного поля при сохранении записи
function save_discount_amount_field( $post_id ) {
    if ( isset( $_POST['discount_amount'] ) ) {
        $discount_amount_field_value = sanitize_text_field( $_POST['discount_amount'] );
        update_post_meta( $post_id, 'discount_amount_key', $discount_amount_field_value );
    }
}
add_action( 'save_post', 'save_discount_amount_field' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-loyalty-program.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_loyalty_program() {

	$plugin = new Woocommerce_Loyalty_Program();
	$plugin->run();

}
run_woocommerce_loyalty_program();