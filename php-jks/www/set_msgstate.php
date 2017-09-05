<?php
include 'inc/settings.inc';

// Session check
include 'inc/session.php';

$mid = $_REQUEST["mid"];
$state = $_REQUEST["state"];

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE Message SET State = '". $state ."' WHERE MsgID = ". $mid;
  // use exec() because no results are returned
  $conn->exec($sql);

  $arr = array('rcode' => 0, 'msg' => 'State updated to:'. $state);
  $out = json_encode($arr);
  //echo "Message successfully droped with message id: " . $id;

} catch(PDOException $e) {
  $mtext = $sql . " - " . $e->getMessage();
  $arr = array('rcode' => 1, 'msg' => $mtext, 'lnk' => 0);
  $out = json_encode($arr);
}
// Encrypt out before sending back to client
include 'inc/para_encode.php';
// Return and clean up
echo $out;
$conn = null;
?>
