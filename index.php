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
            } if( (round($range_small_onboarding) < round($number_of_properties)) && (round($range_big_onboarding) < round($number_of_properties)) ) {
                // set static values :-(
                $onboarding_per_property_fee = '20000';
                $onboarding_minimum_fee = '25000';
            } else {
                // we assume no conditions were met and set min
                $onboarding_per_property_fee = '5000';
                $onboarding_minimum_fee = '500';
            }

        }

    }

}



/**
 * totalCostArray 
 * @author Archie M
 * 
 */
$sum1 = 0;
$sumResponsiveRepairs = array();
$totalCostArray = array();



/**
 * Get pricing rate details from HubDB object
 * @author Archie M
 */
foreach($pricing_rates_obj as $pricing_rates ) {

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
            array_push($val['values'],$range_explode[0]);   // push the minimum value for comparison
    
            // ?? maybe change to a case statement
            $min_value = (int)$val['values'][7];            // get value and covert to string
            $max_value = (int)$range_big;
            $max_properties = (int)$number_of_properties;

            if( $max_properties >= $min_value ) {

                // filter values based on selected services
                if( preg_grep('/^Responsive\s.*/', $selected_services) ) {
                    $responsive_repairs[] = $val['values'][2];
                } else {
                    $responsive_repairs[] = 0;
                }
                
                if( preg_grep('/^Tenant\s.*/', $selected_services) ) {
                    $tenant_app[] = $val['values'][3];       
                } else {
                    $tenant_app[] = 0;
                }
                
                if( preg_grep('/^Compliance\s.*/', $selected_services) ) {
                    $compliance[] = $val['values'][4];
                } else {
                    $compliance[] = 0;
                }
                
                if( preg_grep('/^OOH\s.*/', $selected_services) ) {
                    $ooh[] = $val['values'][5];
                } else {
                    $ooh[] = 0;
                }
                
                if( preg_grep('/^Emergency\s.*/', $selected_services) ) {
                    $emergency[] = $val['values'][6];
                } else {
                    $emergency[] = 0;
                }  

            } else { 
                
                //echo "more";
                
            }

        }

    }
    
}





//var_dump($responsive_repairs);

//var_dump($compliance);


/**
 * Calculate the costs (monthly fee and set up fee)
 * @author Archie M
 * 
 */
//  set up variables for calculation 
settype($total_services,'integer');

$responsive_repairs = floatval($responsive_repairs[0]);
$tenant_app = floatval($tenant_app[0]); 
$compliance = floatval($compliance[0]); 
$ooh = floatval($ooh[0]);
$emergency = floatval($emergency[0]);

// Determine onboarding fees
$onboarding_minimum_fee_total = $onboarding_minimum_fee;
$onboarding_per_property_fee = $onboarding_per_property_fee;


// 0 - 2500
if($max_properties <= 2500) {

    // Sum it all up
    $total_services = floatval($responsive_repairs + $tenant_app + $compliance + $ooh + $emergency);  
    $total_monthly_fee = ( $max_properties * $total_services );



    // test
    echo "Dummy Data " .'<br><br>'; 
    echo $responsive_repairs . '<br>';
    echo $tenant_app  . '<br>';
    echo $compliance . '<br>'; 
    echo $ooh  . '<br>';
    echo $emergency  . '<br>';

    echo "onboarding " . $onboarding_minimum_fee_total . '<br>';
    echo "property fee " . $onboarding_per_property_fee .'<br>';

    echo '<br>' . "Total services " . $total_monthly_fee . '<br>';

    
    // Calculation preview 
    echo '<h1>Calculations</h1><br>';
    echo "Number of properties: " . $number_of_properties . '<br>';
   
    echo "Total Monthly (all rates added): " . $total_services . '<br>';
    echo '<br>';
    echo "Onboarding property fee: " . $onboarding_minimum_fee_total . '<br>';
    echo "Set up fee: " . $onboarding_per_property_fee . '<br>';
    echo "<h3 style='font-weight:bold'>Monthly fee: " . $total_monthly_fee . '</h3>';
   
       
}

// 2501 - 5000
if( ($max_properties >= 2501) || ($max_properties <= 5000) ) {

    // Sum it all up
    $total_services = floatval($responsive_repairs + $tenant_app + $compliance + $ooh + $emergency);  

    $diff1 = ($max_properties - 2500);

    echo "Dif 1" . $diff1;


    $total_monthly_fee = ( $max_properties * $total_services );



}

// 5001 - 7500
if( ($max_properties >= 5001) || ($max_properties <= 7500) ) {

    $total_services = $responsive_repairs[0] + $tenant_app[0] + $compliance[0] + $ooh[0] + $emergency[0];

    //echo "true";
    
}

// 7501 - 10000 
if( ($max_properties >= 2501) || ($max_properties <= 5000) ) {

    $total_services = $responsive_repairs[0] + $tenant_app[0] + $compliance[0] + $ooh[0] + $emergency[0];


    
}
/*
// 10001 - 20000
if(x) {


}

// 20001 - 30000
if(x) {

    
}

//30001 - 40000
if(x) {

    
}

// 40001 - 50000
if(x) {

    
}

// 50001 - 60000
if(x) {

    
}

// 60001 - 70000
if(x) {

    
}

// 70001 - 80000
if(x) {

    
}
*/


/**
 * Update deal with calculated rates 
 * 
 */
function update_deal($deal_id) {



}


/****
 
    // test data
    echo "responsive " . $responsive_repairs . '<br>';
    echo "tenant " . $tenant_app . '<br>';
    echo 'compliance ' . $compliance . '<br>';
    echo 'ooh ' . $ooh . '<br>';
    echo 'emergency ' . $emergency . '<br>';

 */


/**
 * DELETE /// DELETE /// DELETE
 * Test data dump
 */
/*
echo '<br><br>';
echo '<h1>Calculations</h1>';
echo "Deal Id: " . $deal_id . '<br>';
echo "Number of properties: " . $number_of_properties . '<br>';
echo "Selected services: " . $selected_services_list .'<br>';

echo '<br><br>';

echo "Onboarding Minimum Fee: " . $onboarding_minimum_fee .'<br>';
echo "Onboarding Cost Per Property: " . $onboarding_per_property_fee .'<br>';

echo '<br><br>';

//echo "Current Pricing Range Min: " . $current_range_small .'<br>';
//echo "Current Pricing Range Max: " . $current_range_big .'<br>';

//echo '<br><br>';

/*
echo "Responsive " . $responsive_repairs . '<br>';
echo  "Tenant " . $tenant_app .'<br>';
echo "Compliance " . $compliance . '<br>';
echo  "Ooh " . $ooh . '<br>';
echo "Emergency " . $emergency . '<br>';
*/

//echo '<br>'. '____' . '<br><br>';