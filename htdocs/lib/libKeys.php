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

   while ($key = getKeyFromDb()){
      $result .= $key;

   }

   return $result;
}

?>
