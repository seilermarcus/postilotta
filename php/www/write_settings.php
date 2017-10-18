<?php
session_start();
include 'inc/settings.inc';
include 'inc/session.php'; // Session check
include './inc/language-prep.php'; //multilanguage ready

$adr = $_REQUEST["adr"];
$mail = $_REQUEST["mail"];
$vis = $_REQUEST["vis"];
$pay = $_REQUEST["pay"];
$price = $_REQUEST["price"];
$pw = $_REQUEST["pw"];
$mlf = isset($_REQUEST["mlf"]) ? $_REQUEST["mlf"] : $msgexp;

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE Inbox SET Email='". $mail ."', Visible='". $vis ."', MsgLife=". $mlf .", Payment='". $pay ."', Price=". $price .", Password='".$pw ."' WHERE ADDRESS='". $adr ."'";
  // use exec() because no results are returned
  $conn->exec($sql);
  $arr = array('rcode' => 0, 'msg' => $ln['success']);
  $out = json_encode($arr);

}catch(PDOException $e){
  $mtext = $sql . " - " . $e->getMessage();
  $arr = array('rcode' => 1, 'msg' => $mtext, 'lnk' => 0);
  $out = json_encode($arr);
}

// Encrypt out before sending to client
include 'inc/para_encode.php';

// Return and clean up
echo $out;
$conn = null;
?>
