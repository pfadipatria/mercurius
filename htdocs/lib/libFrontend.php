<?php

function showStartPage () {
   // global $uid, $userid;
   // global $uid;
   global $activeUserId, $activeUid;
   $menu = getMenuPath('1');
   if ( $menu  == '' ) {
      $menu = 'default';
   }
   echo getHeader($menu, getMenuPath('2'));
   // echo getHeader('locks', 'search');
   echo '<br><p>Willkommen, ' . $activeUid . ' (skm #' . $activeUserId . '), bei der Schl&uuml;sselverwaltung.</p>';

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
   if(!empty($explodedRequest[1])) {
      $result[1] = $explodedRequest[1];
      } else {
      $result[1] = '';
   }
   if(!empty($explodedRequest[2])) {
      $result[2] = $explodedRequest[2];
      } else {
      $result[2] = '';
   }
   if(!empty($explodedRequest[3])) {
      $result[3] = $explodedRequest[3];
      } else {
      $result[3] = '';
   }
   if(!empty($explodedRequest[4])) {
      $result[4] = $explodedRequest[4];
      } else {
      $result[4] = '';
   }

   return $result[$level];
}

function getHeader ($menu = '', $submenu = '') {
   global $config;

   $view = array(
        'title' => $config['siteName'],
        'subTitle' => $config['siteSubTitle'],
        'pageHeadline' => isset($config['pageHeadline']) ? $config['pageHeadline'] : '',
        'menu' => getMenu($menu, $submenu)
   );

   return render($view, 'header');
}

function getFooter () {

   $view = array();

   return render($view, 'main_footer');
}

function getMenu($menu = '', $submenu = ''){

   $mainEntries = array(
      'key' => array('path' => '/key', 'name' => _('Schlüssel')),
      'lock' => array('path' => '/lock', 'name' => _('Schlösser')),
      'person' => array('path' => '/person', 'name' => _('Personen')),
      'history' => array('path' => '/history', 'name' => _('Verlauf')),
      'help' => array('path' => '/help', 'name' => _('Hilfe'))
   );

   $subEntries = array();
   if($menu == 'lock' || $menu == 'key' || $menu == 'person' ) {
      $subEntries = array(
      'list' => array('path' => '/list', 'name' => _('Liste')),
      'search' => array('path' => '/search', 'name' => _('Suchen')),
      'add' => array('path' => '/add', 'name' => _('Hinzufügen'))
      );
   }

   $view = array(
      'mainEntries' => $mainEntries,
      'mainActive' => $menu,
      'subEntries' => $subEntries,
      'subActive' => $submenu
   );

   return render($view, 'main_menu');
}

function getUsers(){
   $result = array();
   $sql = '
      SELECT
         id,
         name,
         uid,
         comment
      FROM person
      ORDER BY name
   ';
   $con = openDb();
   $dbresult = queryDb($con, $sql);
   $result[] = array('id' => '0', 'name' => _('No Holder'), 'uid' => '', 'comment' => '');
	while ($row = mysqli_fetch_assoc($dbresult)) {
      $result[] = array('id' => $row['id'], 'name' => $row['name'], 'uid' => $row['uid'], 'comment' => $row['comment']);
   }
   return $result;
}

function getKeyStatuses(){
   $result = array();
   $sql = '
      SELECT
         id,
         name
      FROM keystatus
      ORDER BY name
   ';
   $con = openDb();
   $dbresult = queryDb($con, $sql);
	while ($row = mysqli_fetch_assoc($dbresult)) {
      $result[] = array('id' => $row['id'], 'name' => $row['name']);
   }
   return $result;
}

function getLockStatuses(){
   $result = array();
   $sql = '
      SELECT
         id,
         name
      FROM lockstatus
      ORDER BY name
   ';
   $con = openDb();
   $dbresult = queryDb($con, $sql);
	while ($row = mysqli_fetch_assoc($dbresult)) {
      $result[] = array('id' => $row['id'], 'name' => $row['name']);
   }
   return $result;
}

function getColors(){
   $result = array();
   $result[] = array('id' => 0, 'colorid' => '0', 'name' => '- unknown -', 'display' => '000000');
   $sql = '
      SELECT
         id,
         colorid,
         name
      FROM keycolor
   ';
   $con = openDb();
   $dbresult = queryDb($con, $sql);
	while ($row = mysqli_fetch_assoc($dbresult)) {
      $result[] = array('id' => $row['id'], 'colorid' => $row['colorid'], 'name' => $row['name']);
   }
   return $result;
}

function getMechanics(){
   $result = array();
   $result[] = array('id' => 0, 'number' => '', 'description' => '', 'user' => ' unknown ');
   $sql = '
      SELECT
         id,
         number,
         description,
         user
      FROM keymech
   ';
   $con = openDb();
   $dbresult = queryDb($con, $sql);
   var_dump($dbresult);
	while ($row = mysqli_fetch_assoc($dbresult)) {
      $result[] = array('id' => $row['id'], 'number' => $row['number'], 'description' => sprintf('%04d',$row['description']), 'user' => $row['user']);
   }
   return $result;
}

function getCommunications($value = 'all'){
   $result = array(
      null => array('value' => 'null', 'name' => _('unknown'), 'color' => '#C0C0C0'),
      0 => array('value' => '0', 'name' => _('No'), 'color' => '#000000'),
      1 => array('value' => '1', 'name' => _('Yes'), 'color' => '#008000')
   );
   if($value === 'all') {
      return $result;
   }
   return $result[$value];
}

function getPermissionStatuses(){
   $result = array();
   $result[] = array('id' => 0, 'name' => '- removed -');
   $sql = '
      SELECT
         id,
         name
      FROM permissionstatus
   ';
   $con = openDb();
   $dbresult = queryDb($con, $sql);
	while ($row = mysqli_fetch_assoc($dbresult)) {
      $result[] = array('id' => $row['id'], 'name' => $row['name']);
   }
   return $result;
}

function getPermissionStatusesCss($class = 0){
   switch($class) {
      case '1':
         $result = ' alert-info ';
         break;
      case '2':
         $result = ' alert-success ';
         break;
      case '3':
         $result = ' alert-warning ';
         break;
      case '4':
         $result = ' alert-warning ';
         break;
      default:
         $result = '';
         break;
   }
   return $result;
}

function getLockPermissionStatusesCss($class = 0){
   switch($class) {
      case '1':
         $result = ' alert-info ';
         break;
      case '2':
         $result = ' alert-danger ';
         break;
      case '3':
         $result = ' alert-warning ';
         break;
      case '4':
         $result = ' alert-warning ';
         break;
      default:
         $result = '';
         break;
   }
   return $result;
}

function getVenues(){
   $result = array();
   $sql = '
      SELECT
         id,
         name
      FROM place
      ORDER BY id
   ';
   $con = openDb();
   $dbresult = queryDb($con, $sql);
	while ($row = mysqli_fetch_assoc($dbresult)) {
      $result[] = array('id' => $row['id'], 'name' => $row['name']);
   }
   return $result;
}

?>
