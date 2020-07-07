<?php

class fwatch {

    const linelimit = 10; // limit for testing; 0 does mean 0 or don't do anything
    // const linelimit = PHP_INT_MAX;
    
    
public function __construct($paths, $cbf) {
    $this->paths = $paths;
    $this->cbf   = $cbf;
    $this->doit();
}

public static function parseLine($l) {
    preg_match('/^(\d+) (\d+)_([^_]+)_(.*)/', $l, $matches); unset($l);
    kwas(isset($matches[4]), 'bad dat 730');
    
    $ts = strtotimeRecent($matches[2]);
    $file = $matches[4];
    $acts = $matches[3];
    $i = intval($matches[1]);
    kwas($i && $i > 0, 'bad index parseLine watcher');
    unset($matches);
    $vars = get_defined_vars();
    return $vars;
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
	
	$l = $i . ' ' . $o;
	
	($this->cbf)($l);
	
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
