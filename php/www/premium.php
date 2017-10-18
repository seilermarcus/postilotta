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

    <button id="signPrem" type="button" class="button" onclick="window.location='signup-premium.php'"><?php echo $ln['b_signup'];?></button>
    <button id="signPrem" type="button" class="buttonfrm" onclick=""><?php echo $ln['learn'];?></button>

  <p><?php echo $ln['free'];?></p>
    <p><?php echo $ln['offer'];?>
      <ul>
        <?php echo $ln['list'];?>
      </ul>
    </p>
    <p>
      <?php echo $ln['money'];?>
      <br><br>

        <?php echo $ln['interest'];?>
        <button id="signPrem" type="button" class="button" onclick="window.location='signup-premium.php'"><?php echo $ln['b_signup'];?></button><br>
      
    </p>
</div>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.getElementById('tn-li-premium').className += " active";
  document.getElementById('mn-li-premium').className += " active";
  checkParaOn();      // Paranoia mode
  checkLang();        // Prepare for multilanguage
</script>
</body>
</html>
