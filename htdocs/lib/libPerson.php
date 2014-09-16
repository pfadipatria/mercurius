<?php

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
      default:
         showPersonListPage();
   }
}

function showPersonListPage(){
   echo getHeader('person', 'list');
   printPersonList();
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
      <td>Kommentar</td>
      </tr>';
   $query = '
      SELECT
         id,
         name,
         uid,
         uidnumber,
         mdbid,
         comment
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
         <td>' . $row['comment'] . '</td>
         </tr>';
   }

   echo '</table>';
}

function showPersonDetailsPage($personId = '0'){
   echo getHeader('person', '');
   echo '<p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p>';
   printPersonDetails($personId);
   echo '<br><a href="/person/edit/' . $personId . '">Bearbeiten</a><br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><hr><h3>Schl&uuml;ssel:</h3><br>';
   printPersonKeys($personId);
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   echo getFooter();
}

function showPersonEditPage($personId = '0'){
   echo getHeader('person', '');
   echo '<br>';
   printPersonAdd();
   echo '<br>';
   echo getFooter();
}

function showPersonAddPage(){
   echo getHeader('person', 'add');
   echo '<br>';
   printPersonAdd();
   echo '<br>';
   echo getFooter();
}

function showPersonSearchPage(){
   echo getHeader('person', 'search');
   echo '<br>';
   printPersonSearch();
   echo '<br>';
   echo getFooter();
}

function printPersonDetails($personId = '0'){

   $query = "
      SELECT
         id,
         name,
         uid,
         uidnumber,
         mdbid,
         comment
         FROM doorperson
         WHERE id = '" . $personId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<h2>' . $row['name'] . '</h2>
         <table cellpadding="5" cellspacing="0">
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">id</td><td>' . $row['id'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Name</td><td>' . $row['name'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">uid</td><td>' . $row['uid'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">uidNumber</td><td>' . $row['uidnumber'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">mdbId</td><td>' . $row['mdbid'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Kommentar</td><td>' . $row['comment'] . '</td></tr>
         </table>';
   }
}

function printPersonKeys($personId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "
      SELECT
         doorkey.id AS keyid,
         doorkey.code AS keycode,
         doorkey.comment AS keycomment
         FROM doorperson
         LEFT JOIN doorkey ON (doorperson.id = doorkey.owner)
         WHERE doorperson.id = '" . $personId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/keys/show/' . $row['keyid'] . '\';" style="cursor: zoom-in">
               <td>MC ' . $row['keycode'] . '</td>
               <td>' . $row['keycomment'] . '</td>
            </tr>
         ';
   }

   echo '</table>';
}

function printPersonEdit($personId = '0'){

   $query = "
      SELECT
         id,
         name,
         uid,
         uidnumber,
         mdbid,
         comment
         FROM doorperson
         WHERE id = '" . $personId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<form action="/person/show/' . $personId . '" method="post"><h2>' . $row['name'] . '</h2>
         <table cellpadding="5" cellspacing="0">
         <tr><td align="right">id</td><td>' . $row['id'] . '</td></tr>
         <tr><td align="right">Name</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['name'] . '"></td></tr>
         <tr><td align="right">uid</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['uid'] . '" readonly ></td></tr>
         <tr><td align="right">uidNumber</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['uidnumber'] . '" readonly ></td></tr>
         <tr><td align="right">mdbId</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['mdbid'] . '"></td></tr>
         <tr><td align="right">Kommentar</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['comment'] . '"></td></tr>
         <tr></tr>
         <tr><td><input type="button" name="back" value=" Abbrechen " onclick="goBack()"></td><td><input type="submit" value=" Speichern "></td></form>
         </table>';
   }
}

function printPersonAdd(){

    echo '<form action="/person/add" method="post"><h2>Person Hinzuf&uuml;gen</h2>
        <table cellpadding="5" cellspacing="0">
        <tr><td align="right">id</td><td>&sim; ' . getNextId('doorperson') . '</td></tr>
        <tr><td align="right">Name</td><td><input name="comment" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="right">uid</td><td><input name="comment" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="right">uidNumber</td><td><input name="comment" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="right">mdbId</td><td><input name="comment" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="right">Kommentar</td><td><input name="comment" type="text" size="30" maxlength="30"></td></tr>
        <tr></tr>
        <tr><td><input type="button" name="back" value=" Abbrechen " onclick="goBack()"></td><td><input type="submit" value=" Hinzuf&uuml;gen "></td></form>
        </table>';
}

function printPersonSearch(){

    echo '<form action="/person/search" method="get"><h2>Person suchen</h2>
        <table cellpadding="5" cellspacing="0">
        <tr><td align="center"><input name="search" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="center"><input type="submit" value=" Suchen "></td></form>
        </table>';

    echo '<form><h2>Person suchen</h2>
        <table cellpadding="5" cellspacing="0">
        <tr><td align="center"><input name="query" id="query" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="center"><input type="submit" value=" Suchen " onclick="document.location = \'document.getElementById(\'query\').value\';"></td>
        <tr><td><a href="javascript:void(0)" onClick="document.location = \'document.getElementById(\'query\').value\';">Search</a></td></tr></form>
        </table>';
}

?>
