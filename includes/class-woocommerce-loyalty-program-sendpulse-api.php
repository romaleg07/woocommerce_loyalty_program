<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/romaleg07/
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
 * @author     Romaleg <romaleg.sky@yandex.ru>
 */

class SendPulseApi {
	protected $url;

	protected $grant_type;

	protected $client_id;

	protected $client_secret;

	protected $bearer_token;

	protected $logger;

	protected $id_address_book;


    public function __construct() {
		$this->url = get_option('woocommerce_loyalty_program_api_url', false);
		$this->grant_type = get_option('woocommerce_loyalty_grant_type', false);
		$this->client_id = get_option('woocommerce_loyalty_client_id', false);
		$this->client_secret = get_option('woocommerce_loyalty_client_secret', false);
		$this->id_address_book = get_option('woocommerce_loyalty_id_address_book', false);

		$this->logger = wc_get_logger();

		if (!get_option( 'woocommerce_loyalty_plugin_enabled', false )):
			return false;
		endif;

		$this->bearer_token = $this->get_token();

	}

	private function get_token() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . '/oauth/access_token',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
			"grant_type":"' . $this->grant_type . '",
			"client_id":"' . $this->client_id . '",
			"client_secret":"' . $this->client_secret . '"
		}',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);
		$response_array = json_decode($response, true);

		if(isset($response_array['access_token'])){ 
			return $response_array['access_token'];
		} else {
			$this->logger->error( $response, array( 'source' => 'Woocommerce_Loyalty_Program' ) );
			return false;
		}
	}

	public function add_new_and_change_address($address_data, $user_id) {
		$emails = array("emails" => array($address_data) );
		$json_data = json_encode($emails);

		$this->logger->debug( $json_data, array( 'source' => 'Woocommerce_Loyalty_Program' ) );

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $this->url . '/addressbooks/' . $this->id_address_book . '/emails',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $json_data,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $this->bearer_token
		),
		));

		$response = curl_exec($curl);

		$this->logger->debug( $response, array( 'source' => 'Woocommerce_Loyalty_Program' ) );

		update_post_meta($user_id, '_response_from_sendpulse', $response);

		curl_close($curl);

		$response_array = json_decode($response, true);

		if(isset($response_array['result']) and $response_array['result']){ 
			return $response_array['result'];
		} else {
			$this->logger->error( $response, array( 'source' => 'Woocommerce_Loyalty_Program' ) );
			return false;
		}
	}

	public function delete_address($address_data, $user_id) {
		$json_data = json_encode($address_data);

		$this->logger->debug( $json_data, array( 'source' => 'Woocommerce_Loyalty_Program' ) );

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $this->url . '/addressbooks/' . $this->id_address_book . '/emails/variable',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $json_data,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $this->bearer_token
		),
		));

		$response = curl_exec($curl);

		$this->logger->debug( $response, array( 'source' => 'Woocommerce_Loyalty_Program' ) );

		update_post_meta($user_id, '_response_from_sendpulse', $response);

		curl_close($curl);

		$response_array = json_decode($response, true);

		if(isset($response_array['result']) and $response_array['result']){ 
			return $response_array['result'];
		} else {
			$this->logger->error( $response, array( 'source' => 'Woocommerce_Loyalty_Program' ) );
			return false;
		}
	}

	public function add_new_webhook($callback_address) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->url . '/v2/email-service/webhook/',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
			"url":"'.$callback_address.'",
			"actions":["delivered"]
		}',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $this->bearer_token
		  ),
		));
		
		$response = curl_exec($curl);
		
		curl_close($curl);

		$response_array = json_decode($response, true);

		if(isset($response_array['success']) and $response_array['success']){ 
			return $response_array['success'];
		} else {
			$this->logger->error( $response, array( 'source' => 'Woocommerce_Loyalty_Program' ) );
			return false;
		}
	}


}