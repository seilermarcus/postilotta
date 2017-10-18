<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
</head>
<body>
  <?php include 'module-head.php';?>
  <div id="container" class="container">
    <h1><?php echo $ln['tagline'];?>  <img id='logo' src='pics/mdi_logo_30.png' alt='made in germany'></h1>
    <h2><?php echo $ln['header'];?></h2>
    <div class="txt">
      <p><?php echo $ln['text'];?></p>
    </div>
  </div>
  <?php include 'module-banner.php';?>
  <?php include 'module-footer.php';?>
  <script src="general.js"></script>
  <script>
    clearSessionSoft(); // paranoia vars excluded
    checkLang();        // Prepare for multilanguage
    checkParaOn();      // Paranoia mode
  </script>
</body>
</html>
