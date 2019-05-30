<?php

//elavon payments 

//add shortcode
function create_form() {
    return ' 
	<div class="elavonForm">
	<form name="Elavonform" method="POST" action="">
    <input type="submit" name="getform" value="Make Payment Online">
    </form>
	<div>
	';
}
add_shortcode('getform', 'create_form');

if (isset($_POST["getform"])) {

	//request session token 
	sessionTokenRequest();
};

//request session token and redirect to HPP 
function ke_sessionTokenRequest() {
   // Provide Converge Credentials
  $merchantID = "XXXXXX"; //Converge 6-Digit Account ID 
  $merchantUserID = "ConvergeAPIHostedUser"; //Converge User ID 
  $merchantPIN = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"; //Converge PIN (64 CHAR)

  $url = "https://demo.convergepay.com/hosted-payments/transaction_token"; // URL to Converge demo session token server
  //$url = "https://www.convergepay.com/hosted-payments/transaction_token"; // URL to Converge production session token server

  /*Payment Field Variables*/

  // In this section, we set variables to be captured by the PHP file and passed to Converge in the curl request.

  $amount= '1.00'; //Hard-coded transaction amount for testing.

  $ch = curl_init();    // initialize curl handle
  curl_setopt($ch, CURLOPT_URL,$url); // set POST target URL
  curl_setopt($ch,CURLOPT_POST, true); // set POST method
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  //Build the request for the session id. Make sure all payment field variables created above get included in the CURLOPT_POSTFIELDS section below.

  curl_setopt($ch,CURLOPT_POSTFIELDS,
  "ssl_merchant_id=$merchantID".
  "&ssl_user_id=$merchantUserID".
  "&ssl_pin=$merchantPIN".
  "&ssl_transaction_type=ccsale"
  );

  $result = curl_exec($ch); // run the curl to post to Converge
  curl_close($ch); // Close cURL

//urlencode result and redirect to hosted payments page 
	$sessiontoken= urlencode($result);
	$hppurl = "https://demo.convergepay.com/hosted-payments?ssl_txn_auth_token=$sessiontoken"; // URL to the demo Hosted Payments Page
	//$hppurl = "https://www.convergepay.com/hosted-payments?ssl_txn_auth_token=$sessiontoken"; //URL to prod Hosted Payments Page
  
	wp_redirect($hppurl);
		exit;
  };
    
	//header("Location: https://demo.convergepay.com/hosted-payments?ssl_txn_auth_token=$sessiontoken");  //Demo Redirect
	//header("Location: https://www.convergepay.com/hosted-payments?ssl_txn_auth_token=$sessiontoken"); //Prod Redirect
