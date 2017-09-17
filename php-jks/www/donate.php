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
<h1>Donate</h1>
<div class="txt">
  <p>postilotta is free for everyone.</p>
  <p>
    If you like what we do, and even more if you have a benefit from it, consider supporting us by donating.<br>
    Of course without any association to an inbox or send message.
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="hosted_button_id" value="9KW3ZCF2H35HE">
      <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
      <img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
    </form>
  </p>
  <p>
    Especially donations on a regular basis make it much easier to keep the platform up.<br><br>
    <b>Or even better:</b> consider <a href="premium.php"><u>postilotta premium</u></a> as a great way for giving and getting the best out of it.
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
