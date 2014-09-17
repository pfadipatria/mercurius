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
   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
     echo '<p>modifiyng person ';
     // print_r($_POST);
     echo '</p>';
    $addArray['mode'] = 'update';
    $addArray['id'] = $personId;
    foreach($_POST as $item => $value){
        if ($value != '' || $value != '0' || !empty($value)) $addArray[$item] = $value;
    }
    modifiyDbPerson($addArray);
   }

   // Should we return to the view of this person (on success?)?
   printPersonEdit($personId);
   echo '<br>';
   echo getFooter();
}

function showPersonAddPage(){
   echo getHeader('person', 'add');
   echo '<br>';
   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
     echo '<p>adding person ';
     // print_r($_POST);
     echo '</p>';
    addPerson($_POST['name'], $_POST['uid'], $_POST['uidnumber'], $_POST['mdbid'], $_POST['comment']);

   }
   // Should we return to the form or to the newly created person?
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
      echo '<form action="/person/edit/' . $personId . '" method="post"><h2>' . $row['name'] . '</h2>
         <table cellpadding="5" cellspacing="0">
         <tr><td align="right">id</td><td>' . $row['id'] . '</td></tr>
         <tr><td align="right">Name</td><td><input name="name" type="text" size="30" maxlength="30" value="' . $row['name'] . '"></td></tr>
         <tr><td align="right">uid</td><td><input name="uid" type="text" size="30" maxlength="30" value="' . $row['uid'] . '" readonly ></td></tr>
         <tr><td align="right">uidNumber</td><td><input name="uidnumber" type="text" size="30" maxlength="30" value="' . $row['uidnumber'] . '" readonly ></td></tr>
         <tr><td align="right">mdbId</td><td><input name="mdbid" type="text" size="30" maxlength="30" value="' . $row['mdbid'] . '"></td></tr>
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
        <tr><td align="right">Name</td><td><input name="name" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="right">uid</td><td><input name="uid" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="right">uidNumber</td><td><input name="uidnumber" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="right">mdbId</td><td><input name="mdbid" type="text" size="30" maxlength="30"></td></tr>
        <tr><td align="right">Kommentar</td><td><input name="comment" type="text" size="30" maxlength="30"></td></tr>
        <tr></tr>
        <tr><td><input type="button" name="back" value=" Abbrechen " onclick="goBack()"></td><td><input type="submit" value=" Hinzuf&uuml;gen "></td></form>
        </table>';
}

function printPersonSearch(){

    echo '<h2>Person suchen</h2>
          <table cellpadding="5" cellspacing="0">
          <tr><td align="center"><input name="query" id="query" type="text" size="30" maxlength="30"></td></tr>
          <tr><td align="center"><a href="javascript:void(0)" onClick="document.location = \'/person/search/\' + document.getElementById(\'query\').value;">Suchen</a></td>
          </table>';
}

function addPerson($name = '', $uid = '', $uidnumber = '', $mdbid = '', $comment = ''){
    $return = false;

    # @TODO Check if at least the name (or uid?) is given

    # @TODO Check if there are similar users and warn
    $query = "
      SELECT
         id,
         name,
         uid,
         uidnumber
         FROM doorperson
         WHERE name = '" . $name . "' or uid = '" . $uid . "'
      ";
    $con = openDb();
    $dbresult = queryDb($con, $query);
    // $rows = mysqli_num_rows($dbresult);
    if(mysqli_num_rows($dbresult) > 0){
        echo '<p style="color:red">Fehler: Der Benutzer ' . $name . ' (uid: ' . $uid .') existiert schon!</p>';
        # @TODO return the form with the prefilled values from the last try
    } else {
        // echo '<p>Fuege hinzu: ' . $name . ' ' . $uid . ' ' . $uidnumber . ' ' . $mdbid . ' ' . $comment . '</p>';
        $query = "
            INSERT INTO
                doorperson (";
        $cols = '`name`';
        $values = '"' . $name . '"';
        foreach(array( 'uid', 'uidnumber', 'mdbid', 'comment') as $item){
            if (${$item} != '' and ${$item} != '0') {
                $cols .= ', `' . $item . '`';
                $values .= ', "' . ${$item} . '"';
            }
        }
        $query .= $cols . ') VALUES(' . $values . ')';
        $con = openDb();
        if (queryDb($con, $query)){
            echo '<p style="color:green">OK, ' . $name . ' wurde hinzugef&uuml;gt!</p>';
            $return = true;
        } else {
            echo '<p style="color:red">Fehler beim hinzugef&uuml;gen in die Datenbank!</p>';
        }

    }

    return $return;
}

function modifiyDbPerson($params = array()){
    $return = false;

    # We we need at least an id for updates or an name for adds
    if ( empty($params['mode']) || $params['mode'] == 'update' && empty($params['id']) || $params['mode'] == 'add' && empty($params['name']) ) return false;

    $query = "
         SELECT
         id,
         name,
         uid,
         uidnumber
         FROM doorperson";
         # @TODO Check if a similar user already exists
         if ($params['mode'] == 'add') $query .= " WHERE name = '" . $params['name'] . "' or uid = '" . $params['uid'] . "'";
         if ($params['mode'] == 'update') $query .= " WHERE id = '" . $params['id'] . "'";
    $con = openDb();
    $dbresult = queryDb($con, $query);
    $row = mysqli_fetch_row($dbresult);

    # @TODO (If adding?) check if this name (or uid?) already exists

    # Add the beginnen of the query
    if ($params['mode'] == 'add') {
        $query = 'INSERT INTO doorperson ';
        $cols = ' (`lastupdate` ';
        $values = ' ( NOW() ';
        }
    if ($params['mode'] == 'update') $query = 'UPDATE doorperson SET `lastupdate` = NOW() ';

    # @TODO Check if the name is not NULL

    # Add / Update the fields
    # @TODO (How) can fields be emptied (set to NULL)?
    foreach(array( 'name', 'uid', 'uidnumber', 'mdbid', 'comment') as $item){
        if ($params[$item] != '' || $params[$item] != '0' || !empty($params[$item])) {
            if ($params['mode'] == 'add') $cols .= ', `' . $item . '`';
            if ($params['mode'] == 'add') $values .= ', "' . $params[$item] . '"';
            if ($params['mode'] == 'update') $query .= ' , `' . $item . '` = "' . $params[$item] . '" ';
        } else {
            echo 'The ' . $item . ' must not be empty or 0!';
            return false;
        }
    }

    # Add the end of the query
    if ($params['mode'] == 'add') $query .= $cols . ') VALUES (' . $values . ')';
    if ($params['mode'] == 'update') $query .= ' WHERE `id` = "' . $params['id'] . '"';

    # Perfom the db add/update
    error_log($query);
    $con = openDb();
    if (queryDb($con, $query)){
        echo '<p style="color:green">OK, ' . $name . ' wurde aktualisiert</p>';
        $return = true;
        # @TODO create history
    } else {
        echo '<p style="color:red">Fehler beim bearbeiten in die Datenbank!</p>';
    }

    return $return;
}

?>
