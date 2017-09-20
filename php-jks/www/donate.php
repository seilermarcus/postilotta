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
  <p><?php echo $ln['free'];?></p>
  <p><?php echo $ln['text'];?>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="hosted_button_id" value="9KW3ZCF2H35HE">
      <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
      <img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
    </form>
  </p>
  <p>
    <?php echo $ln['regular'];?>
    <?php echo $ln['better'];?>
  </p>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.getElementById('tn-li-donate').className += " active";
  document.getElementById('mn-li-donate').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  checkLang();        // Prepare for multilanguage
</script>
</body>
</html>
