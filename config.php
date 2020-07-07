<?php

require_once('/opt/kwynn/kwutils.php');
// define('KWYNN_FTOT_MIN_REMAIN_GB', 4); // ** move below


class ftotConfig {
    
    const outputf = '/tmp/kftot';
    const linelimit = PHP_INT_MAX;
    const maxGB =  2;
    
    public static function getPaths() {
	
	static $cu = false;
	
	// if (self::isTest()) return '/tmp/';
	
	if (!$cu) $cu = get_current_user();
	$path = '/home/' . $cu . '/';

	return $path;
    }   
    
    public static function getInotLogFile() { return self::outputf;    }
    public static function getMaxLines()    { 
	if (!self::isTest()) return self::linelimit; 
	
	return 10000;
	
    }
    public static function getMaxGB()       { 
	if (!self::isTest())  return self::maxGB; 
	
	return 0.001;
	
    }
    
    public static function isTest() { 	return true; }

    public static function doRecursive() { 
	return true;
	return !self::isTest();     }
    // public static function 
}