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
   // echo getKeyList();
  $query = 'select doorkey.id,elnumber,code,doorkeycolor.name AS colorname,doorkeytatus.name AS statusname,doorkeymech.bezeichung AS bezeichung,comment from doorkey JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id ) JOIN doorkeytatus ON (doorkey.status = doorkeytatus.id) JOIN doorkeymech ON (doorkey.mechnumber = doorkeymech.id ) LIMIT 10';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      var_dump($row);
   }
   echo getFooter();
}

function getKeyList(){
   $result = '';

   while ($key = getKeyFromDb()){
      $result .= $key;

   }

   return $result;
}

?>
