<?php
include 'inc/settings.inc';

// Session check
//include 'inc/session.php';

$mid = $_REQUEST["mid"];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT Recipient, Date, State, Content, ReturnPubKey, ReturnLink FROM Message Where MsgID='". $mid ."'");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = json_encode($result);
  }
  catch(PDOException $e)
  {
  $out = $sql . "<br>" . $e->getMessage(); //TODO check if that works, or must it be json...
  }

  // Encrypt out before sending back to client
  include 'inc/para_encode.php';

  // Return and clean up
  echo $out;
  $conn = null;
?>
