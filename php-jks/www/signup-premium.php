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
  <script src="https://www.paypalobjects.com/api/checkout.js"></script>
</head>
<body>
<?php include 'module-head.php'; ?>
<h1><?php echo $ln['header'];?></h1>
<div class="txt">
  <form id="theForm">
    <div class="capture"><?php echo $ln['inbox'];?></div><br>
    <input type="text" id="p_name" size="30">#postilotta.org
    <img id="adr-typ" src="./pics/premium_25.png">
    <br><br>
    <div class="capture"><?php echo $ln['pass'];?></div><br>
    <input type="password" id="p_pwd" size="20">
    <br><br>
    <div class="capture"><?php echo $ln['confirm'];?></div><br>
    <input type="password" id="p_pwd2" size="20" onchange='checkPWDConf();'>  <span id='notConf'></span>
    <br><br>
    <div class="capture"><?php echo $ln['email'];?></div> <?php echo $ln['optional'];?><br>
    <input type="email" id="p_mail" size="40"><br>
    <br>
<hr>
    <div>
      <input type="checkbox" id="p_visible" name="p_visible" value="1" checked>
      <label for="visible"><div class="capture"><?php echo $ln['visible'];?></div> <?php echo $ln['vis_explain'];?></label>
    </div>
    <br>
    <div>
      <input type="checkbox" id="p_agb" name="p_agb" value="1">
      <label for="agb"><?php echo $ln['agb'];?></label>
    </div>
    <br>
    <hr>
    <div class="capture"><?php echo $ln['pay_header'];?></div><br>
    <br>
    <?php echo $ln['suggest'];?>
    <br>
    <input type="number" id="p_price" size="10"><?php echo $ln['eur'];?>
    <br><br>
    <?php echo $ln['care'];?><br>
    <br>
    <input type="radio" id="r_paypal" name="p_pay" value="paypal" onchange='displayPaypalInfo();'> <?php echo $ln['paypal'];?><br>
      <div id="inf-pal" style="display: none;" class="inf"><p><?php echo $ln['paypal_sel'];?></p></div>
    <input type="radio" id="r_bank" name="p_pay" value="bank" onchange='displayBankInfo();'> <?php echo $ln['bank'];?><br>
      <div id="inf-bank" style="display: none;" class="inf"><p><?php echo $ln['bank_sel'];?></p></div>
    <input type="radio" id="r_others" name="p_pay" value="others" onchange='displayOthersInfo();'> <?php echo $ln['other'];?><br>
      <div id="inf-others" style="display: none;" class="inf"><p><?php echo $ln['other_sel'];?></p></div>
    <br>
    <?php echo $ln['soon'];?>
    <br><br>
    <hr>
    <?php echo $ln['hit'];?>
    <ul>
      <?php echo $ln['list'];?>
    </ul>
    <?php echo $ln['sure'];?>
    <br>
    <p id="ios-info"></p>
    <div style="color:red"><p><?php echo $ln['non-prod'];?></p></div>
    <button type="button" class="button" onclick="signSubmit(iname.value, ipwd.value, ipmail.value, ivisi.checked, iagb.checked, ipay.value, iprice.value, 'premium', 0)"><?php echo $ln['submit'];?></button>
  </form>

  <p id="inf" class="inf"></p>
  <p id="err" class="err"></p>
  <p id="out"></p>

  <form id="paypal_button" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" style="display:none;"><!-- TODO from settings -->
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value="marcus.seiler-facilitator@uwezo-engineering.com"><!-- TODO from settings -->
    <input type="hidden" name="currency_code" value="EUR">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="a3" value="5.00"><!-- TODO make dynamic value onchange p_price-->
    <input type="hidden" name="p3" value="1">
    <input type="hidden" name="t3" value="M">
    <input type="hidden" name="src" value="1">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="item_name" value="set#dynamicly"><!-- TODO make value dynamic onchange p_adr-->
    <input type="hidden" name="return" value="https://prototype.postilotta.com/login.php"><!-- TODO from settings -->
    <input type="hidden" name="notify_url" value="https://prototype.postilotta.com/paypal-confirm.php"><!-- TODO from settings -->
    <input type="image" name="submit" src="pics/paynow.png" alt="Subscribe">
  </form>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  document.getElementById('tn-li-premium').className += " active";
  document.getElementById('mn-li-premium').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  checkLang();        // Prepare for multilanguage

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

    function checkPWDConf() {
        if (document.getElementById('p_pwd').value == document.getElementById('p_pwd2').value) {
          document.getElementById('notConf').style.color = 'green';
          document.getElementById('notConf').innerHTML = '';
        } else {
          document.getElementById('notConf').style.color = 'red';
          document.getElementById('notConf').innerHTML = 'not matching';
        }
      }
    function displayPaypalInfo(){
      if(document.getElementById('r_paypal').checked){
        document.getElementById('inf-pal').style.display = 'block';
        document.getElementById('inf-bank').style.display = 'none';
        document.getElementById('inf-others').style.display = 'none';
      }
    }
    function displayBankInfo(){
      if(document.getElementById('r_bank').checked){
        document.getElementById('inf-pal').style.display = 'none';
        document.getElementById('inf-bank').style.display = 'block';
        document.getElementById('inf-others').style.display = 'none';
      }
    }
    function displayOthersInfo(){
      if(document.getElementById('r_others').checked){
        document.getElementById('inf-pal').style.display = 'none';
        document.getElementById('inf-bank').style.display = 'none';
        document.getElementById('inf-others').style.display = 'block';
      }
    }
</script>
</body>
</html>
