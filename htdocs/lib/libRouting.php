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

function showLocksPage(){
   switch(getMenuPath('2')){
      case 'list':
         showLockListPage();
         break;
      case 'show':
         showLockDetailsPage(getMenuPath('3'));
         break;
      case 'edit':
         showLockEditPage(getMenuPath('3'));
         break;
      default:
         showLockListPage();
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
         break;
      case 'history':
         showPersonHistoryPage();
         break;
      case (preg_match('[0-9]+', getMenuPath('2')) ? true : false) :
         showPersonDetailsPage(getMenuPath('2'));
         break;
      default:
         showPersonListPage();
   }
}
