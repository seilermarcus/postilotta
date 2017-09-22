<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<h3>Settings</h3>
<form id="settings">
<table class="set">
  <tr>
    <td>Email:</td>
    <td><input type="email" id="p_mail" size="40"> (?)</td><!-- readonly -->
  </tr>
  <tr>
    <td>Reset Password:</td>
    <td><input type="password" id="p_newpas" size="20"> (?)</td>
  </tr>
  <tr>
    <td>Reset Key-File:</td>
    <td><button type="button" class="butonfrm" onclick="alert('coming soon');">Generate New Key</button> (?)</td>
  </tr>
  <tr>
    <td>Visible:</td>
    <td><input type="checkbox" id="p_visible" name="p_visible" value="1" > (?)</td><!-- onclick="return false;" -->
  </tr>
  <tr>
    <td>Inbox Type: </td>
    <td><div id="p_typ"></div></td>
  </tr>
</table>
<hr>
<h3>premium Settings</h3>
<table class="set">
  <tr>
    <td>Default Expiration:<br>(of Send Messages)</td>
    <td><input type="numner" id="p_msglife" size="5" readonly> hours (?)</td>
  </tr>
  <tr>
    <td>ID Verification:</td>
    <td><button id="b_very" type="button" class="butonfrm" onclick="loadVerify();">Start Verification</button><div id="d_very"></div> (?)</td>
  </tr>
  <tr>
    <td>Color Scheme:<br>(coming soon)</td>
    <td>
      <div class="setselect">
      <select id="p_color" name="p_color" disabled>
        <option value="blue">blue</option>
        <option value="yellow" selected>yellow</option>
        <option value="grey">grey</option>
        <option value="black">black</option>
        <option value="forest">forest</option>
        <option value="urban">urban</option>
      </select>
    </div>
    </td>
  </tr>
  <tr>
    <td>Own Subdomain:</td>
    <td><button type="button" class="butonfrm" onclick="alert('coming soon');">Request Subdomain</button> (?)</td>
  </tr>
  <tr>
    <td>Payment Type:</td>
    <td>
      <div class="setselect">
        <select id="p_pay" name="p_pay" disabled>
            <option id="o_none" value="none">- none -</option>
             <option id="o_paypal" value="paypal">PayPal</option>
             <option id="o_bank" value="bank">Bank Transfer</option>
             <option id="o_others" value="others">Others</option>
        </select>
      </div>
    </td>
  </tr>
  <tr>
    <td>Contribution:</td>
    <td><input type="numner" id="p_price" size="5" readonly> € per Month (?)</td>
  </tr>
  <tr>
    <td>Payment State:</td>
    <td><div id="p_until">--</div></td>
  </tr>
  <tr>
    <td><div id="d_quit">Terminate premium:</div></td>
    <td><button id="b_quit" type="button" class="butonfrm" onclick="alert('coming soon');">Back to basic</button>
      <button id="b_sign" type="button" class="butonfrm" onclick="window.location='signup-premium.php';">SignUp</button> (?)</td>
  </tr>
</table>
<br>
<button type="button" class="button" onclick="submitSettingsUpdate('coming soon');">Submit Updates</button></td>
</form>
