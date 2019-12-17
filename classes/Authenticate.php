<?php 
/**
 * Authentication / oAuth 
 * @author Anders G
 * 
 */

class Authenticate {

	/**
	 * Get stashed secret key 
	 * 
	 */
	private function getSecretKey () {


	}


	/**
	 * Get Access token
	 */
    public function getAccessToken() {
		
		$clientID = '6d89a357-651d-4b63-8762-df05c936b213';
		//$secretID = $this->getSecretKey();
		$secretID =	'f150812f-66c2-4a48-ba89-02da35736704'; 			// hide this shit
		//$redirectURL = 'https://struto.makuwa.co.za/';
		$redirectURL = 'http://plentificapi.local/';
		
		//$code = '8f96d5af-9156-4006-b885-e0734f14c464';
		//$refToken = '746ca702-6227-48e6-bf5e-64bdfc5bfd46';

        $endpoint = 'https://api.hubapi.com/oauth/v1/token';
        $grantRefType = 'refresh_token';
   
	    // The POST URL and parameters
	    $postArgs = 'grant_type=' . $grantRefType . '&client_id=' . $clientID . '&client_secret=' . $secretID. '&redirect_uri=' . $redirectURL. '&refresh_token=' . $refToken;
	    
		$session = curl_init($endpoint);

	    curl_setopt($session, CURLOPT_POST, true);
	    curl_setopt($session, CURLOPT_POSTFIELDS, $postArgs);
	    curl_setopt($session, CURLOPT_HEADER, 0);
	    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

	    $response = curl_exec($session);

	    curl_close($session);

	    $data = json_decode($response, true);
	    $authToken = $data['access_token'];


		// Testing
		echo "Access token :" . $authToken;


		return $authToken;
		
	}


}