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
<?php include 'module-head.htm'; ?>
<h1>Get Your postilotta Premium Inbox</h1>
<div class="txt">
  <form id="theForm">
    <div class="capture">Desired inbox address:</div><br>
    <input type="text" id="p_name" size="30">#postilotta.org
    <br><br>
    <div class="capture">Login Password:</div><br>
    <input type="password" id="p_pwd" size="20">
    <br><br>
    <div class="capture">Confirm Password:</div><br>
    <input type="password" id="p_pwd2" size="20" onchange='checkPWDConf();'>  <span id='notConf'></span>
    <br><br>
    <div class="capture">E-Mail</div> (optional, if you want to get notified about new messages):<br>
    <input type="email" id="p_mail" size="40"><br>
    <br>
<hr>
    <div>
      <input type="checkbox" id="p_visible" name="p_visible" value="1" checked>
      <label for="visible"><div class="capture">Visible</div> (e.g. in dropdown list and autocompletion on send form. You can change it anytime after login.)</label>
    </div>
    <br>
    <div>
      <input type="checkbox" id="p_agb" name="p_agb" value="1">
      <label for="agb">I agree with postilotta's <u><a href="terms.php">Terms</a></u> and <u><a href="privacy.php">Privacy Policy</a></u></label>
    </div>
    <br>
    <hr>
    <div class="capture">Payment &amp; Price</div><br>
    <br>
    Make a suggestion you want to pay.<br>
    Considere your financial means and expected benefit, e.g. somewhat between 1 and 100 €.<br>
    <input type="number" id="p_price" size="10"> EUR per month
    <br><br>
    We don't care who or how. Just make sure there are credit entries refering to your inbox name or id (?) in reason for transfer.<br>

    <input type="radio" id="r_paypal" name="p_pay" value="paypal" onchange='displayPaypaInfo();'> PayPal<br>
      <div id="inf-pal" style="display: none;" class="inf"><p>Great choice. For your convenience a PayPal 'PayNow-Button' will be displayed after you hit submit.</p></div>
    <input type="radio" id="r_bank" name="p_pay" value="bank" onchange='displayBankInfo();'> Bank Transfer<br>
      <div id="inf-bank" style="display: none;" class="inf"><p>IBAN:.... BIC:...</p></div>
    <input type="radio" id="r_others" name="p_pay" value="others" onchange='displayOthersInfo();'> Others<br>
      <div id="inf-others" style="display: none;" class="inf"><p>O.k. You do it your way.</p></div>
    <br>
    Further payment options coming soon.<br>
    <br>
    <hr>
    When you hit the submit button (and the name isn't already taken), the following things will happen:
    <ul>
      <li>Your new postilotta premium inbox will be created and immediately available for incoming messages.</li>
      <li>A key file will be generated for you, which will be the only way to decrypt messages send to your inbox.</li>
    </ul>
    So make sure you keep your password and key-file safe and secure.
    <br>
    <p id="ios-info"></p>
    <div style="color:red"><p>You noticed that this service is not yet in productive use, right?</p></div>
    <button type="button" class="button" onclick="signSubmit(iname.value, ipwd.value, ipmail.value, ivisi.checked, iagb.checked, ipay.value, iprice.value, 'premium', 0)">Submit</button>
  </form>

  <p id="inf" class="inf"></p>
  <p id="err" class="err"></p><br>
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
    <input type="hidden" name="notify_url" value="prototype.postilotta.com/paypal-notify.php"><!-- TODO from settings -->
    <input type="image" name="submit" src="pics/paynow.png" alt="Subscribe">
  </form>
</div>
<?php include 'module-banner-small.htm'; ?>
<?php include 'module-footer.htm'; ?>
<script>
  document.getElementById('tn-li-premium').className += " active";
  document.getElementById('mn-li-premium').className += " active";
  clearSessionSoft(); // paranoia vars excluded
  checkParaOn();      // Paranoia mode
  var os = getOS(); // TODO move to general.js into a single checkOS()
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
    var iagb = document.forms["theForm"]["p_agb"];
    var ipay = document.forms["theForm"]["p_pay"];
    var iprice = document.forms["theForm"]["p_price"];

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
    function displayPaypaInfo(){
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
