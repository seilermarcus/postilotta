<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <script type="text/javascript" src="./zxcvbn/zxcvbn.js"></script>
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <script src="./cryptojs/aes.js"></script>
  <script src="./cryptojs/enc-base64-min.js"></script>
  <script src="general.js"></script>
</head>
<body>
<?php include 'module-head.php'; ?>

<div id="container-1" class="container-midi">

<h1><?php echo $ln['header'];?></h1>
<div class="txt">
  <div id="inf" class="inf"></div>
  <form id="theForm">
    <div class="capture"><?php echo $ln['inbox'];?></div> <span id='adrtaken'></span><br>
    <input type="text" id="p_name" size="25" onchange="checkAdrExist(this);">#postilotta.org
    <img id="adr-typ" src="./pics/basic_25.png">
    <br><br>

    <div class="capture"><?php echo $ln['pass'];?></div> <span id="password-strength-text"></span><br>
    <div class="biginput">
    <input type="password" id="p_pwd">
    </div>
    <br><br>

    <div class="capture"><?php echo $ln['confirm'];?></div> <span id='notConf'></span><br>
    <div class="biginput">
    <input type="password" id="p_pwd2" size="40" onchange='checkPWDConf();'>
    </div>
    <br><br>

    <div class="capture"><?php echo $ln['email'];?></div> <?php echo $ln['optional'];?><br>
    <div class="biginput">
    <input type="email" id="p_mail" size="40">
    </div>
    <br><br>
    <div>
      <input type="checkbox" id="p_visible" name="visible" value="1" checked>
      <label for="visible"><div class="capture"><?php echo $ln['visible'];?></div> <?php echo $ln['vis_explain'];?></label>
    </div>
    <br>
    <div>
      <input type="checkbox" id="p_sandbox" name="sandbox" value="1">
      <label for="visible"><div class="capture"><?php echo $ln['testbox'];?></div> <?php echo $ln['test_explain'];?></label>
    </div>
    <br>

  </div>
  </div>
  <div id="container-2" class="container-midi">
    <div class="txt">

    <br>
    <?php echo $ln['hit'];?>
    <ul>
      <?php echo $ln['list'];?>
    </ul>
    <?php echo $ln['sure'];?>
    <br>
    <p id="ios-info"></p>
    <div>
      <input type="checkbox" id="p_agb" name="agb" value="1" onchange="agbChanged(this);">
      <label id="l_agb" for="agb"><?php echo $ln['agb'];?></label>
    </div>
    <div style="color:red"><p><?php echo $ln['non-prod'];?></p></div>
    <button type="button" id="signup" class="button" onclick="signSubmit(iname.value, ipwd.value, ipmail.value, ivisi.checked, iagb.checked, 0, 0, 'basic', isand.checked)"><?php echo $ln['submit'];?></button> or <button type="button" class="buttonfrm" onclick="window.location='signup-premium.php'"><?php echo $ln['gopremium'];?></button>
    </form>

  <p id="err" class="err"></p><br>
</div>

</div>

<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.forms["theForm"]["p_name"].focus(); // focus first input field
  document.getElementById('tn-li-signup').className += " active";
  document.getElementById('mn-li-signup').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkLang();        // Prepare for multilanguage
  pwdStrength();      // Activate password strength test
  checkParaOn();      // Paranoia mode
  getAdrNameList();    // Prepare name-exist check

  var os = getOS();
  if (os === 'iOS'){
    document.getElementById('ios-info').style.color = "#0000ff"; //blue
    document.getElementById('ios-info').innerHTML = '<?php echo $ln['apple'];?>';

  }
    var iname = document.forms["theForm"]["p_name"];
    var ipwd = document.forms["theForm"]["p_pwd"];
    var ipmail = document.forms["theForm"]["p_mail"];
    var ivisi = document.forms["theForm"]["p_visible"];
    var isand = document.forms["theForm"]["p_sandbox"];
    var iagb = document.forms["theForm"]["p_agb"];

</script>
</body>
</html>
