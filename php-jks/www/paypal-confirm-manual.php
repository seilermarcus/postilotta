<?php

include 'inc/settings.inc';

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
// post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE Inbox SET Inof='PHP'";
  $conn->exec($sql);
}catch(PDOException $e){}

/*
$fp = fsockopen ('ssl://ipnpb.sandbox.paypal.com', 443, $errno, $errstr, 30);


if (!$fp) {
// HTTP ERROR
} else {
  fputs ($fp, $header . $req);
  while (!feof($fp)) {
      $res = fgets ($fp, 1024);
      if (strcmp ($res, "VERIFIED") == 0) {

        // PAYMENT VALIDATED & VERIFIED!

      }

      else if (strcmp ($res, "INVALID") == 0) {

        // PAYMENT INVALID & INVESTIGATE MANUALY!

      }
    }
    fclose ($fp);
  }
*/

?>
