<?php 

//class StoredData {
class StoredData extends Authenticate { 


    public function get_onboarding_fees($portalID) {

		// Temp data
		/*
		$portalID = '6827351';
		$tableID = '2033380';
		*/

		$endpoint = 'https://api.hubapi.com/hubdb/api/v1/tables';
        $queryString = build_query_string(['portalId' => $portalId]);
        return $this->client->request('get', $endpoint, [], $queryString);

		$accessToken = $this->getAccessToken();

		if (!isset($accessToken)) {
			return array('error' => 'true', 'msg' => 'Error getting access token.');
		}

		$header = array( 
            'Content-type: application/json',
            'Authorization: Bearer '.$accessToken
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

		$response = curl_exec($session);

		if (curl_errno($session)) {
			return array('error' => 'true', 'msg' => 'Get HubDB table error: '.curl_error($session));
		}




		$response = json_decode($response, true);

		var_dump('Response:', $response);



		curl_close($session);	


    }

    
}

