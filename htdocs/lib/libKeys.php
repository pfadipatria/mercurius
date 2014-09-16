<?php

function showKeysPage(){
   switch(getMenuPath('2')){
      case 'list':
         showKeyListPage();
         break;
      case 'show':
         showKeyDetailsPage(getMenuPath('3'));
         break;
      case 'edit':
         showKeyEditPage(getMenuPath('3'));
         break;
      default:
         showKeyListPage();
   }
}

function showKeyListPage(){
   echo getHeader('keys', 'list');
   // echo '<br><p>Hier ist eine &Uuml;bersicht aller Schl&uumlssel.</p>';
   printKeyList();
   echo getFooter();
}

function printKeyList(){
   $result = '';

   echo '<table cellpadding="5" cellspacing="0">';
   echo '<tr>
      <td>id</td>
      <td>Code</td>
      <td>Status</td>
      <td>Besitzer</td>
      <td>Comment</td>
      </tr>';
   $query = '
      SELECT
         doorkey.id,
         code,
         doorkeystatus.name AS statusname,
         doorkey.comment AS keycomment,
         doorperson.name AS owner
         FROM doorkey
         LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
         LEFT JOIN doorperson ON (doorkey.owner = doorperson.id )
         ORDER BY code
         ';
   // $query = 'select * from doorkey limit 10';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/keys/show/' . $row['id'] . '\';" style="cursor: zoom-in">
         <td>' . $row['id'] . '</td>
         <td>' . $row['code'] . '</td>
         <td>' . $row['statusname'] . '</td>
         <td>' . $row['owner'] . '</td>
         <td>' . $row['keycomment'] . '</td>
         </tr>';
   }

   echo '</table>';
   // return $result;
}

function showKeyDetailsPage($keyId = '0'){
   echo getHeader('keys', '');
   echo '<p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p>';
   printKeyDetails($keyId);
   echo '<br><a href="/keys/edit/' . $keyId . '">Bearbeiten</a><br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><hr><h3>Berechtigungen:</h3><br>';
   printKeyPermissions($keyId);
   echo '<br><h3>Sperren auf T&uuml;ren:</h3><br>';
   printKeyDenials($keyId);
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   echo getFooter();
}

function showKeyEditPage($keyId = '0'){
   echo getHeader('keys', '');
   echo '<br>';
   printKeyEdit($keyId);
   echo '<br><br><hr><br><h3>Berechtigungen:</h3><br>';
   printKeyPermissions($keyId);
   echo '<br>';
   echo getFooter();
}

function printKeyDetails($keyId = '0'){

   $query = "
      SELECT
         doorkey.id,
         elnumber,
         code,
         type,
         doorkeycolor.name AS colorname,
         doorkeystatus.name AS statusname,
         doorkeymech.bezeichung AS bezeichung,
         doorperson.name AS owner,
         doorperson.id AS ownerid,
         doorperson.uid AS owneruid,
         doorkey.comment AS keycomment,
         communication,
         lastupdate
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
         $com = 'Ja';
      } else if ( $row['communication'] == '0' ) {
         $com = 'Nein';
      } else {
         $com = 'unknown value';
      }
      echo '<h2>' . $row['code'] . ' - ' . $row['owner'] . '</h2>
         <table cellpadding="5" cellspacing="0">
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">id</td><td>' . $row['id'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">ElNumber</td><td>' . $row['elnumber'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Code</td><td>' . $row['code'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Farbe</td><td>' . $row['colorname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Status</td><td>' . $row['statusname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Bezeichnung</td><td>' . $row['bezeichung'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Kommentar</td><td>' . $row['keycomment'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Besitzer</td><td onclick="/person/show/' . $row['ownerid'] . '" style="cursor: pointer">' . $row['owner'] . ' (' . $row['owneruid'] . ')</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Kommunikation</td><td>' . $com . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Typ</td><td>' . $row['type'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Letztes Update</td><td>' . $row['lastupdate'] . '</td></tr>
         </table>';
   }
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
         lastupdate
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
         <tr><td align="right">Letztes Update</td><td>' . $row['lastupdate'] . '</td></tr>
         <tr></tr>
         <tr><td><input type="button" name="back" value=" Abbrechen " onclick="goBack()"></td><td><input type="submit" value=" Speichern "></td></form>';
   }

   echo '</table>';
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
