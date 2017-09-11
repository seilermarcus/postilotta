<?php

include 'inc/settings.inc';

$id = $_REQUEST["id"];
$adr = $_REQUEST["adr"];
$pub = $_REQUEST["pub"];
$pw = $_REQUEST["pw"];
$eml = $_REQUEST["eml"];
$vis = $_REQUEST["vis"];
$typ = $_REQUEST["typ"];
$pay = $_REQUEST["pay"];
$prc = $_REQUEST["prc"];
$unt = $_REQUEST["unt"];

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("INSERT INTO Inbox (BoxID, Address, PubKey, Password, Email, Visible, Type, Payment, Price, PaidUntil)
  VALUES (". $id .", '". $adr ."', '". $pub ."', '". $pw ."', '". $eml ."', '". $vis ."', '". $typ ."', '". $pay ."', '". $prc ."', '". $unt ."')");
  $stmt->execute();

  $arr = array('rcode' => 0, 'msg' => 'New postilotta-inbox successfully created.', 'pay' => $pay);
  $out = json_encode($arr);
}
catch(PDOException $e) {
  $mtext = $sql . " - " . $e->getMessage();
  $arr = array('rcode' => 1, 'msg' => $mtext);
  $out = json_encode($arr);
}

// Encrypt out before sending back to client
include 'inc/para_encode.php';

// Return and clean up
echo $out;
$conn = null;
?>
