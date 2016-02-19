<?php
	// API to create a coupon
	
	require 'shopify.php';

	// Build up response object
	$response = array('errors'=>array());

	// Make sure all required parameters are passed in
	$required_params = array("storename", "username", "password", "code", "type");
	foreach ($required_params as $value) {
		if (!isset($_GET[$value])) {
			array_push($response['errors'], "Missing Parameter:" . $value );
		}
	}
	if ( count($response['errors']) > 0 ) {
		shortCircuit();
	}

	//Verify Discount Type
	$discount_code = $_GET['code'];
	$discount_type = $_GET['type'];
	if ($discount_type != 'percentage' && $discount_type != 'absolute') {
		array_push($response['errors'], 'Invalid discount_type:' . $discount_type );
		shortCircuit();
	}

	$shopify_storename = $_GET['storename'];
	$shopify_username = $_GET['username'];
	$shopify_password = $_GET['password'];
	$shopify_admin_url = "https://{$shopify_storename}.myshopify.com/admin";
	$api = new \Shopify\PrivateAPI($shopify_username, $shopify_password, $shopify_admin_url);

	echo $api->isLoggedIn();

 /*   if (!$api->isLoggedIn() && !$api->login()) {
    	echo "here";
        array_push($response['errors'], "Can't login");
        shortCircuit();
    } 
    # Set the CSRF token for the POST request
    $discount_url = $shopify_admin_url . '/discounts/new';

    try { $api->setToken($discount_url); } 
    catch (\Exception $ex) { }

    # Create a 5% discount coupon
    $new_discount = ['discount' => [
        'applies_to_id' => '',
        'code' => $discount_code,
        'discount_type' => 'percentage',
        'value' => 5,
        'usage_limit' => 1,
        'starts_at' => date('Y-m-d\TH:i:sO', mktime(0, 0, 0)),
        'ends_at' => null,
        'applies_once' => false
    ]];

    $do_discount = $api->doRequest('POST', 'discounts.json', $new_discount);

    echo 2;

    if (count($do_discount->errors->code) > 0 ) {
    	// Shopify Discount Cod Already exists
    	if ($do_discount->errors->code[0] === 'must be unique. Please try a different code.') {
    		array_push($response["errors"], "Existing Code Already exist: " . $discount_code);
    	}
    } else {
    	$response["success"] = true;
    	$response["output"] = $do_discount;
    }
   
*/

    echo json_encode($response);

	// dumps out response json and exits
	function shortCircuit() {
		global $response;
		echo json_encode($response);
		exit();
	}

?>