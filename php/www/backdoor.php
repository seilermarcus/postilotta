<?php session_start();?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <style>
  body {
    /*background-color: grey;*/
    /*color: #A4A4A4;*/
  }
  a {color: #6495ED;}
  </style>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <!-- SJCL -->
  <script type="text/javascript" src="./sjcl/sjcl.js"></script>
  <!--- CryptoJS AES Libraries --->
  <script src="./cryptojs/aes.js"></script>
  <script src="./cryptojs/enc-base64-min.js"></script>
  <!-- postilotta core -->
  <script src="general.js"></script>
</head>
<body>
  <?php
  include 'inc/settings.inc';
  try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT Passphrase, Watchword FROM Paranoia Where PLink='". $_SERVER['QUERY_STRING'] ."'";
      $stmt = $conn->query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Store Passphrase in PHP session
      $_SESSION['paranoiaPWD'] = $result[0]["Passphrase"];
      $_SESSION['paranoiaLink'] = $_SERVER['QUERY_STRING'];

      // Encrypt Watchword with Passphrase
      $encrypted = cryptoJsAesEncrypt($result[0]["Passphrase"], $result[0]["Watchword"]);

      echo '<p hidden id="hash">'. $encrypted .'</p>';
    }
    catch(PDOException $e)
    {
    echo '<p hidden id="hash">'. $e->getMessage() . "</p>";
    }
    $conn = null;

    /**
    * Encrypt value to a cryptojs compatiable json encoding string
    *
    * @param mixed $passphrase
    * @param mixed $value
    * @return string
    */
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
  ?>
<?php include 'module-head.php'; ?>
<div id="container" class="container-midi">
  <h1 id="p_h2"><?php echo $ln['header'];?></h1>
  <div class="txt">
    <form id="theForm">
      <?php echo $ln['passphrase'];?><br>
      <div class="biginput">
        <input type="password" name="p_pf" id="p_pf" size="20"><br>
        <br>
      </div>
      <button type="button" class="button" onclick="activateParanoia(ipf.value)"><?php echo $ln['enter'];?></button>
    </form>
    <br>
    <p id="out"></p>
    <p id="err" class="err"></p>
    <p id="inf" class="inf"></p>
  </div>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?>
<script>
  checkLang();        // Prepare for multilanguage
  document.getElementById('logoframe').className += " para";
  document.getElementById('logo').src = 'pics/schwarzerumschlag_p_96.jpg';
  clearSessionSoft(); // paranoia vars excluded
  var ipf = document.forms["theForm"]["p_pf"];
</script>
</body>
</html>
