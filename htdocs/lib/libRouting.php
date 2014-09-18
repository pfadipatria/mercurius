<?php

/**
 * handle routing
 *
 * route request and check for logged in users
 *
 * @param boolean $loggedIn is the user logged in
 *
 * @return void
 */
function routing($loggedIn = false){
    $menuPath = 'login';

    if($loggedIn){
        $menuPath = getMenuPath();
    }

   switch($menuPath) {
      case 'keys':
         showKeysPage();
         break;
      case 'locks':
         showLocksPage();
         break;
      case 'person':
         showPersonPage();
         break;
      case 'login':
         showLoginPage();
         break;
      default:
         showStartPage();
   }
}

function showPersonPage(){
   switch(getMenuPath('2')){
      case 'list':
         showPersonListPage();
         break;
      case 'show':
         showPersonDetailsPage(getMenuPath('3'));
         break;
      case 'edit':
         showPersonEditPage(getMenuPath('3'));
         break;
      case 'add':
         showPersonAddPage();
         break;
      case 'search':
         showPersonSearchPage();
      case 'history':
         showPersonHistoryPage();
         break;
      default:
         showPersonListPage();
   }
}
