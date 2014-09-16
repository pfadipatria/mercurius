<?php

include 'lib/libAll.php';

if (loggedIn()){
   switch(getMenuPath()) {
      case 'keys':
         showKeysPage();
         break;
      default:
         showStartPage();
   }
} else {
   showLoginPage();
}

?>
