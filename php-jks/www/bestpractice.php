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
<div class="txt">
  <p><i>Infographic coming soon.</i></p>
  <p><?php echo $ln['intro'];?></p>
</div>
<h2><?php echo $ln['consider'];?></h2>
<div class="txt">
  <?php echo $ln['list'];?>
  <b><?php echo $ln['thumbrule'];?></b>
</p>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  checkLang();        // Prepare for multilanguage
</script>
</body>
</html>
