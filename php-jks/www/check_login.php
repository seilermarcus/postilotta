<?php
include 'inc/settings.inc';

$adr = $_REQUEST["adr"];
$pw = $_REQUEST["pw"];


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM Inbox WHERE Address='". $adr ."' AND Password='". $pw ."'";
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (sizeof($result)==1 ){
      $arr = array('rcode' => 0, 'msg' => 'sizeof(result): ' . sizeof($result), 'typ' => $result[0]['Type']);

      // initialize session
      session_start();
      $_SESSION['timestamp'] = time();

    }else{
      $arr = array('rcode' => 4, 'msg' => 'sizeof(result): ' . sizeof($result) . $sql);
    }
    $out = json_encode($arr);
} catch(PDOException $e) {
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
