<?php include './inc/language-prep.php'; ?>
A more sophisticated verification process coming soon. But for now:<br>
<br>
<p>
  First Name:
<input type="text" name="p_fname" id="p_fname" size="10">
Last Name:
<input type="text" name="p_lname" id="p_lname" size="10">
</p>
<input type="checkbox" id="p_vow" name="p_vow" value="1">
<label for="p_vow">I vow to be who I claim to be.</label>
</div><br>
<br>
<button type="button" id="very" class="button" onclick="verifyID()">Submit</button>
