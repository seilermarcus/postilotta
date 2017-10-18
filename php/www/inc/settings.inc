<?php
// SQL
$username = "root";         //TODO User für App anlegen
$dbname = "postilotta_msgng";
include "sql-ip.inc";
include "sql-pwd.inc";

// Message lifetime
$idletime = 900; // 15 min to session expire
$msgexp = 120; // hours of message lifetime before autodelete

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
?>
