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
  </ul>
<h1 id="p_h2"><?php echo $ln['header'];?></h1>
<div class="txt">
  <p id="out"></p>
  <p id="inf" class="inf"></p>
  <p id="err" class="err"></p>
  <form id="theForm">
    <div class="capture"><?php echo $ln['cap_inbox'];?></div><br>
    <input type="text" name="p_to" id="p_to" list="adds" autocomplete="on" size="20" onchange="adrSelect(this);">#postilotta.org
    <img id="adr-typ" src="">
    <div class="tooltip">
      <img id="adr-idv" src="">
      <span class="tooltiptext"><?php echo $ln['tt_idv']; ?></span>
    </div>
    <datalist id="adds">
    </datalist>
    <br>
    <br>
    <div class="capture"><?php echo $ln['cap_pas'];?></div><br>
    <input type="password" id="p_pwd" size="20">
    <br><br>
    <button type="button" class="button" onclick="loginSubmit(iname.value, ipwd.value)"><?php echo $ln['submit'];?></button>
  </form>
</div>

<div class="bottom">
<br>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
</div>

<script>
  document.getElementById('tn-li-login').className += " active";
  document.getElementById('mn-li-login').className += " active";
  clearSessionSoft();
  checkParaOn();     // Paranoia mode
  // Populate datalist for to field
  getToList();
  checkLang();        // Prepare for multilanguage
  // Prepare parameters for function call
  var iname = document.forms["theForm"]["p_to"];
  var ipwd = document.forms["theForm"]["p_pwd"];
</script>
</body>
</html>
