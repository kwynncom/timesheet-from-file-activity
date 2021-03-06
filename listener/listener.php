<?php

require_once(__DIR__ . '/../config.php');
require_once('reg1.php');

class fwatch {
    
public function __construct() {

    $this->paths     = ftotConfig::getPaths();
    $this->linelimit = ftotConfig::getMaxLines();
    $this->doit();
}

public static function parseLine($lin) {
    
    static $base = false;
    
    if (!$base) $base = ftotConfig::getPaths();
    
    $l = str_replace($base, '/', $lin);
        
    preg_match('/^(\d+)__([^\/]+)(\/.*)/', $l, $matches); unset($l);
    kwas(isset($matches[3]), 'bad dat 730');
    
    $ts = strtotimeRecent($matches[1]);
    $file = $matches[3];
    $acts = $matches[2];
    unset($matches);
    $vars = get_defined_vars();
    return $vars;
}

private function getCommand() {
    $c  = '';
    $c .= 'inotifywait -m ';
    if (ftotConfig::doRecursive()) $c .= '-r ';
    $paths = $this->paths;
    // kwas(file_exists($paths)), 'path'
    $c .= "--format %T__%e%w%f --timefmt %s $paths 2>&1 & echo $!";
    return $c;
}


private function doit() {
    
    $c = $this->getCommand(); unset($paths);
    $pf = popen($c,'r'); unset($c);
    $this->pf = $pf;

    $i = 0;
    $headersDone = false; 
    while ($o = fgets($pf)) {
	if ($i <= 2 && !$headersDone) { 
	    $this->processHeaders($i++, $o);
	    if ($i === 3 && !$headersDone) {
		$headersDone = true;
		$i = 0;
		$this->createFile();
		continue;
	    } else continue; 
	}

	if ($i >= $this->linelimit) break;	
	$i++;
	
	logreg1($i, $o);
	fwrite($this->outh, $o);
    }

    return; 
}

public function __destruct() {
    
    file_put_contents('/tmp/des', 'destructor ran ' . date('r') . "\n", FILE_APPEND);
    
    fclose($this->pf);
    posix_kill($this->pid, SIGHUP);
    
    if (!isset($this->outh)) return;
    flock ($this->outh, LOCK_UN);
    fclose($this->outh);
}

private function createFile() {
    $f = ftotConfig::getInotLogFile();
    $h = fopen($f, 'w'); kwas($h, 'fopen failed - 1333');
    $cmr = chmod($f, 0600); kwas($cmr, 'chmod failed - 1401');
    $lr = flock($h, LOCK_EX); kwas($lr, 'lock failed - 1447');
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
