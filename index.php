<?php

require_once('/opt/kwynn/kwutils.php');

new fwatch('/tmp/blah');

class fwatch {

    const linelimit = 10; // limit for testing; 0 does mean 0 or don't do anything
    // const linelimit = PHP_INT_MAX;
    
    
public function __construct($paths) {
    $this->paths = $paths;
    self::tests();
    $this->doit();
}

private function tests() {
    strtotimeRecent('2020-06-01');
    strtotimeRecent('2020-01-01');    
    // strtotimeRecent('2019-01-01');   // should fail
    strtotimeRecent('2020-07-06');  
    strtotimeRecent('2020-07-07'); 
    // strtotimeRecent('2020-07-08');  // should fail depending
}

private function process1($l, $cnt) {
    preg_match('/^(\d+)_([^_]+)_(.*)/', $l, $matches); unset($l);
    kwas(isset($matches[3]), 'bad dat 730');
    
    $ts = strtotimeRecent($matches[1]);
    $paths = $this->paths;
    $file = $matches[3];
    $acts = $matches[2];
    $i = $cnt; unset($cnt);
    unset($matches);
    $vars = get_defined_vars();
    $x = 2;
}

private function doit() {
    
    if (self::linelimit <= 0) return;

    $paths = $this->paths;
    $c = "inotifywait -m -r --format %T_%e_%w%f --timefmt %s $paths 2>&1 & echo $!"; unset($paths);
    $f = popen($c,'r');

    $i = 0;
    $headersDone = false; 
    while ($o = fgets($f)) {
	if ($i <= 2 && !$headersDone) { 
	    $this->processHeaders($i++, $o);
	    continue; 
	} else if (!$headersDone) {
	    $headersDone = true;
	    $i = 0;
	}
	
	$i++;
	$this->process1($o, $i);
	
	echo $i . ' ' . $o;
	if ($i >= self::linelimit) break;
    }

    fclose($f);
    posix_kill($this->pid, SIGHUP);
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