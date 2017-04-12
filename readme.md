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
| `oauth_timestamp`   | `string` | no       | Custom oAuth timestamp, default is `time()`|                                 

### For GET request to retrieve item from Woocommerce:
[POST] http://sitename.com/wp-json/swrc/v1/get

|       Option      |   Type   | Required |                Description                 					 |
| ----------------- | -------- | -------- | -------------------------------------------------------------|
| `base_url`        | `string` | yes      | Your Store URL, example: http://woo.dev/   			 		 |
| `consumer_key`    | `string` | yes      | Your API consumer key                      					 |
| `consumer_secret` | `string` | yes      | Your API consumer secret                   					 |
| `options`         | `json`  | no       | Extra arguments (see client options table). e.g: {"wp_api":  true, "version": "wc/v1"}] 				 |
| `endpoint`        | `string` | yes      | WooCommerce API endpoint, example: `customers` or `order/12` |
| `parameters`      | `json` | no        | Only for GET and DELETE. e.g: {"page": 1, "per_page": 5}                |
                                           
#### Example in Java Android:

```java
String url = "http://sitename.com/wp-json/swrc/v1/get";
OkHttpClient client = new OkHttpClient();
RequestBody formBody = new FormBody.Builder()
        .add("base_url", "http://sitename.com")
        .add("consumer_key", "ck_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
        .add("consumer_secret", "cs_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
        .add("options[wp_api]", "true")
        .add("options[version]", "wc/v1")
        .add("endpoint", "products")
        .add("parameters[page]", "1")
        .add("parameters[per_page]", "4")
        .build();

Request request = new Request.Builder()
        .url(url)
        .post(formBody)
        .build();

client.newCall(request).enqueue(new Callback() {
    @Override
    public void onFailure(Call call, IOException e) {
        e.printStackTrace();
    }

    @Override
    public void onResponse(Call call, Response response) throws IOException {
        System.out.println("Response: "+response.body().string());
    }
});
```

### For POST request to create new item:
[POST] http://sitename.com/wp-json/swrc/v1/post

|       Option      |   Type   | Required |                Description                 					 |
| ----------------- | -------- | -------- | -------------------------------------------------------------|
| `base_url`        | `string` | yes      | Your Store URL, example: http://woo.dev/   			 		 |
| `consumer_key`    | `string` | yes      | Your API consumer key                      					 |
| `consumer_secret` | `string` | yes      | Your API consumer secret                   					 |
| `options`         | `json`  | no       | Extra arguments (see client options table). e.g: {"wp_api":  true, "version": "wc/v1"}]					 |
| `endpoint`        | `string` | yes      | WooCommerce API endpoint, example: `customers` or `order/12` |
| `data`            | `json`  | yes      | Only for POST and PUT, e.g: {"name" : "Product A", "regular_price" : 2500}  |
 
 #### Example in Java Android:

 ```java
String url = "http://sitename.com/wp-json/swrc/v1/post";
OkHttpClient client = new OkHttpClient();
RequestBody formBody = new FormBody.Builder()
        .add("base_url", "http://sitename.com")
        .add("consumer_key", "ck_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
        .add("consumer_secret", "cs_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
        .add("options[wp_api]", "true")
        .add("options[version]", "wc/v1")
        .add("endpoint", "products")
        .add("data[name]", "Ultra T-Shirt")
        .add("data[regular_price]", "125000")
        .add("data[type]", "simple")
        .add("data[description]", "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.")
        .build();

Request request = new Request.Builder()
        .url(url)
        .post(formBody)
        .build();

client.newCall(request).enqueue(new Callback() {
    @Override
    public void onFailure(Call call, IOException e) {
        e.printStackTrace();
    }

    @Override
    public void onResponse(Call call, Response response) throws IOException {
        System.out.println("RESPONSE CODE: "+response.code());
        System.out.println("RESPONSE: "+response.body().string());
    }
});
```                                          

### For PUT request to update item by id:
[POST] http://sitename.com/wp-json/swrc/v1/put/{id}

|       Option      |   Type   | Required |                Description                 					 |
| ----------------- | -------- | -------- | -------------------------------------------------------------|
| `base_url`        | `string` | yes      | Your Store URL, example: http://woo.dev/   			 		 |
| `consumer_key`    | `string` | yes      | Your API consumer key                      					 |
| `consumer_secret` | `string` | yes      | Your API consumer secret                   					 |
| `options`         | `json`  | no       | Extra arguments (see client options table). e.g: {"wp_api":  true, "version": "wc/v1"}] 				 |
| `endpoint`        | `string` | yes      | WooCommerce API endpoint, example: `customers` or `order/12` |
| `data`            | `array`  | yes      | Only for POST and PUT. e.g: {"regular_price" : "2500"}   									 |
                                           
#### Example in Java

```java
        String url = "http://sitename.com/wp-json/swrc/v1/put/44";
        OkHttpClient client = new OkHttpClient();
        RequestBody formBody = new FormBody.Builder()
                .add("base_url", "http://sitename.com")
                .add("consumer_key", "ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx")
                .add("consumer_secret", "cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx")
                .add("options[wp_api]", "true")
                .add("options[version]", "wc/v1")
                .add("endpoint", "products")
                .add("data[regular_price]", "55000")
                .build();

        Request request = new Request.Builder()
                .url(url)
                .post(formBody)
                .build();

        client.newCall(request).enqueue(new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                e.printStackTrace();
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                System.out.println("RESPONSE CODE: "+response.code());
                System.out.println("RESPONSE: "+response.body().string());
            }
        });
    }
```

### For DELETE request to delete item by id:
[POST] http://sitename.com/wp-json/swrc/v1/delete/{id}

|       Option      |   Type   | Required |                Description                 					 |
| ----------------- | -------- | -------- | -------------------------------------------------------------|
| `base_url`        | `string` | yes      | Your Store URL, example: http://woo.dev/   			 		 |
| `consumer_key`    | `string` | yes      | Your API consumer key                      					 |
| `consumer_secret` | `string` | yes      | Your API consumer secret                   					 |
| `options`         | `json`  | no       | Extra arguments (see client options table). e.g: {"wp_api":  true, "version": "wc/v1"}] 				 |
| `endpoint`        | `string` | yes      | WooCommerce API endpoint, example: `customers` or `order/12` |
| `parameters`      | `json`  | no       | Only for GET and DELETE, e.g: {"force" : true}								         |
 #### Example in Java Android
 ```java
        String url = "http://sitename.com/wp-json/swrc/v1/delete/103";
        OkHttpClient client = new OkHttpClient();
        RequestBody formBody = new FormBody.Builder()
                .add("base_url", "http://sitename.com")
                .add("consumer_key", "ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx")
                .add("consumer_secret", "cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx")
                .add("options[wp_api]", "true")
                .add("options[version]", "wc/v1")
                .add("endpoint", "products")
                .add("parameters[force]", "true")
                .build();

        Request request = new Request.Builder()
                .url(url)
                .post(formBody)
                .build();

        client.newCall(request).enqueue(new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                e.printStackTrace();
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                System.out.println("RESPONSE CODE: "+response.code());
                System.out.println("RESPONSE: "+response.body().string());
            }
        });
 ```                                          

## Credits
* WooCommerce API - PHP Client 1.1.3 by Automattic licensed under MIT

## License
The MIT License (MIT)
Copyright (c) 2016, Septian Ahmad Fujianto (https://septianfujianto.com/)