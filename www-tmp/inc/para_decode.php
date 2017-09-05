<?php
session_start();
/*
if( isset($_SESSION['paranoiaPWD']) && ($_SESSION['paranoiaPWD'] != null) ) {
  foreach($_POST as $key => $value) {
    //$value = urldecode($value);
    $_REQUEST[$key] = cryptoJsAesDecrypt($_SESSION['paranoiaPWD'], $value);
  }

  // For Debugging
//  echo '<br>$_GET: ' . var_dump($_GET);
//  echo '<br>paraPWD: ' . $_SESSION['paranoiaPWD'];
  foreach($_GET as $key => $value) {
    echo '<br>paraPWD: ' . $_SESSION['paranoiaPWD'];
    echo '<br>key: '. $key;
    echo '<br>value: '. $value;
    //$value = urldecode($value);
//    echo '<br>value urldecoded: '. $value;
    echo '<br>crypt output: '. cryptoJsAesDecrypt($_SESSION['paranoiaPWD'], $value);
    $_REQUEST[$key] = cryptoJsAesDecrypt($_SESSION['paranoiaPWD'], $value);
    echo '<br>$_REQUEST[$key]: '. $_REQUEST[$key];
  }
}
*/
/**
* Decrypt data from a CryptoJS json encoding string
*
* @param mixed $passphrase
* @param mixed $jsonString
* @return mixed
*/
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
?>
