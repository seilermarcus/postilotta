<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <!-- SJCL -->
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <!--- CryptoJS AES Libraries --->
  <script src="./cryptojs/aes.js"></script>
  <script src="./cryptojs/enc-base64-min.js"></script>
  <!-- postilotta core -->
  <script src="general.js"></script>
</head>
<body>
<?php include 'module-head.php'; ?>

<div id="container" class="container-transp">
  <h1 id="p_h1" style="display:inline"></h1><img id="typ" src=""><img id="idv" src="">
</div>

<div id="container" class="container">
<div class="txt">
  <div id="err" class="err"></div>
  <p id="inf" class="inf"><?php echo $ln['hello'];?></p>
  <p id="out"></p>
  <p id="fileup"></p>
</div>
</div>
<?php include 'module-toolbar.php'; ?>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
  <script>
  if (sessionStorage.p_adr == null){
   location.replace('login.php');
  } else {
    document.getElementById('tn-li-login').className += " active";
    document.getElementById('mn-li-login').className += " active";
    getInboxData();
    checkParaOn();      // ExtraSecure color scheme and encryption
    checkLang();        // Prepare for multilanguage

    // Header
    document.getElementById("p_h1").innerHTML += sessionStorage.p_adr + '#postilotta.org';
  }
  </script>
</body>
</html>
