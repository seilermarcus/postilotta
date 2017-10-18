<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>

<h2 align="center"><?php echo $ln['header'];?><h2>
<h3>Personal Inbox</h3>
To lable your inbox with
<div class="tooltip">
  <img id="adr-idv" src="./pics/id-verified_yellow_30.png">
  <span class="tooltiptext"><?php echo $ln['tt_idv']; ?></span>
</div> we need to be convinced that you are the one, people expect to reach when sending a message to this inbox.<br>
To acchive this:<br>
<br>

<button type="button" id="very" class="button" onclick="verifyID()">Click Here</button> and choose date and time for a virtual signing appointment.
<table style="padding-left:40px">
<tr><td style="text-align:center"><img src="./pics/arrow_60.png"></td><td></td></tr>
<tr><td style="text-align:center"><img src="./pics/one_60.png"></td><td style="padding-left:5px">We send you a legally efficient assurance form.</td></tr>
<tr><td style="text-align:center"><img src="./pics/arrow_60.png"></td><td></td></tr>
<tr><td style="text-align:center"><img src="./pics/two_60.png"></td><td>You and a postilotta staff meet via <a href="https://appear.in/"><u>appeare.in</u></a> to fillout the form together.</td></tr>
<tr><td style="text-align:center"><img src="./pics/arrow_60.png"></td><td></td></tr>
<tr><td style="text-align:center"><img src="./pics/three_60.png"></td><td>You send the (scanned) form and a copy of your ID card back to us.<br>Done :)</td></tr>
</table>
<br>

<hr>
<h3>Organizational Inbox</h3>
If a sender addresses an organization when sending a message to your inbox, the id-verification process is the same as for personal inboxes (see above),
and in addition we need to ensure that you are an authorised representative of that organization.<br>
<br>
This consists of 2 parts:
<table>
<tr><td style="text-align:center"><img src="./pics/one_60.png"></td><td>We send you a legally efficient form to be filled out be an authorized signatory of your organization.</td></tr>
<tr><td style="text-align:center"><img src="./pics/two_60.png"></td><td>The inbox address appears on an official website of your organization.</td></tr>
</table>
<br>
To start the process <button type="button" id="very" class="button" onclick="verifyID()">Click Here</button> and choose date and time for a virtual signing appointment.
<br>
