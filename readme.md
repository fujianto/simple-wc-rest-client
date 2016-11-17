# Simple Woocommerce Rest Client

A Woocommerce client to make accessing Woocommerce Rest API simpler. Web App and Mobile App will be able to access Woocommerce API without having to do complicated OAuth signing. Require valid Consumer key and secret from Woocommerce.

## Getting started

Make sure you have valid Consumer Key and Consumer Secret for the target Woocommerce website. This plugin could be installed in any website, not necessarily on the target Woocommerce site. Make sure all request is in POST format. Access the API from url below.

#### Client options

|        Option       |   Type   | Required |                                                      Description                                                       |
|---------------------|----------|----------|------------------------------------------------------------------------------------------------------------------------|
| `wp_api`            | `bool`   | no       | Allow make requests to the new WP REST API integration (WooCommerce 2.6 or later)                                      |
| `wp_api_prefix`     | `string` | no       | Custom WP REST API URL prefix, used to support custom prefixes created with the `rest_url_prefix` filter               |
| `version`           | `string` | no       | API version, default is `v3`                                                                                           |
| `timeout`           | `int`    | no       | Request timeout, default is `15`                                                                                       |
| `verify_ssl`        | `bool`   | no       | Verify SSL when connect, use this option as `false` when need to test with self-signed certificates, default is `true` |
| `query_string_auth` | `bool`   | no       | Force Basic Authentication as query string when `true` and using under HTTPS, default is `false`                       |
| `oauth_timestamp`   | `string` | no       | Custom oAuth timestamp, default is `time()`                                 

### For GET request to retrieve item from Woocommerce:
[POST] http://sitename.com/wp-json/swrc/v1/get

|       Option      |   Type   | Required |                Description                 					 |
| ----------------- | -------- | -------- | -------------------------------------------------------------|
| `base_url`        | `string` | yes      | Your Store URL, example: http://woo.dev/   			 		 |
| `consumer_key`    | `string` | yes      | Your API consumer key                      					 |
| `consumer_secret` | `string` | yes      | Your API consumer secret                   					 |
| `options`         | `array`  | no       | Extra arguments (see client options table). In JSON String. e.g: {"wp_api": true, "version" : "wc/v1"} 					 |
| `endpoint`        | `string` | yes      | WooCommerce API endpoint, example: `customers` or `order/12` |
| `parameters`      | `string` | no       | Only for GET and DELETE, request query string. In JSON String. e.g: {"page": 5, "per_page" : 2}                |
                                           |

### For POST request to create new item:
[POST] http://sitename.com/wp-json/swrc/v1/post
|       Option      |   Type   | Required |                Description                 					 |
| ----------------- | -------- | -------- | -------------------------------------------------------------|
| `base_url`        | `string` | yes      | Your Store URL, example: http://woo.dev/   			 		 |
| `consumer_key`    | `string` | yes      | Your API consumer key                      					 |
| `consumer_secret` | `string` | yes      | Your API consumer secret                   					 |
| `options`         | `array`  | no       | Extra arguments (see client options table). In JSON String. e.g: {"wp_api": true, "version" : "wc/v1"} 					 |
| `endpoint`        | `string` | yes      | WooCommerce API endpoint, example: `customers` or `order/12` |
| `data`            | `string`  | yes      | Only for POST and PUT,  In JSON String. e.g: { "name": "Ultra Premium Quality",   "type": "simple",   "regular_price": "2199"}   |
                                           |

### For PUT request to update item by id:
[POST] http://sitename.com/wp-json/swrc/v1/put/{id}
|       Option      |   Type   | Required |                Description                 					 |
| ----------------- | -------- | -------- | -------------------------------------------------------------|
| `base_url`        | `string` | yes      | Your Store URL, example: http://woo.dev/   			 		 |
| `consumer_key`    | `string` | yes      | Your API consumer key                      					 |
| `consumer_secret` | `string` | yes      | Your API consumer secret                   					 |
| `options`         | `array`  | no       | Extra arguments (see client options table). In JSON String. e.g: {"wp_api": true, "version" : "wc/v1"} 					 |
| `endpoint`        | `string` | yes      | WooCommerce API endpoint, example: `customers` or `order/12` |
| `data`            | `string`  | yes      | Only for POST and PUT,  In JSON String. e.g: { "regular_price": "2199"}   |
                                           |

### For DELETE request to delete item by id:
[POST] http://sitename.com/wp-json/swrc/v1/delete/{id}
|       Option      |   Type   | Required |                Description                 					 |
| ----------------- | -------- | -------- | -------------------------------------------------------------|
| `base_url`        | `string` | yes      | Your Store URL, example: http://woo.dev/   			 		 |
| `consumer_key`    | `string` | yes      | Your API consumer key                      					 |
| `consumer_secret` | `string` | yes      | Your API consumer secret                   					 |
| `options`         | `array`  | no       | Extra arguments (see client options table). In JSON String. e.g: {"wp_api": true, "version" : "wc/v1"} 					 |
| `endpoint`        | `string` | yes      | WooCommerce API endpoint, example: `customers` or `order/12` |
| `parameters`      | `string` | no       | Only for GET and DELETE, request query string. In JSON String. e.g: {"force":  false}               |
                                           |

## Credits
* WooCommerce API - PHP Client 1.1.3 by Automattic licensed under MIT

## License
The MIT License (MIT)
Copyright (c) 2016, Septian Ahmad Fujianto (https://septianfujianto.com/)