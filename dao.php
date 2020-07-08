<?php

require_once('/opt/kwynn/kwutils.php');
require_once('listener/listener.php');
require_once('utils.php');

class dao_fileact extends dao_generic {
    const db = 'fileact';
    function __construct() {
	
        parent::__construct(self::db);
        $this->acoll = $this->client->selectCollection(self::db, 'acts');

    }
    
    public function listener($l, $i) {
	// $dat = fwatch::parseLine($l);
	$dat['i'] = $i;
	$dat['l'] = $l;
	$dat['plev'] = 0;
	$dat['datv'] = 1;
	$dat['appv'] = 0;
	$this->acoll->insertOne($dat);

    }
    
    public function rmlev($l) {
	$this->acoll->deleteMany(['plev' => $l]);	
    }
    
    
    public function p1() {
	$this->rmlev(1);
	$p0r = $this->acoll->find();
	foreach($p0r as $r) {
	    if (!isset($r['l'])) continue;
	    try { 
		$dat = fwatch::parseLine($r['l']);
	    } catch(Exception $ex)  { 
		if (isset($r['i']) && $r['i'] === 1) continue;
		throw $ex;
		    
	    } 
	    $dat['r'] = date('r', $dat['ts']);
	    $dat['plev'] = 1;
	    $dat['appv'] = 0;
	    $dat['datv'] = 1;
	    $dat['oid' ] = $r['_id'];
	    $dat['path'] = ftt::pathToArray($dat['file']);
	    $this->acoll->insertOne($dat);
	    // $this->acoll->deleteOne(['_id' => $r['_id']]);
	}
    }
}