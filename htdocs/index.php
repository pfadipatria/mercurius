<?php

include 'lib/libAll.php';

if (loggedIn()){
   switch(getMenuPath()) {
      case 'keys':
         showKeysPage();
         break;
      case 'locks':
         showLocksPage();
         break;
      default:
         showStartPage();
   }
} else {
   showLoginPage();
}

?>
