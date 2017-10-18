<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <script src="general.js"></script>
</head>
<body>
<?php include 'module-head.php';?>

<div id="container" class="container-midi">
  <h1><?php echo $ln['header'];?></h1>
  <div class="txt">
    <?php echo $ln['txt'];?>
  <br>
  </div>
</div>

<div id="container-2" class="container-midi">
    <h2><?php echo $ln['faq'];?></h2>
    <div class="txt">
      <?php echo $ln['faq-txt'];?>
    </div>
</div>

<div id="container-3" class="container-midi">
    <h2><?php echo $ln['issues'];?></h2>
    <div class="txt">
      <?php echo $ln['issues-txt'];?>
    </div>
</div>

<div id="container-4" class="container-midi">
  <h2><?php echo $ln['misuse'];?></h2>
  <div class="txt">
    <?php echo $ln['misuse-txt'];?>
    </div>
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
