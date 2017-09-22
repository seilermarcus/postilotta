
<!-- Large Toolbar-->
<ul class="toolbar">
  <li><div id="tb-li-fetch" class="tbitem" onclick="fetchMsgs(sessionStorage.p_adr)"><?php echo $ln['fetchmsgs']; ?></div></li>
  <li><div id="tb-li-send" class="tbitem" onclick="loadSendForm()"><?php echo $ln['loadsendform']; ?></div></li>
  <li><div id="tb-li-logout" class="tbitem" onclick="logOut()"><?php echo $ln['logout']; ?></div></li>
  <li><div id="tb-li-destroy" class="tbitem" onclick="confirmBlow(sessionStorage.p_adr)"><?php echo $ln['confirmblow']; ?></div></li>
  <li><div id="tb-li-settings" class="tbitem" onclick="loadSettings()"><?php echo $ln['loadsettings']; ?></div></li>
  <!--<li><div id="tb-li-menu" href='#' style="display:none" class="show-small tbitem" onclick="replaceNav()">&#9776;</div></li>-->
</ul>
