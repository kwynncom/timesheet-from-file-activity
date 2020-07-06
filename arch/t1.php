<?php

die('archive only');

$c3 =       "nohup inotifywait -m -r --format %w%f__%T__%e --timefmt %s /tmp/blah 2>&1 > /tmp/b2.txt &";

$pid = pcntl_fork();

if ($pid === 0) {
    pcntl_exec($c3);
    exit(0);
}

$f = fopen('/tmp/b2.txt','r');

$i = 0;
while ($o = fgets($f)) {
    echo $o;
    if ($i++ > 5) break;
}

fclose($f);
posix_kill($pid, SIGTERM);


