<?php
include 'inc/settings.inc';
include 'inc/session.php'; // Session check

$adr = $_REQUEST["adr"];

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE Inbox SET IdVerified=1 WHERE Address='". $adr ."'";

  $conn->exec($sql);
  $arr = array('rcode' => 0, 'msg' => 'Verification complete.');
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
