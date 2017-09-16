<?php
//    $file = basename(__FILE__, '.php');     // get script name
  $file = basename($_SERVER['SCRIPT_NAME'], '.php');     // get name of called script to be translated

  // use parameter lang, or browser language as default
  if (isset($_REQUEST[lang])) {
      $lang = $_REQUEST[lang];
  }else{
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  }
  clearstatcache();
  // load lang file, or en as default
  $jsn = @file_get_contents('language/'. $file .'_'. $lang .'.json')
        or $jsn = file_get_contents('language/'. $file .'_en.json');

  $ln = json_decode($jsn, true);

  // tell js which language was loaded
  //echo '<div hidden id="lang" value="'. $ln['lang'] .'">'.$ln['lang'].'</div>'; //TODO doesn't work! cache or what?
?>
