<!DOCTYPE html>
<html>
<head>
</head>
<body>
  <form>
    <button type="button" class="button" onclick="getOwnPub()">Get Pub of 1</button><br>
    <br>Please select your key file to decode the message content:<br>
    <input type="file" class="button" id="keyFile" size="50" onchange="signIt()">';
</form>

<p id="out"></p>
<!-- postilotta core -->
<script src="general.js"></script>
<!-- SJCL -->
<script type="text/javascript" src="./sjcl/sjcl.js"></script>

<script>
function getOwnPub(){

  var xhttp = new XMLHttpRequest();
  // Organize response handling
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var jpub = transmissonDecryption(this.responseText);
      var spub = jpub[0].PubKey;

      //Unmask back from utf-8 to string
      spub = decodeURIComponent(decodeURI(spub));

      //Deserialize public key
      var pub = new sjcl.ecc.elGamal.publicKey(
        sjcl.ecc.curves.k256,
        sjcl.codec.base64.toBits(spub)
      );

      sessionStorage.pub = pub;

      document.getElementById('out').innerHTML += 'pub: ' + spub + '<br>';
    }
  };

  //Transmit message to server
  xhttp.open("POST", "get_pub.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("to=" + "1");
}


function signIt(){
  var file = document.getElementById("keyFile").files[0];
  var reader = new FileReader();

  //Process after file is loaded
  reader.onload = function(e) {
    var t_text = reader.result;
    t_text = decodeURIComponent(decodeURI(t_text));
    document.getElementById('out').innerHTML += '<br>Sec Key File: ' + t_text;

    //Unserialize secure key
    var sec = new sjcl.ecc.elGamal.secretKey(
        sjcl.ecc.curves.k256,
        sjcl.ecc.curves.k256.field.fromBits(sjcl.codec.base64.toBits(t_text))
    );


  };

  // Start loading file
  var out = reader.readAsText(file);
}


// Must be ECDSA!
var pair = sjcl.ecc.ecdsa.generateKeys(256);
//var sec2 = pair.sec.get()

var sig = pair.sec.sign(sjcl.hash.sha256.hash("Hello World!"));
// [ 799253862, -791427911, -170134622, ...
document.getElementById('out').innerHTML += 'sig: ' + sig;
try{
  var ok = pair.pub.verify(sjcl.hash.sha256.hash("Hello World!"), sig);
  // Either `true` or an error will be thrown.
  document.getElementById('out').innerHTML += '<br>ok: ' + ok;
}catch(err) {
  document.getElementById('out').innerHTML = 'Verification failed: ' + err.message;
}
</script>

</body>
</html>
