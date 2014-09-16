<?php

function showPersonPage(){
   switch(getMenuPath('2')){
      case 'list':
         showPersonListPage();
         break;
      default:
         showPersonListPage();
   }
}

function showPersonListPage(){
   echo getHeader('people', 'list');
   printPeopleList();
   echo getFooter();
}

function printPersonList(){

   echo '<table cellpadding="5" cellspacing="0">';
   echo '<tr>
      <td>id</td>
      <td>SC</td>
      <td>Heim</td>
      <td>Bezeichnung</td>
      <td>Status</td>
      <td>Kommentar</td>
      </tr>';
   $query = '
      SELECT
         doorlock.id AS lockid,
         number,
         doorlock.name AS lockname,
         sc,
         doorplace.name AS heim,
         doorlockstatus.name AS statusname
         FROM doorlock
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
         ORDER BY sc
         ';
   // $query = 'select * from doorkey limit 10';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/locks/show/' . $row['lockid'] . '\';" style="cursor: zoom-in";>
         <td>' . $row['lockid'] . '</td>
         <td>' . $row['sc'] . '</td>
         <td>' . $row['heim'] . '</td>
         <td>' . $row['lockname'] . ' (' . $row['number'] . ')</td>
         <td>' . $row['statusname'] . '</td>
         <td>' . $row['comment'] . '</td>
         </tr>';
   }

   echo '</table>';
}

?>
