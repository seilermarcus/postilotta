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
<h1><?php echo $ln['header'];?></h1>
<div class="txt">
  <form id="theForm">
    <div class="capture"><?php echo $ln['inbox'];?></div><br>
    <input type="text" id="p_name" size="30">#postilotta.org
    <br><br>
    <div class="capture"><?php echo $ln['pass'];?></div><br>
    <input type="password" id="p_pwd" size="20">
    <br><br>
    <div class="capture"><?php echo $ln['confirm'];?></div><br>
    <input type="password" id="p_pwd2" size="20" onchange='checkPWDConf();'>  <span id='notConf'></span>
    <br><br>
    <div class="capture"><?php echo $ln['email'];?></div> <?php echo $ln['optional'];?><br>
    <input type="email" id="p_mail" size="40">
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
    <div>
      <input type="checkbox" id="p_agb" name="agb" value="1">
      <label for="agb"><?php echo $ln['agb'];?></label>
    </div>
    <br><br>
    <?php echo $ln['hit'];?>
    <ul>
      <?php echo $ln['list'];?>
    </ul>
    <?php echo $ln['sure'];?>
    <br>
    <p id="ios-info"></p>
    <div style="color:red"><p><?php echo $ln['non-prod'];?></p></div>
    <button type="button" class="button" onclick="signSubmit(iname.value, ipwd.value, ipmail.value, ivisi.checked, iagb.checked, 0, 0, 'basic', isand.checked)"><?php echo $ln['submit'];?></button>
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
  checkLang();        // Prepare for multilanguage
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

    function checkPWDConf() {
        if (document.getElementById('p_pwd').value ==
        document.getElementById('p_pwd2').value) {
          document.getElementById('notConf').style.color = 'green';
          document.getElementById('notConf').innerHTML = '';
        } else {
          document.getElementById('notConf').style.color = 'red';
          document.getElementById('notConf').innerHTML = '<?php echo $ln['no_match'];?>';
        }
      }
</script>
</body>
</html>
