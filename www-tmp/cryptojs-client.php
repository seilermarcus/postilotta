<!DOCTYPE html>
<html>
<head>
  <!-- SJCL -->
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <!-- JSEncrypt -->
  <script src="./jsencrypt/jsencrypt.min.js"></script>
  <!-- postilotta core -->
  <script src="general.js"></script>
</head>
<body>
  <form>
    <input type='text' id="phrase" size="20">
    <button type="button" onclick="sendCipherPWD()">SendPWD</button><br>
    <button type="button" onclick="fetchPub()">Fetch</button>
</form>

<p id="out"></p>


<!--- CryptoJS AES Libraries --->
<script src="./cryptojs/aes.js"></script>
<script src="./cryptojs/enc-base64-min.js"></script>

<script>
/*
var myString   = "https://www.titanesmedellin.com/";
var myPassword = "myPassword";


// PROCESS
var encrypted = CryptoJS.AES.encrypt(myString, myPassword);
var decrypted = CryptoJS.AES.decrypt(encrypted, myPassword);

document.getElementById("out").innerHTML += 'myString: ' + myString;
document.getElementById("out").innerHTML += '<br>encrypted: ' + encrypted;
document.getElementById("out").innerHTML += '<br>decrypted: ' + decrypted;
document.getElementById("out").innerHTML += '<br>decrypted Utf8: ' + decrypted.toString(CryptoJS.enc.Utf8);
*/


/* ----- working example ------
var encrypted = CryptoJS.AES.encrypt(JSON.stringify("My Message!"), "pwd", {format: CryptoJSAesJson}).toString();
var decrypted = JSON.parse(CryptoJS.AES.decrypt(encrypted, "pwd", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));

document.getElementById("out").innerHTML += 'encrypted: ' + encrypted;
document.getElementById("out").innerHTML += '<br> decrypted: ' + decrypted;
*/

var link = 'efcaee27b1943a15db64bbf98c9d1cdecf964bea0657f04f0ffd9d62fc1f39d1';
var pass = '123';

con('cryptojs-server.php', {paralnk:link}, cbAgter_decrypt);
function cbAgter_decrypt(th){
  document.getElementById('out').innerHTML += '<br>' + th.responseText;
  var decrypted = JSON.parse(CryptoJS.AES.decrypt(th.responseText, pass, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
  document.getElementById('out').innerHTML += '<br>' + decrypted;
}

</script>
</body>
</html>
