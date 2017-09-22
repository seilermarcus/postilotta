<?php
session_start();
include 'inc/settings.inc';

$lnk = $_REQUEST["lnk"];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT Date, Content FROM Message Where Recipient='". $lnk ."'");
    $stmt->execute();

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
