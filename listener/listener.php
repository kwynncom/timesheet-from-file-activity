<?php

class fwatch {

    // limit for testing; 0 does mean 0 or don't do anything
    const linelimit = 5;
    // const linelimit = 10; 
    // const linelimit = PHP_INT_MAX;
    
    
public function __construct($pathsin = false, $cbf) {
    if ($pathsin) $this->paths = $pathsin;
    else          $this->paths = KWYNN_FTOT_DEFAULT_WATCH_PATHS;
    $this->cbf   = $cbf;
    $this->doit();
}

public static function parseLine($l) {
    preg_match('/^(\d+) (\d+)__([^_]+)_(.*)/', $l, $matches); unset($l);
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
    $c = "inotifywait -m -r --format %T__%e_%w%f --timefmt %s $paths 2>&1 & echo $!"; unset($paths);
    $pf = popen($c,'r');

    $i = 0;
    $headersDone = false; 
    while ($o = fgets($pf)) {
	if ($i <= 2 && !$headersDone) { 
	    $this->processHeaders($i++, $o);
	    continue; 
	} else if (!$headersDone) {
	    $headersDone = true;
	    $i = 0;
	    $this->createFile();
	}
	
	$i++;
	
	fwrite($this->outh, $o);
	
	if ($i >= self::linelimit) break;
    }

    fclose($pf);
    posix_kill($this->pid, SIGHUP);
    fclose($this->outh);
}

private function createFile() {
    $f = tempnam('/tmp/', 'kwynn_inotifywatch_log_'); kwas($f, 'temp file cre failed - 1333');
    $this->outf = $f;
    $h = fopen($f, 'w'); kwas($h, 'fopen failed - 1333');
    $this->outh = $h;
    
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
    
    try {
	if ($i === 1) kwas(strpos($l, 'Setting up watches'  ) !== false, 'invalid line 2 = ' . $l);
	if ($i === 2) kwas(strpos($l, 'Watches established.') !== false, 'invalid line 3 = ' . $l);    
    } catch(Exception $ex) { self::handleINSetupErrors($ex); }
    
}

private static function handleINSetupErrors($exin) {
    $min = $exin->getMessage();
    
    $k1 = 'upper limit on inotify watches reached';
    
    if (strpos($min, $k1) !== false) {
	$mout  = '';
	$mout .= $k1 . "\n";
	
	$mout .= 'a temporary solution is ' . "\n";
	$mout .= 'sudo sysctl fs.inotify.max_user_watches=524288' . "\n";
	$mout .= 'sudo sysctl -p' . "\n";
	$mout .= 'see https://github.com/guard/listen/wiki/Increasing-the-amount-of-inotify-watchers' . "\n";
	$mout .= '(cited 2020/07/07 17:42 UTC)' . "\n";
	$mout .= 'full exception: ' . "\n";
	$mout .= $min;
	die($mout);
    }
    
    throw $exin;
} // func
} // class
