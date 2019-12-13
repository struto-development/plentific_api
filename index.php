<?php 

/**
 * Force errors 
 * @author PHP Masters (hide or remove on production)
 * 
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



/**
 * Autoload classes from: 
 * - Authenticate 
 * – ErrorReporting
 * – StoredData - data from hubDB, deals and .... 
 * @author Archie M
 * 
 */
function __autoload($class) {

    $path = 'classes/';
    require_once  $path . $class .'.php';

}

$error_reporting = new ErrorReporting();
//$authentication = new Authenticate();
$tables_data = new StoredData();
//$update_deal = new updateDeal();



/**
 * Authentication / oAuth 
 * @author Anders G
 * 
 */




/**
 * Manage HubDB data for use with the rest of the project
 * @author Archie M
 * 
 */
// Get tables data
$onboarding_fees_srt = $tables_data->get_onboarding_fees();
$pricing_rates_srt = $tables_data->get_price_rates();

// Convert to associative array
$onboarding_fees_obj = json_decode($onboarding_fees_srt, true); /* json_decode($onboarding_fees_srt, true); */
$pricing_rates_obj = json_decode($pricing_rates_srt,true); 
//$pricing_rates_obj = json_decode(json_encode($pricing_rates_srt),true); 

//var_dump($onboarding_fees_obj);


/**
 * Get onboarding price row details from HubDB object
 */
foreach($onboarding_fees_obj as $onboarding){

    echo '<pre>';
    //var_dump($o);
    echo '<pre>';


    foreach ($onboarding as $onboarding_details) {

        var_dump($onboarding_details);

        echo "my id: " . $onboarding_details['id'] . '<br>';
        echo "my range: " . $onboarding_details['values'][1];
        echo "property_fee: " . $onboarding_details['values'][3];
        echo "min_fee: " . $onboarding_details['values'][4];

        return $onboarding_details;

    }

}


var_dump($onboarding_details);
echo "___" . '<br>';



/**
 * Get pricing rate details from HubDB object
 * @author Archie M
 * 
 */
foreach($pricing_rates_obj as $pricing) {

    echo '<pre>';
    //var_dump($pricing);
    echo '<pre>';

    foreach($pricing as $pricing_rate_details) {

    }


}


/**
 * Read incoming data from Web hook (or test file) and 
 * get all valid and required variables and do the maths
 * @author Anders G
 * 
 */  
$json_srt = file_get_contents('data/deal.json');            // Dev / Read incoming data dummy file  DELETE /// DELETE
//$json_srt = file_get_contents('php://input'); */          // production

// Convert in associated array
$json_obj = json_decode($json_srt, true);

// Initial error handling 
if ($json_obj === NULL && !isset($json_obj['vid'])) {
    echo "Invalid JSON provided";
    return;
}

// Get global variables 
$deal_id = ( isset( $json_obj['properties']['hs_object_id']['value']) ) ? $json_obj['properties']['hs_object_id']['value'] : "";
$number_of_properties = ( isset($json_obj['properties']['power']['value']) ) ? $json_obj['properties']['power']['value'] : "";
$selected_services_list = ( isset($json_obj['properties']['product_type']['value']) ) ? $json_obj['properties']['product_type']['value'] : "";

// Convert selected dirty services to separate services in an array
$selected_services = explode(";", $selected_services_list);


/** 
 * Get number of units from HubDB and based on that execute: 
 * - 
 * -
 * -
 * @author Archie M
 * 
 */
function number_of_units($number_of_properties ) {

    switch(true) {
        case in_array($number_of_properties, range(0,2500)): //these values can be replaced by the array (HubDB)
            tier1_func();
        break;
    
        case in_array($number_of_properties, range(2501,5000)):
            tier2_func();
        break;
    
        case in_array($number_of_properties, range(5001,7500)):
            tier3_func();
        break;
    
        case in_array($number_of_properties, range(7501,10000)): 
            tier4_func();
        break;
    
        case in_array($number_of_properties, range(10001,20000)): 

        break;
    
        case in_array($number_of_properties, range(20001,30000)):

        break;
    
        case in_array($number_of_properties, range(30001,40000)): 

        break;
    
        case in_array($number_of_properties, range(40001,50000)): 

        break;
    
        case in_array($number_of_properties, range(50001,60000)): 

        break;
    
        case in_array($number_of_properties, range(60001,70000)):

        break;
    
        case in_array($number_of_properties, range(70001,80000)): 

        break;

        default: 
            $number_of_properties > '70001';

        break;

    }
    
}


/**
 * Get services rates based on the total amount of properties.
 * Makes of us: 
 * – HubDD
 * – number of properties global variable 
 * @author Archie M
 * 
 */
function get_services_rates($number_of_properties) {

    //if() {

    //}

}



/** 
 * Determine onboarding feee
 * @author Archie 
 * 
 */
function calculate_onboarding_fee($number_of_properties) {

    // get dynamic hubDB values to use in the condition below



    // run logic to check boarding fee against DB values
    switch(true) {

        case in_array($number_of_properties, range(0,249)): //these values can be replaced by the array (HubDB)
            $once_off_onboarding_fee = '5000';
            return $once_off_onboarding_fee;
        break;

        case in_array($number_of_properties, range(250,500)):
            $once_off_onboarding_fee = '5000';
            return $once_off_onboarding_fee;
        break;

        case in_array($number_of_properties, range(501,1000)):
            $once_off_onboarding_fee = '5000';
            return $once_off_onboarding_fee;
        break;

        case in_array($number_of_properties, range(1001,5000)): 
            $once_off_onboarding_fee = '5000';
            return $once_off_onboarding_fee;
        break;

        case in_array($number_of_properties, range(5001,10000)): 
            $once_off_onboarding_fee = '10000';
            return $once_off_onboarding_fee;
        break;

        case in_array($number_of_properties, range(10001,25000)): 
            $once_off_onboarding_fee = '15000';
            return $once_off_onboarding_fee;
        break;

        case $number_of_properties >= 25000: 
            $once_off_onboarding_fee = '20000';
            return $once_off_onboarding_fee;
        break;

    }

}



/**
 * Get vars for calculation from deal 
 * @author Archie M
 * 
 */
function calculate_monthly_fee($number_of_properties) {

    // first calculation is always by 2500
    if( $number_of_properties <= 2500 ) {
        //$ = ( $number_of_properties * )

        
    }
    

}



/**
 * Update deal with calculated rates 
 * 
 */
function update_deal($deal_id) {



}





/**
 * DELETE /// DELETE /// DELETE
 * Test data dump
 */

echo '<br><br>';
echo '<h1>Calculations</h1>';
echo "Deal Id: " . $deal_id . '<br>';
echo "Number of properties: " . $number_of_properties . '<br>';
echo "Selected services: " . '<br>';
var_dump($selected_services);


echo '<br>'. '____' . '<br><br>';

echo "Determined services fees: " . '<br>';
//calculate_onboarding_fee($number_of_properties);


echo '<br>'. '____' . '<br><br>';


echo "Determined onboarding fee: " . calculate_onboarding_fee($number_of_properties);


echo '<br>'. '____' . '<br><br>';


echo '<pre>';
//var_dump($json_obj);
var_dump($onboarding_fees_obj);
var_dump($onboarding_fees_srt);


