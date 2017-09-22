<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <!-- postilotta core -->
  <script src="general.js"></script>
  <style>
  body {
    /*background-color: grey;*/
    /*color: #A4A4A4;*/
  }
  a {color: #6495ED;}
  div.qrfrm {
    background-color:white;
    width:auto;
    display:inline-block;
    padding:10px;
    display: none;
  }
  </style>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <!-- SJCL -->
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <!--- CryptoJS AES Libraries --->
  <script src="./cryptojs/aes.js"></script>
  <script src="./cryptojs/enc-base64-min.js"></script>
  <!-- QRCode.js -->
  <script src="./qrcode/qrcode.min.js"></script>
  <!-- postilotta core -->
  <script src="general.js"></script>
</head>
<body>
<?php include 'module-head.php'; ?>
<h1 id="p_h2"><?php echo $ln['header'];?></h1>
<div class="txt">

  <p><?php echo $ln['text'];?></p>
  <ol>
    <?php echo $ln['list'];?>
  </ol>
  <?php echo $ln['ifnot'];?></li>
  <p id="out"></p>
  <p id="err" class="err"></p>
  <p id="inf" class="inf"></p>
  <a id='qrcode-href'><div class="qrfrm" id="qrcode"></div></a>
  <form id="theForm">
    <?php echo $ln['pass'];?><br>
    <input type="password" name="p_pf" id="p_pf" size="20">
    <br><br>
    <?php echo $ln['watch'];?><br>
    <input type="password" id="p_ww" size="20">
    <br><br>
    <button type="button" class="button" onclick="prepareParanoia(ipf.value, iww.value)"><?php echo $ln['submit'];?></button>
  </form>
</div>
<br>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.getElementById('tn-li-para').className += ' active';
  document.getElementById('mn-li-para').className += ' active';
  document.getElementById('logoframe').className += ' para';
  document.getElementById('logo').src = 'pics/schwarzerumschlag_p_96.jpg';
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  checkLang();        // Prepare for multilanguage
  var ipf = document.forms["theForm"]["p_pf"];
  var iww = document.forms["theForm"]["p_ww"];
</script>
</body>
</html>
