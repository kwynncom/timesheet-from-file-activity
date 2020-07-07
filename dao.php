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
    
    public function listener($l) {
	// $dat = fwatch::parseLine($l);
	$dat['l'] = $l;
	$dat['plev'] = 0;
	$dat['datv'] = 1;
	$dat['appv'] = 0;
	$this->acoll->insertOne($dat);

    }
    
    public function p1() {
	$this->acoll->deleteMany(['plev' => 1]);
	$p0r = $this->acoll->find();
	foreach($p0r as $r) {
	    if (!isset($r['l'])) continue;
	    $dat = fwatch::parseLine($r['l']);
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