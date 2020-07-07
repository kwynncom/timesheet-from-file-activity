<?php

require_once('/opt/kwynn/kwutils.php');
require_once('listener.php');
require_once('./../dao.php');

doit();

function doit() {
    
    $path  = '/home/' . get_current_user() . '/';
    $dao = new dao_fileact($path);
    $wo = new fwatch($path, [$dao, 'listener']);
    
}
