<?php

echo $_SERVER[SERVER_NAME]=='dev.postilotta.com';

/*
include 'inc/settings.inc';
include 'inc/crypto-lib.inc';

//$encrypted = $_REQUEST['c'];
$paralnk = $_REQUEST['paralnk'];
$out = 'idiot';
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // get passphrase related to paranoiaLink
  $sql = "SELECT Passphrase FROM Paranoia WHERE PLink='". $paralnk ."'";
  $stmt = $conn->query($sql);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $pass = $result[0]["Passphrase"];
  //echo var_dump($pass);

  // encrypt whole package
  $encrypted = cryptoJsAesEncrypt($pass, $out);
  $out = $encrypted;
  echo $encrypted;

}catch(PDOException $e) {
  $mtext = $sql . " - " . $e->getMessage();
  $arr = array('rcode' => 1, 'msg' => $mtext, 'lnk' => 0);
  $out = json_encode($arr);
  echo $out;
}

$conn = null;
*/

/*
$encrypted = cryptoJsAesEncrypt("pwd", "Greatings from Server!");
$decrypted = cryptoJsAesDecrypt("pwd", $encrypted);

echo $encrypted;
*/
/**
* Decrypt data from a CryptoJS json encoding string
*
* @param mixed $passphrase
* @param mixed $jsonString
* @return mixed
*/
/*
function cryptoJsAesDecrypt($passphrase, $jsonString){
    $jsondata = json_decode($jsonString, true);
    $salt = hex2bin($jsondata["s"]);
    $ct = base64_decode($jsondata["ct"]);
    $iv  = hex2bin($jsondata["iv"]);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return json_decode($data, true);
}
*/
/**
* Encrypt value to a cryptojs compatiable json encoding string
*
* @param mixed $passphrase
* @param mixed $value
* @return string
*/
/*
function cryptoJsAesEncrypt($passphrase, $value){
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx.$passphrase.$salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    return json_encode($data);
}
*/
?>
