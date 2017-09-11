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
</head>
<body>
<?php include 'module-head.htm'; ?>
<h1 id="p_h1" style="display:inline">Inbox: </h1><img id="typ" src="">
<div class="txt">
  <p id="err" class="err"></p>
  <p id="inf" class="inf">Welcome</p>
  <p id="out"></p>
  <p id="fileup"></p>
</div>
  <hr>
  <div class="txt">
    <p id="toolbar">
      <button id="fetchList" type="button" class="button" onclick="fetchMsgs(sessionStorage.p_adr)">List Messages</button>
      <button id="sendMsg" type="button" class="button" onclick="loadSendForm()">Send Message</button>
      <button id="logOut" type="button" class="button" onclick="logOut()">Logout</button>
      <button id="blowUp" type="button" class="button" onclick="confirmBlow(sessionStorage.p_adr)">Destroy Inbox</button>
    </p>
  </div>
  <?php include 'module-banner-small.htm'; ?>
  <?php include 'module-footer.htm'; ?>
    <script>
    if (sessionStorage.p_adr == null){
     location.replace('login.php');
    } else {
      checkPremium();
      document.getElementById('tn-li-login').className += " active";
      document.getElementById('mn-li-login').className += " active";
      // Paranoia mode
      checkParaOn();
      // Header
      document.getElementById("p_h1").innerHTML += sessionStorage.p_adr + '#postilotta.org';
      // Prepare i2i encryption
      setOwnPub();
    }
    </script>
</body>
</html>
