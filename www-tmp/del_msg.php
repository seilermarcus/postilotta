<?php
include 'inc/settings.inc';
include 'inc/session.php'; // Session check

$mid = $_REQUEST["mid"];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("DELETE FROM Message WHERE MsgID='". $mid ."'");
    $ok = $stmt->execute();

    if ($ok){
      $arr = array('rcode' => 0, 'msg' => 'ok');
    }else{
      $arr = array('rcode' => 4, 'msg' => 'nok');
    }
    $out = json_encode($arr);

} catch(PDOException $e) {
    $mtext = $e->getMessage();
    $arr = array('rcode' => 1, 'msg' => $mtext);
    $out = json_encode($arr);
}

// Encrypt out before sending back to client
include 'inc/para_encode.php';

// Return and clean up
echo $out;
$conn = null;
?>
