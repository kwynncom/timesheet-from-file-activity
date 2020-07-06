<?php

die('archive only');

$c3 = "inotifywait -m -r --format %w%f__%T__%e --timefmt %s 2>&1 /tmp/blah & echo $!";
$f = popen($c3,'r');

$i = 0;
while ($o = fgets($f)) {
    if ($i === 0) $pid = intval(trim($o));
    echo $o;
    if ($i++ > 5) break;
}

fclose($f);
if ($pid) posix_kill($pid, SIGTERM);
