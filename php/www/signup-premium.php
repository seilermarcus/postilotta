<?php
  session_start();
  include 'inc/settings.inc';
  include './inc/language-prep.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <script type="text/javascript" src="./zxcvbn/zxcvbn.js" async></script>
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <script src="./cryptojs/aes.js"></script>
  <script src="./cryptojs/enc-base64-min.js"></script>
  <script src="general.js"></script>
  <script src="https://www.paypalobjects.com/api/checkout.js"></script>
</head>
<body>
<?php include 'module-head.php'; ?>
<div id="container" class="container-midi">
<h1><?php echo $ln['header'];?></h1>
<div class="txt">
  <div id="inf" class="inf"></div>
  <form id="paypal_button" action=<?php echo $pal_url;?> method="post" style="display:none;">
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value=<?php echo $pal_business;?>>
    <input type="hidden" name="currency_code" value="EUR">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="a3" value="5.00" id="a3">
    <input type="hidden" name="p3" value="1">
    <input type="hidden" name="t3" value="M">
    <input type="hidden" name="src" value="1">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="item_name" value="set#dynamicly" id="item_name">
    <input type="hidden" name="return" value=<?php echo $pal_return;?>>
    <input type="hidden" name="notify_url" value=<?php echo $pal_notify;?>>
    <input type="image" name="submit" src="pics/paynow.png" alt="Subscribe">
  </form>

  <form id="theForm">

    <div class="capture"><?php echo $ln['inbox'];?></div> <span id='adrtaken'></span><br>
    <input type="text" id="p_name" size="25" onchange="checkAdrExist(this);">#postilotta.org
    <img id="adr-typ" src="./pics/premium_25.png">
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
    <br><br>
  </div>
  </div>
  <div id="container-2" class="container-midi">
    <div class="txt">

    <br>
    <div class="capture"><?php echo $ln['amount'];?></div><?php echo $ln['amount-add'];?><br>
    <input type="number" id="p_price" size="5" style="text-align:right;" onchange="priceChanged(this);">  <?php echo $ln['eur'];?><br>
    <span id='noprice'></span>
    <br>
    <div class="capture" id="paytype"><?php echo $ln['paytype'];?></div><br>
    <input type="radio" id="r_paypal" name="p_pay" value="paypal" onchange='displayPaypalInfo();'> <?php echo $ln['paypal'];?><br>
      <div id="inf-pal" style="display: none;" class="inf"><p><?php echo $ln['paypal_sel'];?></p></div>
    <input type="radio" id="r_bank" name="p_pay" value="bank" onchange='displayBankInfo();'> <?php echo $ln['bank'];?><br>
      <div id="inf-bank" style="display: none;" class="inf"><p><?php echo $ln['bank_sel'];?></p></div>
    <input type="radio" id="r_others" name="p_pay" value="others" onchange='displayOthersInfo();'> <?php echo $ln['other'];?><br>
      <div id="inf-others" style="display: none;" class="inf"><p><?php echo $ln['other_sel'];?></p></div>
    <br>
  </div>
  </div>
  <div id="container-3" class="container-midi">
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
    <button type="button" class="button" onclick="signSubmit(iname.value, ipwd.value, ipmail.value, ivisi.checked, iagb.checked, ipay.value, iprice.value, 'premium', 0)"><?php echo $ln['submit'];?></button>
  </form>

  <p id="err" class="err"></p>

</div>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.forms["theForm"]["p_name"].focus(); // focus first input field
  document.getElementById('tn-li-premium').className += " active";
  document.getElementById('mn-li-premium').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  getAdrNameList();   // Prepare name-exist check
  checkLang();        // Prepare for multilanguage
  pwdStrength();      // Activate password strength test

  var os = getOS(); // TODO move to general.js into a single checkOS()
  if (os === 'iOS'){
    document.getElementById('ios-info').style.color = "#0000ff"; //blue
    document.getElementById('ios-info').innerHTML = '<?php echo $ln['apple'];?>';

  }
    var iname = document.forms["theForm"]["p_name"];
    var ipwd = document.forms["theForm"]["p_pwd"];
    var ipmail = document.forms["theForm"]["p_mail"];
    var ivisi = document.forms["theForm"]["p_visible"];
    var iagb = document.forms["theForm"]["p_agb"];
    var ipay = document.forms["theForm"]["p_pay"];
    var iprice = document.forms["theForm"]["p_price"];

    function displayPaypalInfo(){
      if(document.getElementById('r_paypal').checked){
        document.getElementById('inf-pal').style.display = 'block';
        document.getElementById('inf-bank').style.display = 'none';
        document.getElementById('inf-others').style.display = 'none';
        document.getElementById('paytype').style.color = 'initial';
      }
    }
    function displayBankInfo(){
      if(document.getElementById('r_bank').checked){
        document.getElementById('inf-pal').style.display = 'none';
        document.getElementById('inf-bank').style.display = 'block';
        document.getElementById('inf-others').style.display = 'none';
        document.getElementById('paytype').style.color = 'initial';
      }
    }
    function displayOthersInfo(){
      if(document.getElementById('r_others').checked){
        document.getElementById('inf-pal').style.display = 'none';
        document.getElementById('inf-bank').style.display = 'none';
        document.getElementById('inf-others').style.display = 'block';
        document.getElementById('paytype').style.color = 'initial';
      }
    }
</script>
</body>
</html>
