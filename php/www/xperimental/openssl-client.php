<!DOCTYPE html>
<html>
<head>
  <!-- SJCL -->
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <!-- JSEncrypt -->
  <script src="./jsencrypt/jsencrypt.min.js"></script>
</head>
<body>
  <form>
    <input type='text' id="phrase" size="20">
    <button type="button" onclick="fetchPub()">Go</button>
</form>
<p hidden id="pubkey">
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDlOJu6TyygqxfWT7eLtGDwajtN
FOb9I5XRb6khyfD1Yt3YiCgQWMNW649887VGJiGr/L5i2osbl8C9+WJTeucF+S76
xFxdU6jE0NQ+Z+zEdhUTooNRaY5nZiu5PgDB0ED/ZKBUSLKL7eibMxZtMlUDHjm4
gwQco1KRMDSmXSMkDwIDAQAB
-----END PUBLIC KEY-----
</p>
<p id="out"></p>
<script>
function fetchPub(){
  var text = document.getElementById('phrase').value;
  var bitArray = sjcl.hash.sha256.hash(text);
  var digest_sha256 = sjcl.codec.hex.fromBits(bitArray);
  console.log('digest_sha256: ' + digest_sha256);
  var out = digest_sha256;

  // transmission
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('out').innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "openssl-server.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("c=" + out);
}


function sendCipher(){
  // encryption
  var encrypt = new JSEncrypt();
  var pub_s = document.getElementById('pubkey').innerHTML;
  encrypt.setPublicKey(pub_s);
  var encrypted = encrypt.encrypt('My Message');
  console.log('encrypted: ' + encrypted);

  // transmission

  var out = encodeURIComponent(encodeURI(encrypted));

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('out').innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "openssl-server.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("c=" + out);
}

</script>

</body>
</html>
