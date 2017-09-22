<?php
session_start();
/*
if( !isset($_SESSION['paranoiaPWD'] ){
  die();
}
*/

// pass-through session
$strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';
session_write_close();

$json = file_get_contents('php://input');   // json str out of raw input
$obj = json_decode($json, true);            // get parameter array

/*
$fields = array(
	'lname' => urlencode($_POST['last_name']),
	'fname' => urlencode($_POST['first_name']),
	'title' => urlencode($_POST['title']),
	'company' => urlencode($_POST['institution']),
	'age' => urlencode($_POST['age']),
	'email' => urlencode($_POST['email']),
	'phone' => urlencode($_POST['phone'])
);
*/

//url-ify the data for the POST
foreach($obj as $key=>$value) {
  $value = cryptoJsAesDecrypt($_SESSION['paranoiaPWD'], $value);   // paranoia decrypt
  $fields_string .= $key.'='.$value.'&';
}
rtrim($fields_string, '&');

$url = cryptoJsAesDecrypt($_SESSION['paranoiaPWD'], $obj['target']); // get url to call
$ch = curl_init();                          // open connection

//echo $url . '?' . $fields_string;

// set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($obj));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);       // body only
curl_setopt($ch, CURLOPT_COOKIE, $strCookie);         // seesion in cookie
//curl_setopt($ch,CURLOPT_USERAGENT, $useragent);
//curl_setopt($ch, CURLOPT_HEADER, TRUE);

echo curl_exec($ch);             // execute post and pass throught
curl_close($ch);                 // close connection


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
