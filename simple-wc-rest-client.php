<?php
/**
 * Plugin Name: Simple Woocommerce Rest Client
 * Plugin URI: http://septianfujianto.com/
 * Description: A Woocommerce client to make accessing Woocommerce Rest API simpler. Web App and Mobile App will be able to access Woocommerce API without having to do complicated OAuth signing. Require valid Consumer key and secret from Woocommerce.
 * Version: 0.1.2
 * Author: Septian Ahmad Fujianto
 * Author URI: http://septianfujianto.com
 * GitHub Plugin URI: https://github.com/fujianto/simple-wc-rest-client
 * Github Branch: master
 * Requires at least: 4.4
 * Tested up to: 4.6.1
 *
 * Text Domain: swr-client
 * 
 * @todo: Validate String to find if it's valid JSON or Not to prevent error when inputing data and params
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

$extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
$extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $extension_dir ) );

/* Define necessary constant */
define('FACTORY_DIR'     , $extension_dir);
define('FACTORY_DIR_URI' , $extension_url);

require FACTORY_DIR. '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

class SimpleWcRestClient 
{
	public $version     = '1';
	public $namespace_api;
	public $base_get    = 'get';
	public $base_post   = 'post';
	public $base_put    = 'put';
	public $base_delete = 'delete';

	function __construct(){ 
		$this->init_hook();
		$this->namespace_api = 'swrc/v' . $this->version;
	}

	/* Function to accept WooCommerce Client settings */
	public function swrc_client( $base_url, $consumer_key, $consumer_secret, $options) {
		if($base_url !== "" || $consumer_key !== "" || $consumer_secret != "") {
			$client_options = $options;

			if($options == "") {
				$client_options = [
					'wp_api' => true,
					'version' => 'wc/v1',
				];
			}

			$woocommerce = new Client($base_url, $consumer_key, $consumer_secret, $client_options);
			return $woocommerce;
		}
	}

	/* Callback Woo POST Routes */
	public function post_swrc_api( $request_data ) {
		$base_url        = $request_data['base_url'];
		$consumer_key    = $request_data['consumer_key'];
		$consumer_secret = $request_data['consumer_secret'];
		$options         = $request_data['options'];
		$endpoint        = $request_data['endpoint'];
		$data            = $request_data['data'];
		$results;

		try{
			$woocommerce = $this->swrc_client( $base_url, $consumer_key, $consumer_secret, $options);
			$results     = $woocommerce->post($endpoint, $data);
			$response    = new WP_REST_Response($results, 200);
		} catch (Exception $e) {
			$results     = $e->getMessage();
			$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => $results] ], 408);
		}

		return $response;
	}

	/* Callback Woo PUT Routes */
	public function put_swrc_api( $request_data ) {
		$base_url        = $request_data['base_url'];
		$consumer_key    = $request_data['consumer_key'];
		$consumer_secret = $request_data['consumer_secret'];
		$options         = $request_data['options'];
		$endpoint        = $request_data['endpoint'].'/'.$request_data['id'];
		$data            = $request_data['data'];
		$results;

		try{
			$woocommerce = $this->swrc_client( $base_url, $consumer_key, $consumer_secret, $options);
			$results     = $woocommerce->put($endpoint, $data);

			$response    = new WP_REST_Response($results, 200);
		} catch (Exception $e) {
			$results     = $e->getMessage();
			$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => $results] ], 408);
		}

		return $response;
	}

	/* Callback Woo DELETE Routes */
	public function delete_swrc_api( $request_data ) {
		$base_url        = $request_data['base_url'];
		$consumer_key    = $request_data['consumer_key'];
		$consumer_secret = $request_data['consumer_secret'];
		$options         = $request_data['options'];
		$endpoint        = $request_data['endpoint'].'/'.$request_data['id'];
		$parameters      = $request_data['parameters'];
		$results;

		try{
			$woocommerce = $this->swrc_client( $base_url, $consumer_key, $consumer_secret, $options);
			$results     = $woocommerce->delete($endpoint, $parameters);
			$response    = new WP_REST_Response($results, 200);
		} catch (Exception $e) {
			$results     = $e->getMessage();
			$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => $results] ], 408);
		}

		return $response;
	}

	/* Callback for GET Routes */
	public function get_swrc_api( $request_data ) {
		$base_url        = $request_data['base_url'];
		$consumer_key    = $request_data['consumer_key'];
		$consumer_secret = $request_data['consumer_secret'];
		$options         = $request_data['options'];
		$endpoint        = $request_data['endpoint'];
		$parameters      = $request_data['parameters'];
		$results;

		try{
			$woocommerce = $this->swrc_client( $base_url, $consumer_key, $consumer_secret, $options);
			$results     = $woocommerce->get($endpoint, $parameters);

			$response    = new WP_REST_Response($results, 200);
		} catch (Exception $e) {
			$results     = $e->getMessage();

			$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => $results] ], 408);
		}

		return $response;
	}

	/* Register Custom REST Routes */
	public function register_routes() {
		register_rest_route( $this->namespace_api, $this->base_delete.'/(?P<id>\d+)', 
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'delete_swrc_api' ),
			)
		);

		register_rest_route( $this->namespace_api, $this->base_put.'/(?P<id>\d+)', 
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'put_swrc_api' ),
			)
		);

		register_rest_route( $this->namespace_api, $this->base_get, 
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'get_swrc_api' ),
			)
		);

		register_rest_route( $this->namespace_api, $this->base_post, 
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'post_swrc_api' ),
			)
		);
	}

	public function init_hook() {
		add_action("rest_api_init", array($this, "register_routes"));
	}
}

$client = new SimpleWcRestClient();