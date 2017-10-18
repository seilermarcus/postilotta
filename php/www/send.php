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
<div id="container" class="container-midi">

<h1><?php echo $ln['header'];?></h1>
<div class="txt">
  <form id="theForm">
    <div class="capture"><?php echo $ln['inbox'];?></div> <span id='adr-noex'></span><br>
    <input type="text" name="p_to" id="p_to" list="adds"  autocomplete="on" size="25" onchange="adrSelect(this);">#postilotta.org
    <img id="adr-typ" src="">
    <div class="tooltip">
      <img id="adr-idv" src="">
      <span class="tooltiptext"><?php echo $ln['tt_idv']; ?></span>
    </div>
    <datalist id="adds">
    </datalist>
    <br><br>
    <div class="capture"><?php echo $ln['message'];?></div><br>
    <textarea name="p_text" id="p_text" cols=="45" rows="10" onclick="changedMsgtxt(this);"></textarea>
    <br><br>
    <div class="capture"><?php echo $ln['attach'];?></div> <div id="attReady"></div>
    <input type="file" class="button" id="attach" size="50" onchange="upAttach()" placeholder="max. 5 MB"><br>
    <br>
    <div class="capture"><?php echo $ln['security'];?></div><br>
    <img src="pics/captcha_demo.png" alt="captcha"><br>
    <input type="text" id="p_cap" size="10">
    <br>
    <br>
    <?php echo $ln['hit'];?>
    <ul>
      <?php echo $ln['list'];?>
    </ul>
    <?php echo $ln['sure'];?>
    <p id="ios-info"></p>
    <div style="color:red"><p><?php echo $ln['non-prod'];?></p></div>
    <button type="button" id="send" class="button" onclick="checkSend('anonym', i_to.value, i_c.value)"><?php echo $ln['submit'];?></button>
  </form>
  <br>
  <p id="out"></p>
  <p id="err" class="err"></p>
  <p id="inf" class="inf"></p>
</div>
</div>
<?php include 'module-banner-small.php'; ?>
<div class="foot">
  <p><footer> <?php include 'module-footer.php'; ?> </footer></p>
</div>
<script>
  document.forms["theForm"]["p_to"].focus(); // focus first input field
  document.getElementById('tn-li-send').className += " active";
  document.getElementById('mn-li-send').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  getToList();       // Prepare dropdown and autocompletion
  checkLang();        // Prepare for multilanguage
  // iOS user info
  var os = getOS();
  if (os === 'iOS'){
    document.getElementById('ios-info').style.color = "#0000ff"; //blue
    document.getElementById('ios-info').innerHTML = '&#63743; ATTENTION: On an Apple mobile device, please make sure:<ul>'
                      + '<li>the popup function in your browser settings is enabled</li>'
                      + '<li>you have a storage ready to save the key-file to (e.g. iCloud Drive, Dropbox, GoogleDrive...)</li></ul>'
                      + 'When the key-file download opens up in a new tap, store it as a file in the location of your choice and close the tap afterwards.';

  }
  var i_to = document.forms["theForm"]["p_to"];
  var i_c = document.forms["theForm"]["p_text"];
</script>
</body>
</html>
