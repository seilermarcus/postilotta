<?php

include 'inc/settings.inc';

$id = $_REQUEST["id"];
$to = $_REQUEST["to"];
$c = $_REQUEST["c"];
$pub = $_REQUEST["pub"];
$link = $_REQUEST["link"];
$exp = isset($_REQUEST["exp"]) ? $_REQUEST["exp"] : $msgexp;

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Message (MsgId, Recipient, Content, ReturnPubKey, ReturnLink, Expire)
  VALUES (". $id .", '". $to ."', '". $c ."', '". $pub ."', '". $link ."', DATE_ADD(NOW(), INTERVAL ". $exp ." HOUR) )";
  // use exec() because no results are returned
  $conn->exec($sql);
  $arr = array('rcode' => 0, 'msg' => 'Message successfully sent.', 'lnk' => $link);
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
