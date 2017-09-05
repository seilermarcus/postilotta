<?php
include 'inc/settings.inc';
include 'inc/session.php'; // Session check

$adr = $_REQUEST["adr"];
$pw = $_REQUEST["pw"];
$ok = 0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM Inbox WHERE Address='". $adr ."' AND Password='". $pw ."'";
    $ok = $conn->exec($sql);

    if ( $ok === 1 ){
      $sql = "DELETE FROM Message WHERE Recipient='". $adr ."'";
      $conn->exec($sql);
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
