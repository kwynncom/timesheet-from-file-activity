<?php

require_once('/opt/kwynn/kwutils.php');

new fwatch('/tmp/blah');

class fwatch {

    
    
public function __construct($paths) {
    $this->c2($paths);
}

private function c2($paths) {

    $c = "inotifywait -m -r --format %T_%e_%w%f --timefmt %s $paths 2>&1 & echo $!";
    $f = popen($c,'r');

    $i = 0;
    $headersDone = false; 
    while ($o = fgets($f)) {
	if ($i <= 2 && !$headersDone) { 
	    $this->processHeaders($i++, $o);
	    continue; 
	} else if (!$headersDone) {
	    $headersDone = true;
	    $i = 1;
	}
	
	echo $i++ . ' ' . $o;
	if ($i > 10) break;
    }

    fclose($f);
    posix_kill($this->pid, SIGTERM);
}

private function processHeaders($i, $l) {
    if ($i === 0) {
	$pr = trim($l);
	kwas(is_numeric($pr), 'no pid 1');
	$pr = intval($pr);
	kwas($pr > 0, 'invalid pid');
	$this->pid = $pr;
	return;
    }
    if ($i === 1) kwas(strpos($l, 'Setting up watches'  ) !== false, 'invalid line 2');
    if ($i === 2) kwas(strpos($l, 'Watches established.') !== false, 'invalid line 3');    
    
}

}