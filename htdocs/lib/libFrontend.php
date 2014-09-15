<?php

function showStartPage () {
   global $uid, $userid;
   echo getHeader($menu = 'all');
   echo '<p>Willkommen, ' . $uid . ' (skm #' . $userid . '), bei der Schl&uuml;sselverwaltung.</p>';

   echo getFooter();
}

function showLoginPage () {
   echo getHeader();
   echo '<p>Du musst Dich einloggen!.</p>';

   echo getFooter();
}

function getHeader ($menu = '') {
   $result = '<html>
<head>
<title>skeymanager</title>
<style type="text/css" id="bla">
a {
    text-decoration:none;
}

.label {
    font-size:1.5em;
}

.sublabel {

}

.labelinactive {
   font-size:1.5em;
	color:grey;

}

.sublabelinactive {
	color:grey;
}

</style>
</head>
<body id="seite" bgcolor="#FFFFFF" link="black" vlink="black" alink="red">
   <table width="80%" border="0">
   <tr align="center"><td><h1>skeymanager - dev</h1></td></tr>' . getMenu($menu) . '   <tr align="center"><td>
';

   return $result;

}

function getFooter () {
   $result = '
   </td></tr></table>
</body>
</html>';

   return $result;
}

function getMenu($menu = ''){
   $result = '';

   switch($menu){
      case 'all':
         $result = '
<tr align="center"><td><table cellspacing="10" cellpadding="10" border="1" frame="void"><tr align="center">
   <td><a>Home</a></td>
   <td><a href="info/">Info</a></td>
</tr></table></td></tr>';
      break;
   }

   return $result;
}
?>
