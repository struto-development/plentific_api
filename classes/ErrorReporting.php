<?php
/**
 * Php error logging file for Plentific API 
 * @author Archie M
 * 
 */

 class ErrorReporting {

    // Create error log folder and file
    public function create_error_log() {

        // Declare vars 
        $date = date("d-m-Y, H:i:s");;
        $folder = 'error_logs';
        $file = 'log.txt';


        // Create an error log folder if there is none on load and change folder permissions
        if ( !file_exists($folder) ) {
            mkdir($folder, 0777, true);
        }


        // create log file in folder if it doesn't exist
        if( !is_file($file) ) {
            $new_file = $folder.'/'.$file;
            $contents = 'Plentific API error log file. Created on '.$date.PHP_EOL;
            file_put_contents($new_file, $contents);
        }


        // Monitor and write errors to log file
        //error_log(print_r($v, TRUE), 3, $new_file);

    }

 }


