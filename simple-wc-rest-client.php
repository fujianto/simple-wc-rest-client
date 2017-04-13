<?php
/**
 * Plugin Name: Simple Woocommerce Rest Client
 * Plugin URI: http://septianfujianto.com/
 * Description: A Woocommerce client to make accessing Woocommerce Rest API simpler. Web App and Mobile App will be able to access Woocommerce API without having to do complicated OAuth signing. Require valid Consumer key and secret from Woocommerce.
 * Version: 0.2.1
 * Author: Septian Ahmad Fujianto
 * Author URI: http://septianfujianto.com
 * GitHub Plugin URI: https://github.com/fujianto/simple-wc-rest-client
 * Github Branch: master
 * Requires at least: 4.4
 * Tested up to: 4.7.3
 *
 * Text Domain: swr-client
 * 
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
	public $default_options = [ 'wp_api' => true, 'version' => 'wc/v1', 'query_string_auth' => true ];

	function __construct(){ 
		$this->init_hook();
		$this->namespace_api = 'swrc/v' . $this->version;
	}

	/* Function to accept WooCommerce Client settings */
	public function swrc_client( $base_url, $consumer_key, $consumer_secret, $options, $endpoint) {
		$results;

		if ($base_url === "" || $base_url === null) {
			$results    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => "Missing base url parameter"] ], 405);
		} else if ($consumer_key === "" || $consumer_key === null) {
			$results    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => "Missing Consumer key parameter"] ], 405);
		} else if ($consumer_secret === "" || $consumer_secret === null) {
			$results    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => "Missing Consumer secret parameter"] ], 405);
		} else if ($endpoint === null || $endpoint === "") {
			$results    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => "Missing endpoint parameter"] ], 405);
		} else {
			$client_options = $options;

			if ($options == "" || $options == null) {
				$client_options = [
					'wp_api' => true,
					'version' => 'wc/v1',
				];
			} else {
				if (is_array($options)) {
					$client_options = $options;
				} else {
					$client_options = (array) json_decode($options);
				}
			}

			$results = new Client($base_url, $consumer_key, $consumer_secret, $client_options);

			return $results;
		}
	}

	/* Callback Woo POST Routes */
	public function post_swrc_api( $request_data ) {
		$base_url        = $request_data['base_url'];
		$consumer_key    = $request_data['consumer_key'];
		$consumer_secret = $request_data['consumer_secret'];
		$options         = $request_data['options'];
		$endpoint        = $request_data['endpoint'];
		$data 			 = ($request_data['data'] === null || json_decode($request_data['data']) == null) ? [] : (array) json_decode($request_data['data']);
		$results;

		try{
			$woocommerce = $this->swrc_client($base_url, $consumer_key, $consumer_secret, $options, $endpoint);

			if ($woocommerce !== null) {
				$response    = new WP_REST_Response($woocommerce->post($endpoint, $data), 200);
			} else {
				$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => "Missing parameters"] ], 405);
			}
		} catch (Exception $e) {
			$results     = $e->getMessage();
			$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => $results] ], 405);
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
		$data 			 = ($request_data['data'] === null || json_decode($request_data['data']) == null) ? [] : (array) json_decode($request_data['data']);
		$results;

		try{
			$woocommerce = $this->swrc_client($base_url, $consumer_key, $consumer_secret, $options, $endpoint);
			
			if ($woocommerce !== null) {
				$response    = new WP_REST_Response($woocommerce->put($endpoint, $data), 200);
			} else {
				$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => "Missing parameters"] ], 405);
			}
		} catch (Exception $e) {
			$results     = $e->getMessage();
			$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => $results] ], 405);
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
		$parameters = ($request_data['parameters'] === null || json_decode($request_data['parameters']) == null) ? ['force' => true] : (array) json_decode($request_data['parameters']);
		$results;

		try{
			$woocommerce = $this->swrc_client($base_url, $consumer_key, $consumer_secret, $options, $endpoint);

			if ($woocommerce !== null) {
				$response    = new WP_REST_Response($woocommerce->delete($endpoint, $parameters), 200);
			} else {
				$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => "Missing parameters"] ], 405);
			}
		} catch (Exception $e) {
			$results     = $e->getMessage();
			$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => $results] ], 405);
		}

		return $response;
	}

	/* Callback for GET Routes */
	public function get_swrc_api( $request_data ) {
		$base_url        = $request_data['base_url'];
		$consumer_key    = $request_data['consumer_key'];
		$consumer_secret = $request_data['consumer_secret'];
		$endpoint        = $request_data['endpoint'];
		$options = $request_data['options'];
		$parameters = ($request_data['parameters'] === null || json_decode($request_data['parameters']) == null) ? [] : (array) json_decode($request_data['parameters']);
		$results;

		try{
			$woocommerce = $this->swrc_client($base_url, $consumer_key, $consumer_secret, $this->default_options, $endpoint);

			if ($woocommerce !== null) {
				$results    = new WP_REST_Response($woocommerce->get($endpoint, $parameters), 200);
			} else {
				$results    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => "Missing parameters"] ], 405);
			}
			
		} catch (Exception $e) {
			$results     = $e->getMessage();

			$response    = new WP_REST_Response(["errors" => ["code" => "swrc_get_fail", "message" => $results] ], 405);
		}

		return $results;
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