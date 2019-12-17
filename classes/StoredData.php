<?php 

//class StoredData {
class StoredData extends Authenticate { 

	/**
	 * Get onboarding fees from HubDB
	 * @author Archie M
	 * 
	 */
    public function get_onboarding_fees() {

		$portalID = '6827351';
		$onBoardingTblId = '2033380';

		/*
		$endpoint = 'https://api.hubapi.com/hubdb/api/v1/tables';
        $queryString = build_query_string(['portalId' => $portalId]);
        return $this->client->request('get', $endpoint, [], $queryString);

		$accessToken = $this->getAccessToken();

		if (!isset($accessToken)) {
			return array('error' => 'true', 'msg' => 'Error getting access token.');
		}
		*/

		$endPoint = "https://api.hubapi.com/hubdb/api/v2/tables/";
		$queryString = $onBoardingTblId."/rows?portalId=".$portalID;

		$header = array( 
            'Content-type: application/json',
            //'Authorization: Bearer '.$accessToken
		);

		//Curl
		$session = curl_init();
		curl_setopt_array($session, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_URL => $endPoint.$queryString,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($session);

		if (curl_errno($session)) {
			return array('error' => 'true', 'msg' => 'Error obtaining HubDB tables: '.curl_error($session));
		}

		curl_close($session);	

		//$response = json_decode($response, true);
		return $response;


	}


	/**
	 * Get Plentific pricing rates from HubDB
	 * @author Archie M
	 * 
	 */
	public function get_price_rates() {

		$portalID = '6827351';
		$onBoardingTblId = '2033374';

		/*
		$endpoint = 'https://api.hubapi.com/hubdb/api/v1/tables';
        $queryString = build_query_string(['portalId' => $portalId]);
        return $this->client->request('get', $endpoint, [], $queryString);

		$accessToken = $this->getAccessToken();

		if (!isset($accessToken)) {
			return array('error' => 'true', 'msg' => 'Error getting access token.');
		}
		*/

		$endPoint = "https://api.hubapi.com/hubdb/api/v2/tables/";
		$queryString = $onBoardingTblId."/rows?portalId=".$portalID;

		$header = array( 
            'Content-type: application/json',
            //'Authorization: Bearer '.$accessToken
		);

		//Curl
		$session = curl_init();
		curl_setopt_array($session, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_URL => $endPoint.$queryString,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($session);

		if (curl_errno($session)) {
			return array('error' => 'true', 'msg' => 'Error obtaining HubDB tables: '.curl_error($session));
		}

		curl_close($session);	

		//$response = json_decode($response, true);
		return $response;

	}


	/**
	 * Get posted deal details
	 */
	public function get_deal_details($deal_id) {

		// Temp credentials 
		$apiKey = '599f031c-64c2-45cf-80d1-a1eecbf2bccd';


		$endPoint = "https://api.hubapi.com/deals/v1/deal/";
		$queryString = $deal_id."?hapikey=".$apiKey;

		$header = array( 
            'Content-type: application/json',
            //'Authorization: Bearer '.$accessToken
		);



	}


	/**
	 * Get selected services 
	 * @author Archie M
	 * 
	 */
	function get_selected_services() {


		
	}


    
}



/**
 * Example function
 * 
 * 
 */
/*
function test(){

	echo "Function called";

}

$a = 1;
switch($a)
{
 
case 2:
	echo "Its in 2";
break;
case 1:
	echo "Its in 1";
	test();
break;
 
}
*/