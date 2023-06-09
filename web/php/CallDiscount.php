<?php
/*
    File: CallDiscount.php 
    Purpose: call curl library to get discount
    Author: Pan Rubin
    Date: 04/06/2022
*/

function getDiscount($totalAmount)
{
	if (!extension_loaded("curl")) {
		die("enable library curl first");
	}

	$url = "http://127.0.0.1:8080//api/discountCalculator?discount={$totalAmount}";   # URL is to make GET request to Python RESTful API

	// Initializes a new cURL session
	$curl = curl_init($url);   # Initialize a cURL session
	// to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);   # Perform a cURL session
	curl_close($curl);
	$data = json_decode($response, true);
	return $data['discount'];
}
?>