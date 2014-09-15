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

ul#Navigation {
 margin: 0; padding: 0.8em;
 text-align: center;
 border: 1px solid black;
 background-color: silver;
}

ul#Navigation li {
 list-style: none;
 display: inline;
 margin: 0.4em; padding: 0;
}

ul#Navigation a, ul#Navigation span {
 padding: 0.2em 1em;
 text-decoration: none; font-weight: bold;
 border: 1px solid black;
 border-left-color: white; border-top-color: white;
 color: blue; background-color: #ccc;
}


ul#Navigation a:hover, ul#Navigation span {
 border-color: white;
 border-left-color: black; border-top-color: black;
 color: blue; background-color: gray;
}

</style>
</head>
<body id="seite" bgcolor="#FFFFFF" link="black" vlink="black" alink="red">
   <table width="80%" border="0" align="center">
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
<tr align="center"><td><ul id="Navigation">
   <li><a>Home</a></li>
   <li><a href="info/">Info</a></li>
</ul></td></tr>';
      break;
   }

   return $result;
}
?>
