<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Woocommerce_Loyalty_Program
 * @subpackage Woocommerce_Loyalty_Program/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Loyalty_Program
 * @subpackage Woocommerce_Loyalty_Program/includes
 * @author     Romaleg
 */
class Woocommerce_Loyalty_Program_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	# Singleton

	public static function activate() {
		add_option( 'woocommerce_loyalty_program_api_url', 'https://api.sendpulse.com' );
		add_option( 'woocommerce_loyalty_plugin_enabled', false );
		add_option( 'woocommerce_loyalty_grant_type', 'client_credentials' );
		add_option( 'woocommerce_loyalty_client_id', '' );
		add_option( 'woocommerce_loyalty_client_secret', '' );
		add_option( 'woocommerce_loyalty_default_percent_sale', '10' );
		add_option( 'woocommerce_loyalty_id_address_book', '' );
		add_option( 'woocommerce_loyalty_send_statistic_to_sendpulse', false );
	}
 
}
