<?php

function showStartPage () {
   // global $uid, $userid;
   // global $uid;
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
   $view = array(
        'menu' => getMenu($menu, $submenu),
        'title' => 'skeymanager - dev'
   );

   return render($view, 'header');
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

   $activeLink = ' class="active"  ';
   $passiveLink = ' ';
   $homeLink = $keysLink = $locksLink = $personLink = $historyLink = $helpLink = $listLink = $searchLink = $addLink = $passiveLink;

   switch($menu) {
      case '':
         $homeLink = $activeLink;
         break;
      case 'keys':
         $keysLink = $activeLink;
         break;
      case 'key':
         $keysLink = $activeLink;
         break;
      case 'locks':
         $locksLink = $activeLink;
         break;
      case 'lock':
         $locksLink = $activeLink;
         break;
      case 'person':
         $personLink = $activeLink;
         break;
      case 'history':
         $historyLink = $activeLink;
         break;
      case 'help':
         $helpLink = $activeLink;
         break;
   }

   switch($submenu) {
      case 'search':
         $searchLink = $activeLink;
         break;
      case 'add':
         $addLink = $activeLink;
         break;
   }

$result = '

<h1>SKeyMangager - DEV</h1>
<div class="navbar masthead">
   <ul class="nav nav-justified">
      <li ' . $keysLink . '><a href="/key">Schl&uuml;ssel</a></li>
      <li ' . $locksLink . '><a href="/lock">Schl&ouml;sser</a></li>
      <li ' . $personLink . '><a href="/person">Personen</a></li>
      <li ' . $historyLink . '><a href="/history">Verlauf</a></li>
      <li ' . $helpLink . '><a href="/help">Help</a></li>
   </ul>
';

   switch($menu){
      case 'keys':
      case 'locks':
      case 'person':
         $result .= '
   <ul class="nav nav-justified">
      <li><a href="/' . $menu .'/list"' . $listLink . '>Liste</a></li>
      <li><a href="/' . $menu .'/search"' . $searchLink . '>Suchen</a></li>
      <li><a href="/' . $menu .'/add"' . $addLink . '>Hinzuf&uuml;gen</a></li>
      <li><a href="/' . $menu .'/history">Verlauf</a></li>
   </ul>
';
      break;
   }

$result .= '</div>';

echo '
';
   return $result;
}
?>
