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
<h1>Send an anonymous message to a postilotta inbox</h1>
<div class="txt">
  <form id="theForm">
    <div class="capture">Inbox:</div><br>
    <input type="text" name="p_to" id="p_to" list="adds"  autocomplete="on" size="20" onchange="adrSelect(this);">#postilotta.org <img id="adr-typ" src="">
    <datalist id="adds">
    </datalist>
    <br><br>
    <div class="capture">Message:</div><br>
    <textarea name="p_text" id="p_text" cols=="45" rows="10"></textarea>
    <br><br>
    <div class="capture">Attachment:</div> <div id="attReady"></div>
    <input type="file" class="button" id="attach" size="50" onchange="upAttach()"><br>
    <br>
    <div class="capture">Security Check:</div><br>
    <img src="pics/captcha_demo.png" alt="captcha"><br>
    <input type="text" id="p_cap" size="10">
    <br>
    <br>
    When you hit the submit button, the following things will happen:
    <ul>
      <li>The message will be encrypted immediately and transferred as cipher data to the recipients inbox. His/her key will be the only way to decrypt it.</li>
      <li>A key-file will be generated for you, which will be the only way to decrypt the response, if the recipient replies to your message.</li>
      <li>A link (URL) will be generated and displayed, which will be the location a response can be accessed, if - you guessed it - the recipient replies to your message.</li>
      <li>Both messages will expire after 120 hours and then deleted automatically without any recovery option.</li>
    </ul>
    Make sure you keep key-file and link safe and secure.
    <p id="ios-info"></p>
    <div style="color:red"><p>You noticed that this service is not yet in productive use, right?</p></div>
    <button type="button" id="send" class="button" onclick="prepReply(i_to.value, i_c.value)">Submit</button>
  </form>
  <br>
  <p id="out"></p>
  <p id="err" class="err"></p>
  <p id="inf" class="inf"></p>
</div>
<?php include 'module-banner-small.htm'; ?>
<div class="foot">
  <p><footer> <?php include 'module-footer.htm'; ?> </footer></p>
</div>
<script>
  document.getElementById('tn-li-send').className += " active";
  document.getElementById('mn-li-send').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  getToList();
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
