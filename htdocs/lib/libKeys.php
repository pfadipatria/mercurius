<?php

function showKeysPage(){
   switch(getMenuPath('2')){
      case 'list':
         showKeyListPage();
         break;

   }
}

function showKeyListPage(){
   echo getHeader('keys', 'list');
   echo '<br><p>Hier ist eine &Uuml;bersicht aller Schl&uumlssel.</p>';
   echo getKeyList();
   echo getFooter();
}

function getKeyList(){
   $result = '';

   $result .= '<table>';
   // $query = 'select doorkey.id,elnumber,code,doorkeycolor.name AS colorname,doorkeytatus.name AS statusname,doorkeymech.bezeichung AS bezeichung,comment from doorkey JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id ) JOIN doorkeytatus ON (doorkey.status = doorkeytatus.id) JOIN doorkeymech ON (doorkey.mechnumber = doorkeymech.id ) LIMIT 10';
   $query = 'select * from doorkey';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      $result .= '<tr><td>' . $row . ' elnumber: ' . $row['elnumber'] . ' comment: ' . $row['comment'] . '</td></tr>';
   }

   $result .= '</table>';
   return $result;
}

?>
