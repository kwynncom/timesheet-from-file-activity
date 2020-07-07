<?php

require_once('/opt/kwynn/kwutils.php');
require_once('listener.php');
require_once('save.php');

doit();

function doit() {
    
    $path  = '/tmp/blah';
    $dao = new dao_fileact($path);
    $wo = new fwatch($path, [$dao, 'listener']);
    
}
