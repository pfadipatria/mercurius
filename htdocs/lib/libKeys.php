<?php

function showKeysPage(){
   switch(getMenuPath('2')){
      case 'list':
         showKeyListPage();
         break;
      case 'show':
         showKeyDetailsPage(getMenuPath('3'));
         break;
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
   $query = 'select doorkey.id,elnumber,code,doorkeycolor.name AS colorname,doorkeytatus.name AS statusname,doorkeymech.bezeichung AS bezeichung,comment from doorkey JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id ) JOIN doorkeytatus ON (doorkey.status = doorkeytatus.id) JOIN doorkeymech ON (doorkey.mechnumber = doorkeymech.id ) LIMIT 10';
   // $query = 'select * from doorkey limit 10';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">
         <td>' . $row['id'] . '</td>
         <td>' . $row['elnumber'] . '</td>
         <td>' . $row['code'] . '</td>
         <td>' . $row['colorname'] . '</td>
         <td>' . $row['statusname'] . '</td>
         <td>' . $row['bezeichung'] . '</td>
         <td>' . $row['comment'] . '</td>
         <td><a href="/keys/show/' . $row['id'] . '">Show</a></td>
         </tr>';
   }

   echo '</table>';
   // return $result;
}

function showKeyDetailsPage($keyId = '0'){
   echo getHeader('keys', '');
   printKeyDetails($keyId);
   echo getFooter();
}

function printKeyDetails($keyId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "select doorkey.id,elnumber,code,doorkeycolor.name AS colorname,doorkeytatus.name AS statusname,doorkeymech.bezeichung AS bezeichung,comment from doorkey JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id ) JOIN doorkeytatus ON (doorkey.status = doorkeytatus.id) JOIN doorkeymech ON (doorkey.mechnumber = doorkeymech.id ) WHERE doorkey.id = '" . $keyId . "'";
   error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td>id</td><td>' . $row['id'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td>id</td><td>' . $row['elnumber'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td>id</td><td>' . $row['code'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td>id</td><td>' . $row['colorname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td>id</td><td>' . $row['statusname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td>id</td><td>' . $row['bezeichung'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td>id</td><td>' . $row['comment'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td>id</td><td><a href="/keys/show/' . $row['id'] . '">Show</a></td></tr>
         ';
   }

   echo '</table>';
}

?>
