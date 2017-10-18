<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<h3><?php echo $ln['header'];?></h3>
<form id="settings">
<table class="set">
  <tr>
    <td><?php echo $ln['mail'];?>:</td>
    <td><input type="email" id="p_mail" size="40"></td>
  </tr>
  <tr>
    <td><?php echo $ln['pass'];?>:</td>
    <td><input type="password" id="p_newpas" size="20"></td>
  </tr>
  <tr>
    <td><?php echo $ln['key'];?>:</td>
    <td><button type="button" class="buttonfrm" onclick="alert('info@uwezo-engineering.com');"><?php echo $ln['b_key'];?></button></td>
  </tr>
  <tr>
    <td><?php echo $ln['vis'];?>:</td>
    <td><input type="checkbox" id="p_visible" name="p_visible" value="1" >  <?php echo $ln['t_vis'];?></td>
  </tr>
  <tr>
    <td><?php echo $ln['typ'];?>:</td>
    <td><div id="p_typ"></div></td>
  </tr>
</table>
<br>
  <button id="sub1" type="button" class="button" onclick="submitSettingsUpdate('coming soon');"><?php echo $ln['b_submit'];?></button><br>
<br>
<hr>
<h3><?php echo $ln['prem_header'];?></h3>
<table class="set">
  <tr>
    <td><?php echo $ln['exp'];?></td>
    <td><input type="numner" id="p_msglife" size="5" readonly> <?php echo $ln['hours'];?></td>
  </tr>
  <tr>
    <td><?php echo $ln['very'];?>:</td>
    <td><button id="b_very" type="button" class="buttonfrm" onclick="loadVerify();"><?php echo $ln['b_very'];?></button><div id="d_very"></div></td>
  </tr>
  <tr>
    <td><?php echo $ln['color'];?>:</td>
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
    <td><?php echo $ln['domain'];?>:</td>
    <td><button type="button" class="buttonfrm" onclick="alert('coming soon');"><?php echo $ln['b_domain'];?></button></td>
  </tr>
  <tr>
    <td><?php echo $ln['pay'];?>:</td>
    <td>
      <div class="setselect">
        <select id="p_pay" name="p_pay" disabled>
            <option id="o_none" value="none">- none -</option>
             <option id="o_paypal" value="paypal"><?php echo $ln['o_pay']['paypal'];?></option>
             <option id="o_bank" value="bank"><?php echo $ln['o_pay']['bank'];?></option>
             <option id="o_others" value="others"><?php echo $ln['o_pay']['others'];?></option>
        </select>
      </div>
    </td>
  </tr>
  <tr>
    <td><?php echo $ln['amount'];?>:</td>
    <td><input type="numner" id="p_price" size="5" readonly> <?php echo $ln['per'];?></td>
  </tr>
  <tr>
    <td><?php echo $ln['state'];?>:</td>
    <td><div id="p_until">--</div></td>
  </tr>
  <tr>
    <td><div id="d_quit"><?php echo $ln['terminate'];?>:</div></td>
    <td><button id="b_quit" type="button" class="buttonfrm" onclick="alert('coming soon');"><?php echo $ln['b_basic'];?></button>
      <button id="b_sign" type="button" class="buttonfrm" onclick="window.location='signup-premium.php';"><?php echo $ln['b_prem'];?></button></td>
  </tr>
</table>
<br>
<button id="sub2" type="button" class="button" onclick="submitSettingsUpdate('coming soon');"><?php echo $ln['b_submit'];?></button>
</form>
