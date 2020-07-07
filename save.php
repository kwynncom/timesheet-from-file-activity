<?php

class dao_fileact extends dao_generic {
    const db = 'fileact';
    function __construct($paths) {
	$this->paths = $paths;
	
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
	
	return;
    }
}