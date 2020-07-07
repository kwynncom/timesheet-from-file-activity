<?php

function logreg1($i, $l) {
    static $sz = 0;
    static $fmax = false;
    
    if ($fmax === false) {
	 $cmax = ftotConfig::getMaxGB();
	 kwas(is_numeric($cmax), 'ftot max not numeric 1416');
	 $mby = 1 << 30;
	 $fmax = intval(round($cmax * $mby)); unset($cmax, $mby);
	 kwas(is_integer($fmax), 'file max is not integer 1414');
    }
    
    $sl  = strlen($l);
    $sz += $sl;
    if ($sz >= $fmax) {
	die('max log file size reached where max in bytes = ' . number_format($fmax) . "\n");
    }
    
    
}