<?php 

class Deals extends Authenticate {

    public function get_deal_data() {

        $endPoint = "https://api.hubapi.com/deals/v1/deal";
		
		$accesToken = $this->getAccessToken();

		if (!isset($accesToken)) {
			return array('error' => 'true', 'msg' => 'Error getting access token.');
		}
		
		$contact = new Contact();
		$contactID = $contact->getContactVID();

		if (!empty($contactID[0])) {
			$clientEmail = $contactID[0];
			$venueEmail = $contactID[1];
		} else {
			return array('error' => 'true', 'msg' => 'Create Booking Error 2: Please try again.'); //format of error msg to be decided
		}






    }



    /**
     * Calculate 
     * @author Archie M
     * 
     */
    



    /** 
     * Update Deal
     * 
     */
}