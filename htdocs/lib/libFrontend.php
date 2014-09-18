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

   ob_start();
   include __DIR__.'/../templates/header.phtml';
   $result = ob_end_flush();

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

   $homeLink = $keysLink = $locksLink = $personLink = $historyLink = $helpLink = $listLink = $searchLink = $addLink = '';

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
         <li><a href="/person/list"' . $personLink . '>Personen</a></li>
         <li><a href="/history/list"' . $historyLink . '>Verlauf</a></li>
         <li><a href="/help"' . $helpLink . '>Hilfe</a></li>
      </ul>';

   switch($menu){
      case 'keys':
      case 'locks':
      case 'person':
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
