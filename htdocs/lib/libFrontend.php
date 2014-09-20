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
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">SKeyMangager - DEV</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li ' . $homeLink . '><a href="/">Home</a></li>
        <li class="dropdown">
          <a href="#" ' . $keysLink . ' class="dropdown-toggle" data-toggle="dropdown">Schl&uuml;ssel <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/key/">Liste</a></li>
            <li><a href="/key/add">Hinzuf&uuml;gen</a></li>
            <li><a href="/key/search">Suchen</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="/lock" ' . $locksLink . ' class="dropdown-toggle" data-toggle="dropdown">Schl&ouml;sser <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/lock/add">Hinzuf&uuml;gen</a></li>
            <li><a href="/lock/search">Suchen</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="/person" ' . $personLink . ' class="dropdown-toggle" data-toggle="dropdown">Personen <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/person/add">Hinzuf&uuml;gen</a></li>
            <li><a href="/person/search">Suchen</a></li>
          </ul>
        </li>
        <li ' . $historyLink . '><a href="/history">Verlauf</a></li>

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li ' . $helpLink . '><a href="/help">Help</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
';

   return $result;
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
