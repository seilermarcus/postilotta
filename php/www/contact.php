<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <!-- postilotta core -->
  <script src="general.js"></script>
</head>
<body>
<?php include 'module-head.php'; ?>
<h1><?php echo $ln['header'];?></h1>
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
Email: info@uwezo-engineering.com<br>
<br>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.getElementById('tn-li-contact').className += " active";
  document.getElementById('mn-li-contact').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  checkLang();        // Prepare for multilanguage
</script>
</body>
</html>
