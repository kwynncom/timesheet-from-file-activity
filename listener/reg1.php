<?php

function logreg1($i, $l) {
    static $sz = 0;
    static $fmax = false;
    
    if ($fmax === false) {
	 $cmax = KWYNN_FTOT_MAX_FILE_GB;
	 kwas(is_numeric($cmax), 'ftot max not numeric 1416');
	 $mby = 1 << 30;
	 $fmax = intval(round($cmax * $mby)); unset($cmax, $mby);
	 kwas(is_integer($fmax), 'file max is not integer 1414');
    }
    
    $sz .= strlen($l);
    
    
    
}