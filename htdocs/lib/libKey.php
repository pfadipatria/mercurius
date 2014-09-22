<?php

function showKeyListPage(){

   $keys = new \SKeyManager\Repository\KeyRepository;

   $view = array(
        'header' => getHeader('keys', 'list'),
        'body' => getKeyList($keys),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

function getKeyList($keys = null){

    $view = array(
        'keys' => $keys->getAll()
    );

    return render($view, 'key_list');
}

function showKeyDetailsPage($keyId = '0'){

   $key = new \SKeyManager\Entity\Key($keyId);
   $key->load();

   $view = array(
      'header' => getHeader('key', $keyId),
      'body' => getKeyDetails($key),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

function getKeyDetails($key = null){

   $allows = new \SKeyManager\Repository\PermissionRepository;
   $denied = new \SKeyManager\Repository\PermissionRepository;

   $keyView = array(
     'key' => $key
   );

   $permissionView = array(
      'title' => _('Permissions to Locks'),
      'locks' => $allows->getAllowedByKeyId($key->getId())
   );

   $denialView = array(
      'title' => _('Denied by Locks'),
      'locks' => $denied->getDeniedByKeyId($key->getId())
   );

   $view = array(
      'key' => render($keyView, 'key_entry'),
      'permissions' => render($permissionView, 'key_locklist'),
      'denials' => render($denialView, 'key_locklist')
   );

   return render($view, 'key_layout');
}

function showKeyEditPage($keyId = '0'){
   $view = array(
      'header' => getHeader('key', ''),
      'footer' => getFooter()
   );

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $message = '';
      $id = null;
      if (array_key_exists('id',$_POST)) {
         $id = $_POST['id'];
      }
      $key = new SKeyManager\Entity\Key($id);
      try {
         if (array_key_exists('id',$_POST)) {
            $key->load();
         }
         $key->setElNumber($_POST['elnumber']);
         $key->setCode($_POST['code']);
         $key->setStatusId($_POST['statusid']);
         $key->setType($_POST['type']);
         $key->setColorId($_POST['colorid']);
         $key->setComment($_POST['comment']);
         $result = $key->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! Der Eintrag wurde aktualisiert.');
         $newKey = new \SKeyManager\Entity\Key($key->getId());
         $newKey->load();
         $view['body'] = getKeyDetails($newKey);
      } else {
         $view['danger'] = _('Fehler! Der Eintrag konnte nicht aktualisiert werden.'.$message);
         $view['body'] = getKeyEdit($key);
      }
   } else {
      if ($keyId === '0') {
         $view['body'] = getKeyEdit();
      } else {
         $key = new \SKeyManager\Entity\Key($keyId);
         $key->load();
         $view['body'] = getKeyEdit($key);
      }
   }

   echo render($view, 'layout');
}

function getKeyEdit($key = null){
   $hasData = false;
   if ($key !== null) {
      $hasData = true;
      $view['title'] = $key->getName();
   } else {
      $key = new \SKeyManager\Entity\Key();
      $view['title'] = _('Add a new Key');
   }

   $view['hasData'] = $hasData;
   $view['key'] = $key;

   return render($view, 'key_edit');
}

function showKeyDeletePage($keyId = '0'){

   $key = new \SKeyManager\Entity\Key($keyId);
   $key->load();

   $view = array(
      'header' => getHeader('key', $keyId),
      'danger' => _('Do not delete keys at this point, just mark them as dismissed.'),
      'body' => getKeyDetails($key),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

///////////////////////////////////////////////////////////

function oldshowKeyEditPage($keyId = '0'){
   echo getHeader('keys', '');
   echo '<br>';
   printKeyEdit($keyId);
   echo '<br><br><hr><br><h3>Berechtigungen:</h3><br>';
   printKeyPermissions($keyId);
   echo '<br>';
   echo getFooter();
}

function printKeyEdit($keyId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "
      SELECT
         doorkey.id,
         elnumber,
         code,
         type,
         color,
         status,
         doorkeycolor.name AS colorname,
         doorkeystatus.name AS statusname,
         doorkeymech.bezeichung AS bezeichung,
         doorperson.name AS owner,
         doorperson.uid AS owneruid,
         doorkey.comment AS keycomment,
         communication,
         doorkey.lastupdate AS keyupdate
         FROM doorkey
         LEFT JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id )
         LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
         LEFT JOIN doorkeymech ON (doorkey.mech = doorkeymech.id )
         LEFT JOIN doorperson ON (doorkey.owner = doorperson.id )
         WHERE doorkey.id = '" . $keyId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      if ( $row['communication'] == '1' ){
         $com = ' checked ';
      } else {
         $com = '';
      }

         $colorChoose = '<select name="color" size="1" disabled>';
         $cquery = 'SELECT id, name FROM doorkeycolor';
         $ccon = openDb();
         $cdbresult = queryDb($ccon, $cquery);
	      while ($crow = mysqli_fetch_array($cdbresult)){
            $colorChoose .= '<option value="' . $crow['id'] . '"';
            if ($crow['id'] == $row['color']){
               $colorChoose .= ' selected ';
            }
            $colorChoose .= '>' . $crow['name'] . '</option>';
         }
         $colorChoose .= '</select>';

         $statusChoose = '<select name="status" size="1">';
         $squery = 'SELECT id, name FROM doorkeystatus';
         $scon = openDb();
         $sdbresult = queryDb($scon, $squery);
	      while ($srow = mysqli_fetch_array($sdbresult)){
            $statusChoose .= '<option value="' . $srow['id'] . '"';
            if ($srow['id'] == $row['status']){
               $statusChoose .= ' selected ';
            }
            $statusChoose .= '>' . $srow['name'] . '</option>';
         }
         $statusChoose .= '</select>';

      echo '<form action="/keys/show/' . $keyId . '" method="post">
         <tr><td align="right">id</td><td>' . $row['id'] . '</td></tr>
         <tr><td align="right">ElNumber</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['elnumber'] . '" readonly ></td></tr>
         <tr><td align="right">Code</td><td><b><input name="comment" type="text" size="30" maxlength="30" value="' . $row['code'] . '" readonly ></b></td></tr>
         <tr><td align="right">Farbe</td><td>' . $colorChoose . '</td></tr>
         <tr><td align="right">Status</td><td>' . $statusChoose . '</td></tr>
         <tr><td align="right">Bezeichnung</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['bezeichung'] . '" readonly ></td></tr>
         <tr><td align="right">Kommentar</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['keycomment'] . '"></td></tr>
         <tr><td align="right">Besitzer</td><td>' . $row['owner'] . '(' . $row['owneruid'] . ')</td></tr>
         <tr><td align="right">Kommunikation</td><td><input type="checkbox" name="zutat" value="communication" ' . $com . '></td></tr>
         <tr><td align="right">Typ</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['type'] . '" readonly ></td></tr>
         <tr><td align="right">Letztes Update</td><td>' . $row['keyupdate'] . '</td></tr>
         <tr></tr>
         <tr><td><input type="button" name="back" value=" Abbrechen " onclick="goBack()"></td><td><input type="submit" value=" Speichern "></td></form>';
   }

   echo '</table>';
}

function getKeyPermissions($keyId = '0'){

    $locks = new \SKeyManager\Repository\LockRepository;
    list($rows, $locations) = $locks->getAllowedByKeyId($keyId);

    $view = array(
        'headers' => array (
            'Id',
            'SC',
            'Heim',
            'Name',
        ),
        'rows' => $rows,
        'locations' => $locations
    );

    return render($view, 'list');

    $key = new \SKeyManager\Entity\KeyEntity($keyId);
    list($rows, $locations) = $key->getPermissions();

    $view = array(
        'title' => 'Berechtigungen',
        'headers' => array (
            'Id',
            'SC',
            'Heim',
            'Name'
        ),
        'rows' => $rows,
        'locations' => $locations
    );

    return render($view, 'list');
}

function printKeyPermissions($keyId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "
      SELECT
         doorkey_opens_lock.lock AS lockid,
         doorlock.sc AS locksc,
         doorplace.name AS heim,
         doorlock.name AS lockname
         FROM doorkey_opens_lock
         LEFT JOIN doorlock ON (doorkey_opens_lock.lock = doorlock.id )
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         WHERE doorkey_opens_lock.key = '" . $keyId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/locks/show/' . $row['lockid'] . '\';" style="cursor: zoom-in">
               <td>SC ' . $row['locksc'] . '</td>
               <td>' . $row['heim'] . '</td>
               <td>' . $row['lockname'] . '</td>
            </tr>
         ';
   }

   echo '</table>';
}

function printKeyDenials($keyId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "
      SELECT
         doorlock_locks_key.lock AS lockid,
         doorlock.sc AS locksc,
         doorplace.name AS heim,
         doorlock.name AS lockname
         FROM doorlock_locks_key
         LEFT JOIN doorlock ON (doorlock_locks_key.lock = doorlock.id )
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         WHERE doorlock_locks_key.key = '" . $keyId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/locks/show/' . $row['lockid'] . '\';" style="cursor: zoom-in">
               <td>SC ' . $row['locksc'] . '</td>
               <td>' . $row['heim'] . '</td>
               <td>' . $row['lockname'] . '</td>
            </tr>
         ';
   }

   echo '</table>';
}

?>
