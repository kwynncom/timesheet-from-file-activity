<?php

require_once('config.php');

class ftotTestMode {
    
    public static function isTest() { 	return true; }
    public static function getPaths() {
	if (self::isTest()) return '/tmp/';
	return KWYNN_FTOT_DEFAULT_WATCH_PATHS;
    }
    public static function doRecursive() {
	return !self::isTest();
    }
}