<?php
session_start();
include 'inc/settings.inc';
include './inc/language-prep.php'; //multilanguage ready

$id = $_REQUEST["id"];
$to = $_REQUEST["to"];
$c = $_REQUEST["c"];
$pub = $_REQUEST["pub"];
$link = $_REQUEST["link"];
$exp = $_REQUEST["exp"] ? $_REQUEST["exp"] : $msgexp;

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Write message
  $sql = "INSERT INTO Message (MsgId, Recipient, Content, ReturnPubKey, ReturnLink, Expire)
  VALUES (". $id .", '". $to ."', '". $c ."', '". $pub ."', '". $link ."', DATE_ADD(NOW(), INTERVAL ". $exp ." HOUR) )";
  // use exec() because no results are returned
  $conn->exec($sql);
  $arr = array('rcode' => 0, 'msg' => $ln['success'], 'lnktxt' => $ln['lnktxt'], 'lnk' => $link);
  $out = json_encode($arr);

  // Get email address for notification
  $sql = "SELECT Email FROM Inbox Where Address='". $to ."'";
  $stmt = $conn->query($sql);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Send notification email
  $smtp_to = $result[0]["Email"];
  if ($smtp_to != "") {
    require_once "Mail.php";
    $subject = $ln['e_sub'];
    $body = $ln['e_body'] . $to . "#postilotta.org" . $ln['e_bye'];
    $headers = array ('From' => $smtp_from,
      'To' => $smtp_to,
      'Subject' => $subject);
    $smtp = Mail::factory('smtp',
      array ('host' => $smtp_host,
        'auth' => true,
        'username' => $smtp_user,
        'password' => $smtp_password));

    $mail = $smtp->send($smtp_to, $headers, $body);

    if (PEAR::isError($mail)) {
      //echo("<p> email sending failed: " . $mail->getMessage() . "</p>");
    } else {
    //  echo("<p>Message successfully sent!</p>");
   }
 }

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
