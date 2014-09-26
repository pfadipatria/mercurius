<?php

function loggedIn() {
   $result = false;
   global $activeUserId, $activeUid;

   if (isset($_SERVER['REMOTE_USER'])){
      $uid = $_SERVER['REMOTE_USER'];
      if (!uidExists($uid)){
         if(addUser($uid)){
            // @TODO move this text inside the html tags...
            echo 'Your user has been added to the database';
         } else {
            echo 'Your user could not have been added to the database';
         }
      }

      $activeUserId = getIdFromUid($uid);
      $activeUid = $uid;
      $result = true;
   }
   return $result;
}

function uidExists($uid) {
   $result = false;

   $query = "select count(*) from person where uid = '" . $uid . "';";
   $count = queryValue($query);
   if ( $count == 1 ) {
      $result = true;
   }
   
   return $result;
}

function getIdFromUid($uid) {
   $result = 0;

   $query = "select id from person where uid = '" . $uid . "';";
   $result = queryValue($query);

   return $result;
}

function addUser($uid) {
   $name = ucfirst($uid);
   $comment = _('Created automatically');
   $sql = '
      INSERT INTO person
      SET
         uid = "'.$uid.'",
         name = "'.$name.'",
         comment = "'.$comment.'"
   ';

   $con = openDb();
   return queryDb($con, $sql);
}
