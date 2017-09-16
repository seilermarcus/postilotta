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
<?php include 'module-head.php'; ?>
<h1>Get your own postilotta inbox</h1>
<div class="txt">
  <form id="theForm">
    <div class="capture">Desired Inbox Address:</div><br>
    <input type="text" id="p_name" size="30">#postilotta.org
    <br><br>
    <div class="capture">Login Password:</div><br>
    <input type="password" id="p_pwd" size="20">
    <br><br>
    <div class="capture">Confirm Password:</div><br>
    <input type="password" id="p_pwd2" size="20" onchange='checkPWDConf();'>  <span id='notConf'></span>
    <br><br>
    <div class="capture">E-Mail</div> (optional, if you want to get notified about new messages):<br>
    <input type="email" id="p_mail" size="40">
    <br><br>
    <div>
      <input type="checkbox" id="p_visible" name="visible" value="1" checked>
      <label for="visible"><div class="capture">Visible</div> (e.g. in dropdown list and autocompletion on send form. You can change it anytime after login.)</label>
    </div>
    <br>
    <div>
      <input type="checkbox" id="p_sandbox" name="sandbox" value="1">
      <label for="visible"><div class="capture">Test Inbox</div> (for rehearsal and dry run, auto-deleted after 48h)</label>
    </div>
    <br>
    <div>
      <input type="checkbox" id="p_agb" name="agb" value="1">
      <label for="agb">I agree with postilotta's <u><a href="terms.php">Terms</a></u> and <u><a href="privacy.php">Privacy Policy</a></u></label>
    </div>
    <br><br>
    When you hit the submit button (and the name isn't already taken), the following things will happen:
    <ul>
      <li>Your new postilotta inbox will be created and immediately available for incoming messages.</li>
      <li>A key file will be generated for you, which will be the only way to decrypt messages send to your inbox.</li>
    </ul>
    So make sure you keep your password and key-file safe and secure.
    <br>
    <p id="ios-info"></p>
    <div style="color:red"><p>You noticed that this service is not yet in productive use, right?</p></div>
    <button type="button" class="button" onclick="signSubmit(iname.value, ipwd.value, ipmail.value, ivisi.checked, iagb.checked, 0, 0, 'basic', isand.checked)">Submit</button>
  </form>
  <p id="out"></p>
  <p id="inf" class="inf"></p>
  <p id="err" class="err"></p><br>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.getElementById('tn-li-signup').className += " active";
  document.getElementById('mn-li-signup').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  var os = getOS();
  if (os === 'iOS'){
    document.getElementById('ios-info').style.color = "#0000ff"; //blue
    document.getElementById('ios-info').innerHTML = '&#63743; ATTENTION: On an Apple mobile device, please make sure:<ul>'
                      + '<li>the popup function in your browser settings is enabled</li>'
                      + '<li>you have a storage ready to save the key-file to (e.g. iCloud Drive, Dropbox, GoogleDrive...)</li></ul>'
                      + 'When the key-file download opens up in a new tap, store it as a file in the location of your choice and close the tap afterwards.';

  }
    var iname = document.forms["theForm"]["p_name"];
    var ipwd = document.forms["theForm"]["p_pwd"];
    var ipmail = document.forms["theForm"]["p_mail"];
    var ivisi = document.forms["theForm"]["p_visible"];
    var isand = document.forms["theForm"]["p_sandbox"];
    var iagb = document.forms["theForm"]["p_agb"];

    function checkPWDConf() {
        if (document.getElementById('p_pwd').value ==
        document.getElementById('p_pwd2').value) {
          document.getElementById('notConf').style.color = 'green';
          document.getElementById('notConf').innerHTML = '';
        } else {
          document.getElementById('notConf').style.color = 'red';
          document.getElementById('notConf').innerHTML = 'not matching';
        }
      }
</script>
</body>
</html>
