<?php

function showKeysPage(){
   switch(getMenuPath('2')){
      case 'list':
         showKeyListPage();
         break;

   }
}

function showKeyListPage(){
   echo getHeader('keys', 'lislt');
   echo '<br><p>Hier ist eine &Uunl;bersicht aller Schl&uumlssel.</p>';

   echo getFooter();
}

?>
