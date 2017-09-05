<?php
session_start();
include 'inc/settings.inc';

// Session check
if(!isset($_SESSION['timestamp'])) {
  echo 'name not set';
   die();
}

// Session idle time check
if (time() - $_SESSION['timestamp'] > $idletime){
    session_destroy();
    session_unset();
    echo 'timeout';
    die();
}else{
    $_SESSION['timestamp'] = time();
    echo 'all clear';
}
?>
