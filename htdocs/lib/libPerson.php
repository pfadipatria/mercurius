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
   echo getHeader('person', 'list');
   printPeopleList();
   echo getFooter();
}

function printPersonList(){

   echo '<table cellpadding="5" cellspacing="0">';
   echo '<tr>
      <td>id</td>
      <td>Name</td>
      <td>uid</td>
      <td>uidNumber</td>
      <td>mbdId</td>
      </tr>';
   $query = '
      SELECT
         id
         name,
         uid,
         uidnumber,
         mdbid
         FROM doorperson
         ORDER BY name
         ';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/person/show/' . $row['id'] . '\';" style="cursor: zoom-in";>
         <td>' . $row['id'] . '</td>
         <td>' . $row['name'] . '</td>
         <td>' . $row['uid'] . '</td>
         <td>' . $row['uidnumber'] . '</td>
         <td>' . $row['mdbid'] . '</td>
         </tr>';
   }

   echo '</table>';
}

?>
