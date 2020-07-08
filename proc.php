<?php

require_once('config.php');
require_once('dao.php');

ftot_process::pf1();

class ftot_process {

public static function pf1() {

    $dao = new dao_fileact();
    $f = ftotConfig::getInotLogFile();
    $h = fopen($f, 'r');
    
    if (1) {
	$i = 0;
	$dao->rmlev(0);
	while ($l = fgets($h)) {
	    $dao->listener($l, ++$i);	
	} unset($i);
    }
    
    $dao->p1();
    
    

}



}