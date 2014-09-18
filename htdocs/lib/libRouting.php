<?php

function routing($loggedIn = false){
    if($loggedIn){
       switch(getMenuPath()) {
          case 'keys':
             showKeysPage();
             break;
          case 'locks':
             showLocksPage();
             break;
          case 'person':
             showPersonPage();
             break;
          default:
             showStartPage();
       }
    } else {
       showLoginPage();
    }
}
