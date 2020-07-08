<?php

require_once('config.php');
require_once('dao.php');
require_once('listener/listen.php');

ftot_process::pf1();

class ftot_process {

public static function pf1() {
    
    // setListener();

    $dao = new dao_fileact();

    
    if (0) {
	
	$f = ftotConfig::getInotLogFile();
	$h = fopen($f, 'r');
	
	$i = 0;
	$dao->rmlev(0);
	while ($l = fgets($h)) {
	    $dao->listener($l, ++$i);	
	} unset($i);
    }
    
    // $dao->p1();
    $dao->p2();

}



}