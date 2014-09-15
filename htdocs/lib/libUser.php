<?php

function loggedIn() {
   $result = false;
   global $userid;

   if (isset($_SERVER['REMOTE_USER'])){
      $uid = $_SERVER['REMOTE_USER'];
      if (!uidExists($uid)){
         addUser($uid);
      }

      $userid = getIdFromUid($uid);
      $result = true;
   }
   return $result;
}

function uidExists($uid) {
   $result = false;

   $query = "select count(*) from doorperson where uid = '" . $uid . "';";
   $con = openDb();
   $dbresult = queryDb($con, $query);
	$row = mysqli_fetch_row($dbresult)
   if ( $row[0] == 1 ) $result = true;
   
   return $result;
}

?>
