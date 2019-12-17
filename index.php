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
$pricing_rates_obj = json_decode($pricing_rates_srt,true); 
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


echo '<pre>';
var_dump($selected_services);
echo '</pre>';


/**
 * Get pricing rate details from HubDB object (per property fee and min fee)
 * @author Archie M
 * 
 */
foreach($onboarding_fees_obj as $onboarding){

    if( is_array($onboarding) || is_object($onboarding) ) {

        //foreach ($onboarding as $onboarding_details) {   
        foreach ($onboarding as $onboarding_details => $val) {

            echo '<pre>';
            //var_dump($val);
            echo '</pre>';

            // init range cars
            $range_small_onboarding = '';
            $range_big_onboarding = '';
            $num_of_properties_onboarding = $val['values'][1];

            // remove dash and replace with commas
            $num_of_properties_range_onboarding = str_replace(" - ", ",", $num_of_properties_onboarding);



            // split into two variables
            $range_explode_onboarding = explode(',', $num_of_properties_range_onboarding);
            var_dump($range_explode_onboarding);

            //if( array_key_exists($range_small_onboarding, $range_explode_onboarding) ){
                $range_small_onboarding = round($range_explode_onboarding[0]);
                $range_big_onboarding = round($range_explode_onboarding[1]);
            //}
            
            // conditionally determine  if in range
            switch(true) {
                case ($number_of_properties, range($range_small_onboarding,$range_big_onboarding): 
                    $per_property_fee = $val['values'][3];
                    $minimum_fee = $val['values'][4];
                    break;
            
                // if not in range, we assume it's max
                /*
                default:                        
                    $per_property_fee = '20000';
                    $minimum_fee = '25000';
                    break;
                */
            }

        }

    }

}



/**
 * Get pricing rate details from HubDB object
 * @author Archie M
 */
foreach($pricing_rates_obj as $pricing_rates) {

    if( is_array($pricing_rates) || is_object($pricing_rates) ) {

        foreach ($pricing_rates as $pricing_rate_details => $val) {

            // init cost vars based on service costs (checks to see if service is selected)
            $num_of_properties = $val['values'][1];
            $responsive_repairs = $val['values'][2];
            $tenant_app = '';
            $compliance = '';
            $ooh = '';
            $emergency = ''; 
            
        
            // check if selected services exists in the array and return matching values if exists
            if( preg_grep('/^Tenant\s.*/', $selected_services) ) {
                $tenant_app = $val['values'][3];
            }
            if( preg_grep('/^Compliance\s.*/', $selected_services) ) {
                $compliance = $val['values'][4];
            }
            if( preg_grep('/^Ooh\s.*/', $selected_services) ) {
                $ooh = $val['values'][5];
            }
            if( preg_grep('/^Emergency\s.*/', $selected_services) ) {
                $emergency = $val['values'][6];
            }   
            /*
            if (array_key_exists($val, $selected_services)) {
                //echo $value['label'] . ' is complete<br>';
                echo "test";
            }
            */
            
            // remove dash and replace with comma
            $num_of_properties_range = str_replace(" - ", ",", $num_of_properties);

            // split into two variables
            $range_explode = explode(',', $num_of_properties_range);
            $range_small = round($range_explode[0]);
            $range_big = round($range_explode[1]);



            // conditionally determine if in range
            switch(true) {
                case in_array($number_of_properties, range($range_small,$range_big)): 
                        
                    $range_small = round($range_explode[0]);
                    $range_big = round($range_explode[1]);
                    /*
                    echo "Properties " . $number_of_properties . '<br>';
                    echo $responsive_repairs . '<br>';
                    echo $tenant_app . '<br>';
                    echo $compliance . '<br>';
                    echo $ooh  . '<br>';
                    echo $emergency  . '<br>';
                    */ 

                    break;
            
                // if not in range, we assume it's max    
                default:    
                    $responsive_repairs = '0.56';
                    $tenant_app = '0.3';
                    $compliance = '0.5';
                    $ooh = '0';
                    $emergency = '0.5';
                    //$myLastElement = array_key_last ( $number_of_properties );
                    //echo $myLastElement;
                    break;

            }

        }

    }

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

echo "Property Range Min: " . $minimum_fee .'<br>';
echo "Property Range Max: " . $per_property_fee .'<br>';

echo '<br><br>';

echo "Current Min Fee: " . $range_small_onboarding .'<br>';
echo "Current Max Fee: " . $range_big_onboarding .'<br>';

echo '<br><br>';


echo '';

echo   $tenant_app .'<br>';
echo    $compliance . '<br>';
 echo   $ooh . '<br>';
 echo $emergency . '<br>';

echo '<br>'. '____' . '<br><br>';

echo "Per Property fee: " . $per_property_fee;

echo "Determined services fees: " . '<br>';
//calculate_onboarding_fee($number_of_properties);


echo '<br>'. '____' . '<br><br>';


//echo "Determined onboarding fee: " . calculate_onboarding_fee($number_of_properties);


echo '<br>'. '____' . '<br><br>';


echo '<pre>';
//var_dump($json_obj);
//var_dump($onboarding_fees_obj);
//var_dump($onboarding_fees_srt);






/**
 * Update deal with calculated rates 
 * 
 */
function update_deal($deal_id) {



}

