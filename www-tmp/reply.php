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
<?php include 'module-head.htm'; ?>
<h1>Anonymous Reply</h1>
<div class="txt">
<p id="out"></p>
<p id="inf" class="inf"></p>
<p id="err" class="err"></p>
<p id="fileup"></p>
</div>
<?php include 'module-banner-small.htm'; ?>
<?php include 'module-footer.htm'; ?>
<script>
  checkParaOn();
  var str = window.location.href;
  var lnk = str.slice(str.indexOf("?")+1, str.length);
  loadRpl(lnk);
</script>
</body>
</html>
