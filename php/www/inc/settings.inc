<?php
// SQL
$username = "root";
$dbname = "postilotta_msgng";
include "sql-ip.inc";
include "sql-pwd.inc";
// Message lifetime
$idletime = 900; // 15 min to session expire
$msgexp = 120; // hours of message lifetime before autodelete

// productive environment
if ($_SERVER[SERVER_NAME]=='postilotta.org'){
    // general
    $prod = TRUE;
    $logo = "pics/postilottatext_small_beta.jpg";
    // Email
    $smtp_from = "postilotta <mail@postilotta.org>";
    $smtp_host = "mail.postilotta.org";
    $smtp_user = "mail@postilotta.org";
    include "smtp-pwd.inc";
    //PayPal
    $pal_url = "\"https://www.paypal.com/cgi-bin/webscr\"";
    $pal_business = "\"marcus.seiler@uwezo-engineering.com\"";
    $pal_return = "\"https://postilotta.org/login.php\"";
    $pal_notify = "\"https://postilotta.org/paypal-confirm.php\"";
}
// test environments
else {
    // general
    $prod = FALSE;
    $logo = "pics/postilottatext_small_proto.jpg";
    // Email
    $smtp_from = "postilotta <mail@youwezo.org>";
    $smtp_host = "mail.youwezo.org";
    $smtp_user = "mail@youwezo.org";
    include "smtp-pwd.inc";
    //PayPal
    $pal_url = "\"https://www.sandbox.paypal.com/cgi-bin/webscr\"";
    $pal_business = "\"marcus.seiler-facilitator@uwezo-engineering.com\"";
    $pal_return = "\"https://prototype.postilotta.com/login.php\"";
    $pal_notify = "\"https://prototype.postilotta.com/paypal-confirm.php\"";
}
?>
