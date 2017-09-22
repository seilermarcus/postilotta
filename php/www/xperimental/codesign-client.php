<!DOCTYPE html>
<html>
<head>
  <!-- SJCL -->
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <!-- -->
  <script src="http://kjur.github.io/jsrsasign/jsrsasign-latest-all-min.js"></script>

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
      var pack = this.responseText;
      document.getElementById('out').innerHTML = this.responseText;

      var pub = document.getElementById('pubkey').innerHTML;

      // initialize
      var sig = new KJUR.crypto.Signature({"alg": "SHA256withRSA"});
      // initialize for signature validation
      sig.init(pub); // signer's certificate
      // update data
      //sig.updateString('aaa')
      // verify signature
      var isValid = sig.verify(pack);
      document.getElementById('out').innerHTML = 'isValid: ' + isValid;

    }
  };
  xhttp.open("POST", "codesign-server.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send();
}


</script>

</body>
</html>
