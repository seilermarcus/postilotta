<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <script src="./cryptojs/aes.js"></script>
  <script src="./cryptojs/enc-base64-min.js"></script>
  <script src="general.js"></script>
</head>
<body>
<?php include 'module-head.php'; ?>
<div id="container" class="container-login">
<h1 id="p_h2"><?php echo $ln['header'];?></h1>
<div class="txt">
  <p id="inf" class="inf"></p>
  <p id="err" class="err"></p>
  <form id="theForm">
    <div class="capture"><?php echo $ln['cap_inbox'];?></div>
    <img id="adr-typ" src="">
    <div class="tooltip">
      <img id="adr-idv" src=""> <span id='adr-noex'></span>
      <span class="tooltiptext"><?php echo $ln['tt_idv']; ?></span>
    </div>
    <br>
    <input type="text" name="p_to" id="p_to" list="adds" autocomplete="on" size="25" onchange="adrSelect(this);">#postilotta.org
    <datalist id="adds">
    </datalist>
    <br>
    <br>
    <div class="capture"><?php echo $ln['cap_pas'];?></div><br>
    <div class="biginput">
      <input type="password" id="p_pwd" size="20">
    </div>
    <br>
    <button type="button" class="button" onclick="loginSubmit(iname.value, ipwd.value)"><?php echo $ln['submit'];?></button>
  </form>
  <br>
  <a href="#" onclick="alert('Automated process coming soon. For now: please shot us a message.');" align="center"><u><?php echo $ln['pass-forgot'];?></u></a>
</div>
</div>
<div class="bottom">
<br>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
</div>

<script>
  document.forms["theForm"]["p_to"].focus(); // focus first input field
  document.getElementById('tn-li-login').className += " active";
  document.getElementById('mn-li-login').className += " active";
  clearSessionSoft();
  checkParaOn();      // Paranoia mode
//  getToList();        // Populate datalist for to field
  getAdrNameList();   // Prepare name-exist check
  checkLang();        // Prepare for multilanguage
  // Prepare parameters for function call
  var iname = document.forms["theForm"]["p_to"];
  var ipwd = document.forms["theForm"]["p_pwd"];
</script>
</body>
</html>
