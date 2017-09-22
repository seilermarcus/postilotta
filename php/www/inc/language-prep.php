<?php
  $ln = null;
//    $file = basename(__FILE__, '.php');     // get script name
  $file = basename($_SERVER['SCRIPT_NAME'], '.php');     // get name of called script to be translated

  // use parameter lang, or session value, or browser language as default
  if ( (isset($_REQUEST['lang'])) && ($_REQUEST['lang'] != 'undefined') ) {
      $lang = $_REQUEST['lang'];
      $_SESSION['plang'] = $lang;
  }else{
    if(isset($_SESSION['plang'])) {
      $lang = $_SESSION['plang'];
    }else{
      $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
      $_SESSION['plang'] = $lang;
    }
  }
//  clearstatcache();
  // load lang file, or en as default
  $jsn = @file_get_contents('language/'. $file .'_'. $lang .'.json')
        or $jsn = file_get_contents('language/'. $file .'_en.json');

  $ln = json_decode($jsn, true);
?>
