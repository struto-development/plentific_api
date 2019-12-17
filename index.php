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

//$authentication = new Authenticate();
$error_reporting = new ErrorReporting();
$tables_data = new StoredData();



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
$pricing_rates_obj = json_decode($pricing_rates_srt, true); 
//$pricing_rates_obj = json_decode(json_encode($pricing_rates_srt),true); 



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
$number_of_properties = floatval( isset($json_obj['properties']['power']['value']) ) ? $json_obj['properties']['power']['value'] : "";
$selected_services_list = ( isset($json_obj['properties']['product_type']['value']) ) ? $json_obj['properties']['product_type']['value'] : "";

// Convert selected dirty services to separate services in an array
$selected_services = explode(";", $selected_services_list);


/**
 * Get pricing rate details from HubDB object (per property fee and min fee)
 * @author Archie M
 * 
 */
foreach($onboarding_fees_obj as $onboarding ){

    if( is_array($onboarding) || is_object($onboarding) ) {

        //foreach ($onboarding as $onboarding_details) {   
        foreach ($onboarding as $onboarding_details => $val) {

            // init range cars
            $range_small_onboarding = '';
            $range_big_onboarding = '';
            $num_of_properties_onboarding = $val['values'][1];

            // remove dash and replace with commas
            $num_of_properties_range_onboarding = str_replace(" - ", ",", $num_of_properties_onboarding);

            // split into two variables
            $range_explode_onboarding = explode(',', $num_of_properties_range_onboarding);
            if( isset($range_explode_onboarding[1]) ) {
                $range_small_onboarding = round($range_explode_onboarding[0]);
                $range_big_onboarding = round($range_explode_onboarding[1]); 
            } else {
                $range_small_onboarding = round($range_explode_onboarding[0]);
                $range_big_onboarding = null; 
            }
                
            // conditionally determine  if in range
            if( ( round($range_small_onboarding) <= round($number_of_properties)) && (round($range_big_onboarding) <= round($number_of_properties)) ) {
                $onboarding_per_property_fee = $val['values'][3];
                $onboarding_minimum_fee = $val['values'][4]; 
            } elseif( (round($range_small_onboarding) < round($number_of_properties)) && (round($range_big_onboarding) < round($number_of_properties)) ) {
                // set static values :-(
                $onboarding_per_property_fee = '20000';
                $onboarding_minimum_fee = '25000';
            }

        }

    }

}



/**
 * Get pricing rate details from HubDB object
 * @author Archie M
 */
foreach($pricing_rates_obj as $pricing_rates ) {

    //
    //echo "Testing " . $val['values'][1];
    /*
    $responsive_repairs_val = $pricing_rates['values'][2];
    $tenant_app_val = $pricing_rates['values'][3];
    $compliance_val = $pricing_rates['values'][4];
    $ooh_val = $pricing_rates['values'][5];
    $emergency_val = $pricing_rates['values'][6]; 
    */

    if( is_array($pricing_rates) || is_object($pricing_rates) ) {
        
        foreach ($pricing_rates as $pricing_rate_details => $val) {

            // init cost vars based on service costs (checks to see if service is selected)
            $num_of_properties = $val['values'][1];
            
            // remove dash and replace with comma
            $num_of_properties_range = str_replace(" - ", ",", $num_of_properties);

            // split into two variables
            $range_explode = explode(',', $num_of_properties_range);
            $range_small = round($range_explode[0]);
            $range_big = round($range_explode[1]);

            // push max value into the array ???
            array_push($val['values'],$range_explode[1]);
    

            // test
            $max_value = (int)$val['values'][7];                 // get value and covert to string
            $max_properties = (int)$number_of_properties;

            if( $max_value <= $max_properties ) {
                
                echo "less" . '<br>';
                echo "properties " . $number_of_properties .'<br>';
                echo "max value " . $max_value;

                // if conditions are met, assign vars
                $responsive_repairs_val = $val['values'][2];
                $tenant_app_val = $val['values'][3];
                $compliance_val = $val['values'][4];
                $ooh_val = $val['values'][5];
                $emergency_val = $val['values'][6];


            } else {
                
                
                
                //echo "more";
            }




            // calculate
            if($max_properties < 2500) {

                echo "Less than 2500";

            }





            echo '<pre>';
            //var_dump($val);
            //var_dump($test_array);
            echo '</pre>';

            //echo $num_of_properties_range . "??????" . '<br>';
            //echo $range_small;


            // check to see if the array falls into range

            /*
            $responsive_repairs_val = $val['values'][2];
            $tenant_app_val = $val['values'][3];
            $compliance_val = $val['values'][4];
            $ooh_val = $val['values'][5];
            $emergency_val = $val['values'][6];
            */

            // conditionally determine if in range
            /*
            switch(true) {
                case in_array($number_of_properties, range($range_small,$range_big)): 
                    $current_range_small = round($range_explode[0]);
                    $current_range_big = round($range_explode[1]);
                    
                    $responsive_repairs_val = $val['values'][2];
                    $tenant_app_val = $val['values'][3];
                    $compliance_val = $val['values'][4];
                    $ooh_val = $val['values'][5];
                    $emergency_val = $val['values'][6]; 
                    break;
            
                // everything else here is in range, else null    
                default:    
                    $range_small = null;
                    $range_big = null;
                    break;

            }
            */

        }

    }
    
}



/**
 * Calculate the costs (monthly fee and set up fee)
 * @author Archie M
 * 
 */

// check to see if all product costs are not null and push to array
// get values
/*
foreach ($pricing_rates_obj as $price) {

    $max_value = $price['values'][7];
    if( ( round($max_value) <= round($num_of_properties)) ) {
        echo "test///////" . '<br>';
        echo "max value " . $max_value;
    }

}
*/





/**
 * Validate variables and determine costs 
 * @author Archie M
 * 
 */

if( preg_grep('/^Responsive\s.*/', $selected_services) ) {
    $responsive_repairs = $responsive_repairs_val;
} else {
    $responsive_repairs = null;
}

if( preg_grep('/^Tenant\s.*/', $selected_services) ) {
    $tenant_app = $tenant_app_val;        
} else {
    $tenant_app = null;
}

if( preg_grep('/^Compliance\s.*/', $selected_services) ) {
    $compliance = $compliance_val;
} else {
    $compliance = null;
}

if( preg_grep('/^OOH\s.*/', $selected_services) ) {
    $ooh = $ooh_val;
} else {
    $ooh = null;
}

if( preg_grep('/^Emergency\s.*/', $selected_services) ) {
    $emergency = $emergency_val;
} else {
    $emergency = null;
}  



/*

if( ( round($num_of_properties) <= round($max_value)) ) {
        
    echo "Responsive ".$responsive_repairs_val = $val['values'][2] . '<br>';
    echo "Tenant " . $tenant_app_val = $val['values'][3] . '<br>';
    echo "Compliance " . $compliance_val = $val['values'][4] . '<br>';
    echo "Ooh " . $ooh_val = $val['values'][5] . '<br>';
    echo "Emergency " . $emergency_val = $val['values'][6] . '<br>';
    
    echo "Max value " . $max_value;

    /*
    $responsive_repairs_val = $val['values'][2];
    $tenant_app_val = $val['values'][3];
    $compliance_val = $val['values'][4];
    $ooh_val = $val['values'][5];
    $emergency_val = $val['values'][6];
    *
}
*/

// sum


 $set_up_fee = '';
 $monthly_fee = '';






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
echo "Selected services: " . $selected_services_list .'<br>';

echo '<br><br>';

echo "Onboarding Minimum Fee: " . $onboarding_minimum_fee .'<br>';
echo "Onboarding Cost Per Property: " . $onboarding_per_property_fee .'<br>';

echo '<br><br>';

echo "Current Pricing Range Min: " . $current_range_small .'<br>';
echo "Current Pricing Range Max: " . $current_range_big .'<br>';

echo '<br><br>';

echo "Responsive " . $responsive_repairs . '<br>';
echo  "Tenant " . $tenant_app .'<br>';
echo "Compliance " . $compliance . '<br>';
echo  "Ooh " . $ooh . '<br>';
echo "Emergency " . $emergency . '<br>';

echo '<br>'. '____' . '<br><br>';