<?php

include 'settings.inc';

$sid = $_REQUEST["sid"];

if ( $sid == 0 ){
  // generate new session-ID
  $newID = uniqid(string $prefix = "", $more_entropy = true);

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("INSERT INTO Inbox (BoxID, Address, PubKey, Password)
    VALUES (". $id .", '". $adr ."', '". $pub ."', '". $pw ."')");
    $stmt->execute();

    $arr = array('rcode' => 0, 'msg' => 'New postilotta-inbox successfully created.');
    echo json_encode($arr);
  }
  catch(PDOException $e)
  {
    $mtext = $sql . " - " . $e->getMessage();
    $arr = array('rcode' => 1, 'msg' => $mtext);
    echo json_encode($arr);
  }
  $conn = null;
}
?>
