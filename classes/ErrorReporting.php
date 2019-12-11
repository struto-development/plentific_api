<?php
/**
 * Logger class. 
 * Logs to file and defaults to a /logs/ subdirectory under this script location
 * 
 * This is a bit mickey-mouse as loggers go, but helpful.
 * 
 */
// ------------------------------------------------------------------------- //
//debug logger to dump to log files. Could be enhanced to do all sorts of things
//but this just allows internal data structures/messages to be captured easily.
//log directory needs to exist - simple version does not auto-create directory.

class ErrorReporting {
    //log levels
    const NO_LOG    = 5;
    const ERROR     = 4;
    const WARNING   = 3;
    const NOTICE    = 2;
    const INFO      = 1;
    const DEBUG     = 0;
    
    private static $filePath = null;
    
    private static $currentLogLevel = self::ERROR;
    public static function setFilepath($path = null) {
        
        if(!is_null($path)) {
            self::$filePath = $path;
        }
        
    }
    //set current log level - use constants
    public static function setLogLevel($level) {
        self::$currentLogLevel = $level;
    }
    //actual log calls - check for level & only continue if allowed by current
    public static function logDebug() {
        if(self::$currentLogLevel <= self::DEBUG) {
            $args = func_get_args();
            array_unshift($args,'DEBUG');
            call_user_func_array('self::log', $args);
        }
    }
    public static function logInfo() {
        if(self::$currentLogLevel <= self::INFO) {
            $args = func_get_args();
            array_unshift($args,'INFO');
            call_user_func_array('self::log', $args);
        }
    }
    public static function logNotice() {
        if(self::$currentLogLevel <= self::NOTICE) {
            $args = func_get_args();
            array_unshift($args,'NOTICE');
            call_user_func_array('self::log', $args);
        }
    }
    public static function logWarning() {
        if(self::$currentLogLevel <= self::WARNING) {
            $args = func_get_args();
            array_unshift($args,'WARNING');
            call_user_func_array('self::log', $args);
        }
    }
    public static function logError() {
        if(self::$currentLogLevel <= self::ERROR) {
            $args = func_get_args();
            array_unshift($args,'ERROR');
            call_user_func_array('self::log', $args);
        }
    }
    
    //log routine - can take any number of args, first one is the log level
    private static function log() {
        $args = func_get_args();
        $level = array_shift($args);
        $message = '';
        
        if(is_null(self::$filePath)) {
            self::$filePath = dirname(__FILE__)."/logs/";
        }
        
        foreach ($args as $arg) {
            if(!is_string($arg)) {
                //output as php variable unless a string
                $message .= print_r($arg, true);
            } else {
                $message .= $arg . "\n";
            }
        }
        //open file and suppress errors - silently fail function if error
        $fd = @fopen(self::$filePath . 'log-'.date('Y-m-d').'.txt', 'a');
    
        if($fd !== false) {
            //message header
            $date_str = date("Y-m-d H:i:s") . " - ";
    
            @fwrite($fd, $level . '::' . $date_str . $message);
    
            @fclose($fd);
        }
            
    }
} //eoc
