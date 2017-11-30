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
<div id="container" class="container-midi">
  <h1><?php echo $ln['header'];?></h1>
  <div class="txt">
  <b>Uwezo Engineering GmbH</b><br>
  Emmeransstr. 38<br>
  55116 Mainz<br>
  (Germany)<br>
  <br>
  <?php echo $ln['phone'];?>: +49 6131 - 608 66 24<br>
  <?php echo $ln['email'];?>: info@uwezo-engineering.com<br>
  <br>
  <?php echo $ln['register'];?>: HRB 46947<br>
  USt.-Id.-Nr.: DE307695510<br>
  <br>
  <?php echo $ln['executive'];?>:<br>
  Marcus Seiler<br>
  <br>
  </div>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  checkParaOn();      // Paranoia mode
  checkLang();        // Prepare for multilanguage
</script>
</body>
</html>
