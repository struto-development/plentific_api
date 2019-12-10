<?php 

/**
 * Debug
 * Force all errors to show 
 * 
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/**
 * Load required classes
 * 
 */
include_once('classes/Error_Reporting.php');
include_once('classes/Authenticate.php');
include_once('classes/StoredData.php');
//include_once('classes/Deals.php');
//include_once('classes/Error_Reporting.php');





/**
 * Temp variables
 * /// DELETE OR KEEP SAFE
 *
 */   
$app_id = '207914';
/*
$client_id = '6d89a357-651d-4b63-8762-df05c936b213';
$client_secret = 'f150812f-66c2-4a48-ba89-02da35736704';
$access_token = '';

$api_key = '599f031c-64c2-45cf-80d1-a1eecbf2bccd';
*/


/**
 * Define reusable vars
 * @author Archie M
 * 
 */
$portalId = '6827351';
$pricing_tbl_id = '2033374';
$onboarding_fees_tbl_id = '2033380';




/** 
 * OAuth 
 * @author Anders Grove
 * 
 */
/*
function getAccessToken($endpoint, $clientID, $secretID, $redirectURL, $refToken) {
     
    // The POST URL and parameters
    $postargs = 'grant_type=refresh_token&client_id='.$clientID.'&client_secret='.$secretID.'&redirect_uri='.$redirectURL.'&refresh_token='.$refToken;
    
    // Get the curl session object
    $session = curl_init($endpoint);

    // Set the POST options.
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
    curl_setopt($session, CURLOPT_HEADER, 0);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

    // Do the POST 
    $response = curl_exec($session);

    // Get error codes
    $status_code = curl_getinfo($session, CURLINFO_HTTP_CODE);
    $curl_errors = curl_error($session);

    //Close the session
    curl_close($session);

    $data = json_decode($response, true);
    $authToken = $data['access_token'];
    
    return $authToken;

}
*/


/**
 * Get table data
 * 
 */
/*
function get_pricing_rates( $pricing_tbl_id ) {


		$accesToken = $this->getAccessToken();

		if (!isset($accesToken)) {
			return array('error' => 'true', 'msg' => 'Error getting access token.');
		}

		$header = array( 
            'Content-type: application/json',
            'Authorization: Bearer '.$accesToken
        );

		//Curl
		$session = curl_init();
		curl_setopt_array($session, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $endPoint.$postargs,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_CONNECTTIMEOUT  => 0,
			CURLOPT_TIMEOUT  => 20
		));

		$resp = curl_exec($session);

		if (curl_errno($session)) {
			return array('error' => 'true', 'msg' => 'Get Contact VID Error: '.curl_error($session));
		}

		curl_close($session);			

		//Decode, loop through and bind to array
		$arr = json_decode($resp,true);

    //https://api.hubapi.com/hubdb/api/v2/tables/300081/rows/draft?hapikey=demo


}
*/


/** 
 * Get onboarding fees
 * 
 */ 
/*
function get_onboarding_fees() {


}




/**
 * Calculate Prices
 * 
 *
function calculate_prices() {





}




/**
 * 
 * 
 *
function get_deal($name, $id, $type) {

    

}



/**
 * Update deal after calculation 
 * 
 *
function update_calculated_deal() {


}
*/