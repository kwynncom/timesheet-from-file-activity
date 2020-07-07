<?php

class ftt {
    public static function pathToArray($pin) {
	
	// Note for future reference, around 12 levels should be enough, based on mlaw code tree
	
	$a = explode('/', $pin);
	foreach ($a as $k => $v) if (!trim($v)) unset($a[$k]);
	$a = array_values($a);
	return $a;
    }
    
    
}