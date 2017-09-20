<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<form id="sendInbox">
  <div class="capture"><?php echo $ln['inbox'];?></div><br>
  <input type="text" name="p_to" id="p_to" list="adds"  autocomplete="on" size="20" onchange="adrSelect(this);">#postilotta.org
  <img id="adr-typ" src="">
  <div class="tooltip">
    <img id="adr-idv" src="">
    <span class="tooltiptext"><?php echo $ln['tt_idv']; ?></span>
  </div>
  <datalist id="adds">
  </datalist>
  <br><br>
  <div class="capture"><?php echo $ln['message'];?></div><br>
  <textarea name="p_text" id="p_text" cols="50" rows="10"></textarea>
  <br><br>
  <div class="capture"><?php echo $ln['attach'];?></div> <p id="attReady"></p>
  <input type="file" class="button" id="attach" size="50" onchange="upAttach()">
  <br><br>
  <?php echo $ln['hit'];?>
  <ul>
    <?php echo $ln['list'];?>
  </ul>
  <br>
  <button type="button" id="send" class="button" onclick="sendNewMessage()"><?php echo $ln['submit'];?></button>
</form>
