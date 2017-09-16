<?php session_start(); ?>
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
  <?php include './inc/language-prep.php'; ?>
</head>
<body>
<?php include 'module-head.htm'; ?>
<p><h1 id="p_h1" style="display:inline">Inbox: </h1><img id="typ" src=""> <img id="idv" src=""></p>
<div class="txt">
  <p id="err" class="err"></p>
  <p id="inf" class="inf">Welcome</p>
  <p id="out"></p>
  <p id="fileup"></p>
</div>
<?php include 'module-toolbar.htm'; ?>
<?php include 'module-banner-small.htm'; ?>
<?php include 'module-footer.htm'; ?>
  <script>
  if (sessionStorage.p_adr == null){
   location.replace('login.php');
  } else {
    document.getElementById('tn-li-login').className += " active";
    document.getElementById('mn-li-login').className += " active";
    getInboxData();
    checkParaOn(); // color scheme and encryption

    // Header
    document.getElementById("p_h1").innerHTML += sessionStorage.p_adr + '#postilotta.org';
  }
  </script>
</body>
</html>
