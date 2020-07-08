<?php

require_once('/opt/kwynn/kwutils.php');
require_once('listener.php');
require_once(__DIR__ . '/../dao.php');

function setListener() {
    
    $path  = '/home/' . get_current_user() . '/';
    $dao = new dao_fileact($path);
    $wo = new fwatch($path, [$dao, 'listener']);
    
}

if (didCLICallMe(__FILE__)) setListener();