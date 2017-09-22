<?php
include 'inc/crypto-lib.inc';

//$paralnk = $_REQUEST["paralnk"];

session_start();

if( isset($_SESSION['paranoiaPWD']) ) {
  $pas = $_SESSION['paranoiaPWD'];
  $out = cryptoJsAesEncrypt($pas, $out);
}
//$out = 'pPWD: ' . $_SESSION['paranoiaPWD'] . 'pLINK: ' . $_SESSION['paranoiaLink'];

/*
// only if para-mode is activated in php session and client requests para-mode
if( (isset($_SESSION['paranoiaPWD'])) && ($paralnk != 'undefined') ) {
  $pas = $_SESSION['paranoiaPWD'];
  if ($_SESSION['paranoiaLink'] == $paralnk){
    $out = cryptoJsAesEncrypt($pas, $out);
  }
}
*/
?>
