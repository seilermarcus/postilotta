<?php
session_start();
include 'inc/settings.inc';

// Session check
//include 'inc/session.php';

$to = $_REQUEST["to"];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT MsgID, Date, State FROM Message Where Recipient='". $to ."' ORDER BY Date DESC";
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $out = json_encode($result);
  }
  catch(PDOException $e)
  {
  $out = $sql . "<br>" . $e->getMessage();
  }

  // Encrypt out before sending back to client
  include 'inc/para_encode.php';

  // Return and clean up
  echo $out;
  $conn = null;
?>
