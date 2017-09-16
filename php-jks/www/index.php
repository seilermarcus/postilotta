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
  <?php include 'module-head.htm'; ?>
<h1><?php echo $ln['tagline']; ?></h1>
<h2><?php echo $ln['header']; ?></h2>
<div class="txt">
  <p><?php echo $ln['text']; ?></p>
</div>
<?php include 'module-banner.htm'; ?>
<?php include 'module-footer.htm'; ?>
<script>
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  checkLang();
</script>
</body>
</html>
