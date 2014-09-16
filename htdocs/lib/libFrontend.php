<?php

function showStartPage () {
   global $uid, $userid;
   $menu = getMenuPath('1');
   if ( $menu  == '' ) {
      $menu = 'default';
   }
   echo getHeader($menu, getMenuPath('2'));
   // echo getHeader('locks', 'search');
   echo '<br><p>Willkommen, ' . $uid . ' (skm #' . $userid . '), bei der Schl&uuml;sselverwaltung.</p>';

   echo getFooter();
}

function showLoginPage () {
   echo getHeader();
   echo '<p>Du musst Dich einloggen!.</p>';

   echo getFooter();
}

function getMenuPath ($level = '1') {
   $result = array();

   $explodedRequest = explode('/', $_SERVER['REQUEST_URI']);
   $result[0] = $explodedRequest[0];
   $result[1] = $explodedRequest[1];
   $result[2] = $explodedRequest[2];
   $result[3] = $explodedRequest[3];

   return $result[$level];
}

function getHeader ($menu = '', $submenu = '') {
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

ul#Navigation a#Aktiv {
 border-color: white;
 border-left-color: black; border-top-color: black;
 color: blue; background-color: gray;
}

.normal { background-color: white }
.highlight { background-color: #cccccc }

</style>

<script>
function goBack() {
    window.history.back()
}
</script>

</head>
<body id="seite" bgcolor="#FFFFFF" link="black" vlink="black" alink="red">
   <table width="80%" border="0" align="center">
   <tr align="center"><td><h1>skeymanager - dev</h1></td></tr>' . getMenu($menu, $submenu) . '   <tr align="center"><td>
';

   return $result;

}

function getFooter () {
   $result = '
   </td></tr></table>';
   // $result .= '<hr><p><pre>' . var_dump($_SERVER) .'</pre></p>';
   $result .= '
</body>
</html>';

   return $result;
}

function getMenu($menu = '', $submenu = ''){
   $result = '';
   if ( $menu == '' ) {
      return $result;
   }

   $homeLink = $keysLink = $locksLink = $peopleLink = $historyLink = $helpLink = $listLink = $searchLink = $addLink = '';

   $activeLink = ' id="Aktiv" ';

   switch($menu) {
      case 'home':
         $homeLink = $activeLink;
         break;
      case 'keys':
         $keysLink = $activeLink;
         break;
      case 'locks':
         $locksLink = $activeLink;
         break;
      case 'people':
         $peopleLink = $activeLink;
         break;
      case 'history':
         $historyLink = $activeLink;
         break;
      case 'help':
         $helpLink = $activeLink;
         break;
   }

   switch($submenu) {
      case 'list':
         $listLink = $activeLink;
         break;
      case 'search':
         $searchLink = $activeLink;
         break;
      case 'add':
         $addLink = $activeLink;
         break;
   }

   $result .= '
      <tr align="center"><td><ul id="Navigation">
         <li><a href="/home"' . $homeLink . '>Home</a></li>
         <li><a href="/keys/list"' . $keysLink . '>Schl&uuml;ssel</a></li>
         <li><a href="/locks/list"' . $locksLink . '>Schl&ouml;sser</a></li>
         <li><a href="/people/list"' . $peopleLink . '>Personen</a></li>
         <li><a href="/history/list"' . $historyLink . '>Verlauf</a></li>
         <li><a href="/help"' . $helpLink . '>Hilfe</a></li>
      </ul>';

   switch($menu){
      case 'keys':
      case 'locks':
      case 'people':
         $result .= '
         <ul id="Navigation" style="border-top-color: silver;">
            <li><a href="/' . $menu .'/list"' . $listLink . '>Liste</a></li>
            <li><a href="/' . $menu .'/search"' . $searchLink . '>Suchen</a></li>
            <li><a href="/' . $menu .'/add"' . $addLink . '>Hinzuf&uuml;gen</a></li>
         </ul>';
      break;
      case 'history':
         $result .= '
         <ul id="Navigation" style="border-top-color: silver;">
            <li><a href="/' . $menu .'/list"' . $listLink . '>Liste</a></li>
            <li><a href="/' . $menu .'/search"' . $searchLink . '>Suchen</a></li>
         </ul>';
      break;
   }

   $result .= '
      </td></tr>';

   return $result;
}
?>
