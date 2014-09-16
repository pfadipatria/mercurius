<?php

function showKeysPage(){
   switch(getMenuPath('2')){
      case 'list':
         showKeyListPage();
         break;
      case 'show':
         showKeyDetailsPage(getMenuPath('3'));
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
      <td>elNumber</td>
      <td>Code</td>
      <td>Color</td>
      <td>Status</td>
      <td>Bezeichnung</td>
      <td>Comment</td>
      </tr>';
   $query = '
      select
         doorkey.id,
         elnumber,
         code,
         doorkeycolor.name AS colorname,
         doorkeytatus.name AS statusname,
         doorkeymech.bezeichung AS bezeichung,
         comment
         from doorkey
         JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id )
         JOIN doorkeytatus ON (doorkey.status = doorkeytatus.id)
         JOIN doorkeymech ON (doorkey.mechnumber = doorkeymech.id )';
   // $query = 'select * from doorkey limit 10';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/keys/show/' . $row['id'] . '\';" style="cursor: zoom-in";>
         <td>' . $row['id'] . '</td>
         <td>' . $row['elnumber'] . '</td>
         <td>' . $row['code'] . '</td>
         <td>' . $row['colorname'] . '</td>
         <td>' . $row['statusname'] . '</td>
         <td>' . $row['bezeichung'] . '</td>
         <td>' . $row['comment'] . '</td>
         </tr>';
   }

   echo '</table>';
   // return $result;
}

function showKeyDetailsPage($keyId = '0'){
   echo getHeader('keys', '');
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   printKeyDetails($keyId);
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   echo getFooter();
}

function printKeyDetails($keyId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "select doorkey.id,elnumber,code,doorkeycolor.name AS colorname,doorkeytatus.name AS statusname,doorkeymech.bezeichung AS bezeichung,comment from doorkey JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id ) JOIN doorkeytatus ON (doorkey.status = doorkeytatus.id) JOIN doorkeymech ON (doorkey.mechnumber = doorkeymech.id ) WHERE doorkey.id = '" . $keyId . "'";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">id</td><td>' . $row['id'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">ElNumber</td><td>' . $row['elnumber'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Code</td><td><b>' . $row['code'] . '</b></td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Farbe</td><td>' . $row['colorname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Status</td><td>' . $row['statusname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Bezeichnung</td><td>' . $row['bezeichung'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Kommentar</td><td>' . $row['comment'] . '</td></tr>
         ';
   }

   echo '</table>';
}

?>
