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
<h1>postilotta premium</h1>
<div class="txt">
  <button id="signPrem" type="button" class="button" onclick="window.location='signup-premium.php'">SignUp</button>
  <button id="signPrem" type="button" class="butonfrm" onclick="">LearnMore</button>
  <p>postilotta and its basic features are forever free for everyone.</p>
    <p>In addition, we offer <i>postilotta premium</i>, which includes:
      <ul>
        <li>Identity-verified inbox</li>
        <li>Message signing <i>[under construction]</i></li>
        <li>Own sub domains (like tim#apple.postilotta.org) <i>[under construction]</i></li>
        <li>Premium Support</li>
        <li>Send multiple attachments <i>[under construction]</i></li>
        <li>Reply directly with attachment <i>[under construction]</i></li>
        <li>Folders in inbox <i>[under construction]</i></li>
        <li>Message lifetime definition.</li>
      </ul>
    </p>
    <p>
      Starting at <b>1 € per month</b><br>
      Money should not stop you from using <i>postilotta premium</i>.<br>
      So our approach is to agree with you on a monthly fee that fits your financial means and perceived benefit.<br>
      <br>
      Interessted?
      <button id="signPrem" type="button" class="button" onclick="window.location='signup-premium.php'">SignUp</button> <br>
    </p>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.getElementById('tn-li-premium').className += " active";
  document.getElementById('mn-li-premium').className += " active";
  checkParaOn();      // Paranoia mode
</script>
</body>
</html>
