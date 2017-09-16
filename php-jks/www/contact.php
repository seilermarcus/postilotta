<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <!-- postilotta core -->
  <script src="general.js"></script>
  <?php include './inc/language-prep.php'; ?>
</head>
<body>
<?php include 'module-head.php'; ?>
<h1>Contact</h1>
<hr>
<h2>Report Misuse</h2>
<div class="txt">
If you know, or even just suspect, that a inbox is misused in whatever way, please contact us immediately.<br><br>
Inbox: <a href="send.php?p_to=misuse"><u>misuse#postilotta.com</u></a><br>
</div>
<hr>
<h2>FAQ</h2>
<div class="txt">
  <i>Coming soon.</i>
</div>
<hr>
<h2>Technical Issues</h2>
<div class="txt">
  <i>Coming soon.</i> Using GitHup Issue Tool.
</div>
<hr>
<h2>Contact</h2>
<div class="txt">
email: info@postilotta.com<br>
inbox: info#postilotta.com<br>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.getElementById('tn-li-contact').className += " active";
  document.getElementById('mn-li-contact').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
</script>
</body>
</html>
