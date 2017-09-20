<?php session_start(); ?>
<?php include './inc/language-prep.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="./inc/style.css" />
  <!-- postilotta core -->
  <script src="general.js"></script>
</head>
<body>
<?php include 'module-head.php'; ?>
<h1>Trusted Security</h1>
<div class="txt">
  <p><i>Infographic coming soon.</i></p>
  <p>
    <b>In a nutshell:</b><br>
    You don't have to trust us, that we do what we promise. Our code is open source, so everyone can verify this,
     and hundreds of geeks and IT-professionals have already done thad (soon).
   </p>
   <table align="center">
     <tr>
       <td align="right"><img src="pics/agpl_black_70.png"></td>
       <td><p>All of our source code is free software<br>under AGPL3 license.</p></td>
     </tr>
     <tr>
       <td><p>All of our source code is open source<br>and available here on <a href="https://github.com/seilermarcus/postilotta" target="_blank"><u>GitHub</u></a>.</p></td>
       <td><a href="https://github.com/seilermarcus/postilotta" target="_blank"><img src="pics/github_70.png"></a></td>
       <tr>
         <td align="right"><a href="https://tree.taiga.io/project/marcusuwezo-postilotta/kanban"><img src="pics/taiga_io_80.png"></a></td>
         <td>All of our requirements and development process management<br>is open for participation here on <a href="https://tree.taiga.io/project/marcusuwezo-postilotta/kanban" target="_blank"><u>taiga.io</u></a></td>
       </tr>
     </table>
  <p>
    We can do this without risking system integrity, because we practice <i>privacy by design</i> and don't rely on <i>security through obscurity</i>.
  </p>
</div>
<?php include 'module-banner-small.php'; ?>
<?php include 'module-footer.php'; ?> </footer></p>
<script>
  checkParaOn();      // Paranoia mode
  checkLang();        // Prepare for multilanguage
</script>
</body>
</html>
