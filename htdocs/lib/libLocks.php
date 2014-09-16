<?php

function showLocksPage(){
   switch(getMenuPath('2')){
      case 'list':
         showLockListPage();
         break;
      case 'show':
         showLockDetailsPage(getMenuPath('3'));
         break;
      case 'edit':
         showLockEditPage(getMenuPath('3'));
         break;
      default:
         showLockListPage();
   }
}

function showLockListPage(){
   echo getHeader('locks', 'list');
   printLockList();
   echo getFooter();
}

function printLockList(){

   echo '<table cellpadding="5" cellspacing="0">';
   echo '<tr>
      <td>id</td>
      <td>SC</td>
      <td>Heim</td>
      <td>Bezeichnung</td>
      <td>Status</td>
      <td>Kommentar</td>
      </tr>';
   $query = '
      SELECT
         doorlock.id AS lockid,
         number,
         doorlock.name AS lockname,
         sc,
         doorplace.name AS heim,
         doorlockstatus.name AS statusname
         FROM doorlock
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
         ORDER BY sc
         ';
   // $query = 'select * from doorkey limit 10';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/locks/show/' . $row['lockid'] . '\';" style="cursor: zoom-in";>
         <td>' . $row['lockid'] . '</td>
         <td>' . $row['sc'] . '</td>
         <td>' . $row['heim'] . '</td>
         <td>' . $row['lockname'] . ' (' . $row['number'] . ')</td>
         <td>' . $row['statusname'] . '</td>
         <td>' . $row['comment'] . '</td>
         </tr>';
   }

   echo '</table>';
}

function showLockDetailsPage($lockId = '0'){
   echo getHeader('locks', '');
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   printLockDetails($lockId);
   echo '<br><a href="/locks/edit/' . $lockId . '">Bearbeiten</a><br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br><hr><br><h3>Sperren:</h3><br>';
   printLockDenials($lockId);
   echo '<br><h3>Berechtigungen auf Schl&uumlssel:</h3><br>';
   printLockPermissions($lockId);
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   echo getFooter();
}

function showLockEditPage($lockId = '0'){
   echo getHeader('locks', '');
   echo '<br>';
   printLockEdit($lockId);
   echo '<br><br><hr><br><h3>Sperren:</h3><br>';
   printLockDenials($lockId);
   echo '<br>';
   echo getFooter();
}

function printLockDetails($lockId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "

      SELECT
         doorlock.id AS lockid,
         number,
         doorlock.name AS lockname,
         sc,
         doorplace.name AS heim,
         doorlockstatus.name AS statusname,
         hasbatteries,
         lastupdate,
         type,
         position,
         comment
         FROM doorlock
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
         WHERE doorlock.id = '" . $lockId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      if ( $row['hasbatteries'] == '1' ){
         $bat = 'Ja';
      } else if ( $row['hasbatteries'] == '0' ) {
         $bat = 'Nein';
      } else {
         $bat = 'n/a';
      }
      echo '
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">id</td><td>' . $row['lockid'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">SC</td><td>' . $row['sc'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Heim</td><td>' . $row['heim'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Bezeichnung</td><td>' . $row['lockname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Nummer</td><td>' . $row['number'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Status</td><td>' . $row['statusname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Elektronik</td><td>' . $bat . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Typ</td><td>' . $row['type'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Position</td><td>' . $row['position'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Kommentar</td><td>' . $row['comment'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Letztes Update</td><td>' . $row['lastupdate'] . '</td></tr>
         ';
   }

   echo '</table>';
}

function printLockEdit($lockId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "

      SELECT
         doorlock.id AS lockid,
         number,
         doorlock.name AS lockname,
         sc,
         doorplace.name AS heim,
         doorlockstatus.name AS statusname,
         hasbatteries,
         lastupdate,
         type,
         position,
         comment
         FROM doorlock
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
         WHERE doorlock.id = '" . $lockId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      if ( $row['hasbatteries'] == '1' ){
         $bat = ' checked ';
      } else {
         $bat = '';
      }

         $placeChoose = '<select name="place" size="1">';
         $pquery = 'SELECT id, name FROM doorplace';
         $pcon = openDb();
         $pdbresult = queryDb($pcon, $pquery);
	      while ($prow = mysqli_fetch_array($pdbresult)){
            $placeChoose .= '<option value="' . $prow['id'] . '"';
            if ($prow['id'] == $row['heim']){
               $placeChoose .= ' selected ';
            }
            $placeChoose .= '>' . $prow['name'] . '</option>';
         }
         $placeChoose .= '</select>';

      echo '<form action="/keys/show/' . $lockId . '" method="post">
         <tr><td align="right">id</td><td>' . $row['lockid'] . '</td></tr>
         <tr><td align="right">SC</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['sc'] . '" readonly ></td></tr>
         <tr><td align="right">Heim</td><td>' . $placeChoose . '</td></tr>
         <tr><td align="right">Bezeichnung</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['lockname'] . '"></td></tr>
         <tr><td align="right">Nummer</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['number'] . '"></td></tr>
         <tr><td align="right">Status</td><td>' . $row['statusname'] . '</td></tr>
         <tr><td align="right">Elektronik</td><td><input type="checkbox" name="zutat" value="communication" ' . $bat . '></td></tr>
         <tr><td align="right">Typ</td><td>' . $row['type'] . '</td></tr>
         <tr><td align="right">Position</td><td>' . $row['position'] . '</td></tr>
         <tr><td align="right">Kommentar</td><td>' . $row['comment'] . '</td></tr>
         <tr><td align="right">Letztes Update</td><td>' . $row['lastupdate'] . '</td></tr>
         <tr></tr>
         <tr><td><input type="button" name="back" value=" Abbrechen " onclick="goBack()"></td><td><input type="submit" value=" Speichern "></td></form>';
   }

   echo '</table>';
}

function printLockPermissions($lockId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "
      SELECT
         doorkey_opens_lock.key AS keyid,
         doorkey.code AS keycode,
         doorperson.name AS personname
         FROM doorkey_opens_lock
         LEFT JOIN doorkey ON (doorkey_opens_lock.key = doorkey.id )
         LEFT JOIN doorperson ON (doorkey.owner = doorperson.id)
         WHERE doorkey_opens_lock.lock = '" . $lockId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/keys/show/' . $row['keyid'] . '\';" style="cursor: zoom-in">
               <td>MC ' . $row['keycode'] . '</td>
               <td>' . $row['personname'] . '</td>
            </tr>
         ';
   }

   echo '</table>';
}

function printLockDenials($lockId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "
      SELECT
         doorlock_locks_key.key AS keyid,
         doorkey.code AS keycode,
         doorperson.name AS personname
         FROM doorlock_locks_key
         LEFT JOIN doorkey ON (doorlock_locks_key.key = doorkey.id )
         LEFT JOIN doorperson ON (doorkey.owner = doorperson.id)
         WHERE doorlock_locks_key.lock = '" . $lockId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/keys/show/' . $row['keyid'] . '\';" style="cursor: zoom-in">
               <td>MC ' . $row['keycode'] . '</td>
               <td>' . $row['personname'] . '</td>
            </tr>
         ';
   }

   echo '</table>';
}

?>
