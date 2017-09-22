<?php
session_start();
include 'inc/settings.inc';
include './inc/language-prep.php'; //multilanguage ready

$pf = $_REQUEST["pf"];
$ww = $_REQUEST["ww"];

//Generate $plink TODO
$itg = rand(10000, 99999) * mt_rand(10000, 99999);
$plnk = hash('sha256' , strval(date(U)) . strval($itg));

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO Paranoia (PLink, Passphrase, Watchword, Expire) VALUES ('". $plnk ."', '". $pf ."', '". $ww ."', DATE_ADD(NOW(), INTERVAL 48 HOUR))";
    $stmt = $conn->query($sql);
//    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $arr = array('rcode' => 0, 'msg' => $ln['success'], 'plink' => $plnk);
    echo json_encode($arr);
  }
  catch(PDOException $e)
  {
    $mtext = $sql . " - " . $e->getMessage();
    $arr = array('rcode' => 1, 'msg' => $mtext);
    echo json_encode($arr);
  }
  $conn = null;

?>
