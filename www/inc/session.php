<?php

session_start();

if(!isset($_SESSION['timestamp'])) {
  $arr = array('rcode' => 8, 'msg' => 'please login.');
  echo json_encode($arr);
   die();
}

// Session idle time check
if (time() - $_SESSION['timestamp'] > $idletime){
    session_destroy();
    session_unset();
    $arr = array('rcode' => 8, 'msg' => 'Session expired.');
    echo json_encode($arr);
    die();
}else{
    $_SESSION['timestamp'] = time();
}

?>
