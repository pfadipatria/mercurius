<?php
include 'lib/libAll.php';

if (loggedIn()){
   showStartPage();
} else {
   showLoginPage();
}

?>
