<?php

$data = $_REQUEST['c'];
//$data = 'cpk3hvyIE95OVAatMCZwWEfIuOvBY+XpOa82nzAYMnx8xwbDR+ksfxmkItlEXd1Xqxqge4efpFUTuVAig1Rt0xNw5wOyP/rcMpe/vbeiEkhuRJ3yFwwaqzgwCC+eQcHWDfy9iMGasUZY4umMwgpvmCIF3k6S9SV+PheVxyXCbck=';

$text = '';
$key = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDlOJu6TyygqxfWT7eLtGDwajtNFOb9I5XRb6khyfD1Yt3YiCgQ
WMNW649887VGJiGr/L5i2osbl8C9+WJTeucF+S76xFxdU6jE0NQ+Z+zEdhUTooNR
aY5nZiu5PgDB0ED/ZKBUSLKL7eibMxZtMlUDHjm4gwQco1KRMDSmXSMkDwIDAQAB
AoGAfY9LpnuWK5Bs50UVep5c93SJdUi82u7yMx4iHFMc/Z2hfenfYEzu+57fI4fv
xTQ//5DbzRR/XKb8ulNv6+CHyPF31xk7YOBfkGI8qjLoq06V+FyBfDSwL8KbLyeH
m7KUZnLNQbk8yGLzB3iYKkRHlmUanQGaNMIJziWOkN+N9dECQQD0ONYRNZeuM8zd
8XJTSdcIX4a3gy3GGCJxOzv16XHxD03GW6UNLmfPwenKu+cdrQeaqEixrCejXdAF
z/7+BSMpAkEA8EaSOeP5Xr3ZrbiKzi6TGMwHMvC7HdJxaBJbVRfApFrE0/mPwmP5
rN7QwjrMY+0+AbXcm8mRQyQ1+IGEembsdwJBAN6az8Rv7QnD/YBvi52POIlRSSIM
V7SwWvSK4WSMnGb1ZBbhgdg57DXaspcwHsFV7hByQ5BvMtIduHcT14ECfcECQATe
aTgjFnqE/lQ22Rk0eGaYO80cc643BXVGafNfd9fcvwBMnk0iGX0XRsOozVt5Azil
psLBYuApa66NcVHJpCECQQDTjI2AQhFc1yRnCU/YgDnSpJVm1nASoRUnU8Jfm3Oz
uku7JUXcVpt08DFSceCEX9unCuMcT72rAQlLpdZir876
-----END RSA PRIVATE KEY-----';
/*
$resp = openssl_private_decrypt(base64_decode($data), $text, $key, OPENSSL_SSLV23_PADDING);
echo '<br>text: ' . $text;
*/
//---------------------------------------------------------------------------
/*
$hash_code = hash('sha256' , 'nope' );
if ($data == $hash_code){
  echo 'yes';
} else {
  echo 'no';
}
*/
//---------------------------------------------------------------------------
include 'inc/settings.inc';

//$mid = $_REQUEST["mid"];
//$to = $_REQUEST["to"];

$mid = 914480793;
$to = '9';
$arr = array('data' => null, 'pub' => null);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // get Message from DB
    $stmt = $conn->prepare("SELECT Recipient, Date, State, Content, ReturnPubKey, ReturnLink FROM Message Where MsgID='". $mid ."'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr['data'] = $result;
    echo 'data:<br>' . json_encode($result) . '<br>';

    // get pub key from DB
    $stmt = $conn->prepare("SELECT PubKey FROM Inbox Where Address='". $to ."'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr['pub'] = $result;
    echo 'pub:<br>' . json_encode($result) . '<br>';

} catch(PDOException $e){
  echo $sql . "<br>" . $e->getMessage();
}
$conn = null;

// Send upfront-hash to client
$thingsToCome = json_encode($arr);
$upfrontHash = hash('sha256', $thingsToCome);

echo 'hash: <br>' . $upfrontHash;

?>
