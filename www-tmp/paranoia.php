<?php session_start(); ?>
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
<?php include 'module-head.htm'; ?>
<h1 id="p_h2">ExtraSecure Mode</h1>
<div class="txt">

  <p>Using postilotta and a non-compromised device already makes your messages secure as hell.<br>
     However, if you have very sensitive content to transmit and the suspicion to be at special risk (e.g. man-in-the-middle is already watching):
     Let's add an extra independent AES encryption layer (used by governments for top secret purpose).<br>
     <br>
     We recommend doing this as follows:</p>
  <ol>
    <li>Type in a passphrase and watchword below.</li>
    <li>Get the generated backdoor-link (e.g. via QR-Code).</li>
    <li>Use another device at another access point and visit the backdoor-link within 48h.</li>
    <li>Type in your passphrase and if the site can respond with the watchword, the additional security layer has been established.</li>
  </ol>
  If the site does not answer correctly, something not-trustworthy had happened and you should not proceed and start over the ExtraSecure activation process.</li>
  <p id="out"></p>
  <p id="err" class="err"></p>
  <p id="inf" class="inf"></p>
  <a id='qrcode-href'><div class="qrfrm" id="qrcode"></div></a>
  <form id="theForm">
    Passphrase:<br>
    <input type="password" name="p_pf" id="p_pf" size="20">
    <br><br>
    Watchword:<br>
    <input type="password" id="p_ww" size="20">
    <br><br>
    <button type="button" class="button" onclick="prepareParanoia(ipf.value, iww.value)">Submit</button>
  </form>
</div>
<?php include 'module-banner-small.htm'; ?>
<?php include 'module-footer.htm'; ?>
<script>
  document.getElementById('tn-li-para').className += ' active';
  document.getElementById('mn-li-para').className += ' active';
  document.getElementById('logoframe').className += ' para';
  document.getElementById('logo').src = 'pics/schwarzerumschlag_p_96.jpg';
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  var ipf = document.forms["theForm"]["p_pf"];
  var iww = document.forms["theForm"]["p_ww"];
</script>
</body>
</html>
