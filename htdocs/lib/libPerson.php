<?php

function showPersonListPage(){

   $people = new \SKeyManager\Repository\PersonRepository;

   $view = array(
        'header' => getHeader('person', 'list'),
        'body' => getPersonList($people),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

function showPersonDetailsPage($personId = '0'){

   $person = new \SKeyManager\Entity\Person($personId);
   $person->load();

   $view = array(
      'header' => getHeader('person', ''),
      'body' => getPersonDetails($person),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

function showPersonEditPage($personId = '0'){
   $view = array(
      'header' => getHeader('person', ''),
      'footer' => getFooter()
   );

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $message = '';
      $id = null;
      if (array_key_exists('id',$_POST)) {
         $id = $_POST['id'];
      }
      $person = new SKeyManager\Entity\Person($id);
      try {
         if (array_key_exists('id',$_POST)) {
            $person->load();
         }
         $person->setName($_POST['name']);
         $result = $person->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! Der Eintrag wurde aktualisiert.');
         $view['body'] = getPersonDetails($person->getId());
      } else {
         $view['danger'] = _('Fehler! Der Eintrag konnte nicht aktualisiert werden.'.$message);
         $view['body'] = getPersonEdit($person->getId());
      }
   } else {
      if ($personId === '0') {
         $view['body'] = getPersonAdd();
      } else {
         $view['body'] = getPersonEdit($personId);
      }
   }

   echo render($view, 'layout');
}

function getPersonList($people = null){

    $people = new \SKeyManager\Repository\PersonRepository;

    $view = array(
        'people' => $people->getAll()
    );

    return render($view, 'person_list');
}

function getPersonDetails($person = null){

   $personView = array(
     'person' => $person
   );

   $keyView = array(
      'keys' => $person->getKeys()
   );

   $view = array(
      'person' => render($personView, 'person_entry'),
      'keys' => render($keyView, 'person_keylist')
   );

   return render($view, 'person_layout');
}

function getPersonKeys($personId = '0'){
   $keys = new \SKeyManager\Repository\KeyRepository;

   list($rows, $locations) = $keys->getByPersonId($personId);

   $view = array(
      'title' => 'Schl&uuml;ssel',
      'rows' => $rows,
      'locations' => $locations
   );

   return render($view, 'person_keylist');
}

function getPersonEdit($personId = '0'){
   $person = new \SKeyManager\Entity\Person($personId);
   $person->load();

   $row = $person->getAll();
   $name = $person->getName();

   $view = array(
      'title' => $name,
      'row' => $row,
      'content' => array(
         'id' => array(
            'label' => 'ID',
            'value' => $row['id'],
            'editable' => True
            ),
         'name' => array(
            'label' => 'Name',
            'value' => $row['name'],
            'editable' => True
            ),
         'uid' => array(
            'label' => 'UID',
            'value' => $row['uid'],
            'editable' => True
            ),
         'uidnumber' => array(
            'label' => 'UidNumber',
            'value' => $row['uidnumber'],
            'editable' => True
            ),
         'mdbid' => array(
            'label' => 'mdbId',
            'value' => $row['mdbid'],
            'editable' => True
            ),
         'comment' => array(
            'label' => 'Kommentar',
            'value' => $row['comment'],
            'editable' => True
            ),
         'lastupdate' => array(
            'label' => 'lastUpdate',
            'value' => $row['lastupdate'],
            'editable' => False
            )
         ),
      'locations' => $locations
   );

   return render($view, 'editEntry');
}

function showPersonSearchPage(){
   echo getHeader('person', 'search');
   echo '<br>';
   printPersonSearch();
   echo '<br>';
   echo getFooter();
}

function showPersonHistoryPage(){
   echo getHeader('person', 'history');
   printPersonHistory();
   echo getFooter();
}

function getPersonAdd(){

   $view = array(
      'title' => 'Person hinzuf&uuml;gen',
      'row' => $row,
      'content' => array(
         'id' => array(
            'label' => 'ID',
            'value' => getNextId('doorperson'),
            'editable' => False
            ),
         'name' => array(
            'label' => 'Name',
            'value' => '',
            'editable' => True
            ),
         'uid' => array(
            'label' => 'UID',
            'value' => '',
            'editable' => True
            ),
         'uidnumber' => array(
            'label' => 'UidNumber',
            'value' => '',
            'editable' => True
            ),
         'mdbid' => array(
            'label' => 'mdbId',
            'value' => '',
            'editable' => True
            ),
         'comment' => array(
            'label' => 'Kommentar',
            'value' => '',
            'editable' => True
            )
         ),
      'locations' => $locations
   );

   return render($view, 'editEntry');
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

    if ($params['mode'] == 'update') {
       $oldquery = "
            SELECT
            id,
            name,
            uid,
            uidnumber,
            mdbid,
            comment
            FROM doorperson
            WHERE id = '" . $params['id'] ."'
            ";
            # @TODO Check if a similar user already exists
       $con = openDb();
       $dbresult = queryDb($con, $oldquery);
       $row = mysqli_fetch_assoc($dbresult);

       # Save the old data for history
       $hist['old'] = $row;
       $hist['id'] = $params['id'];
    }

    // if ($params['mode'] == 'add') $query .= " WHERE name = '" . $params['name'] . "' or uid = '" . $params['uid'] . "'";
    // if ($params['mode'] == 'update') $query .= " WHERE id = '" . $params['id'] . "'";

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
    foreach(array( 'name', 'uid', 'uidnumber', 'mdbid', 'comment') as $item){
        if ($params[$item] == '' || $params[$item] == '0' || empty($params[$item])) {
            if ($params['mode'] == 'update') $query .= ' , `' . $item . '` = NULL ';
        } else {
            if ($params['mode'] == 'add') $cols .= ', `' . $item . '`';
            if ($params['mode'] == 'add') $values .= ', "' . $params[$item] . '"';
            if ($params['mode'] == 'update') $query .= ' , `' . $item . '` = "' . $params[$item] . '" ';
        }
    }



    # Add the end of the query
    if ($params['mode'] == 'add') $query .= $cols . ') VALUES (' . $values . ')';
    if ($params['mode'] == 'update') $query .= ' WHERE `id` = "' . $params['id'] . '"';

    # Perfom the db add/update
    # error_log($query);
    $con = openDb();
    if (queryDb($con, $query)){
        // echo '<p style="color:green">OK, ' . $name . ' wurde aktualisiert</p>';
        $return = true;
        /* if ($params['mode'] == 'add') $hist['id'] = mysql_insert_id();
        $hist['new'] = $params;
        createPersonHistory($hist);
        */
    // } else {
        // echo '<p style="color:red">Fehler beim bearbeiten in die Datenbank!</p>';
    }

    return $return;
}

function createPersonHistory($params = array()){
   $return = false;

   /*
   echo '<pre>';
   var_dump($params);
   echo '</pre>';
   */

   $authorId = getIdFromUid($_SERVER['REMOTE_USER']);
   $query = 'INSERT INTO doorpersonhistory ';
   $cols = ' `person`, `author` ';
   $values = ' "' . $params['id'] . '", "' . $authorId . '" ';
   if(isset($params['old']))
   foreach($params['old'] as $item => $value){
      if ($params['new'][$item] != $value) {
         // echo 'Creating history for item ' . $item . ' as ' . $value . ' != ' . $params['new'][$item] . '!';
         $cols .= ' , `' . $item . '` ';
         $values .= ', "' . $value . '" ';
      }
   }
   $query .= ' ( ' . $cols . ') VALUES (' . $values . ')';
   error_log($query);
   $con = openDb();
   if ($dbresult = queryDb($con, $query)) $return = true;

   return $return;

}

function printPersonHistory($count = '10'){

   $query = '
      SELECT
         hist.id,
         user.name AS username,
         authors.name AS author,
         hist.name,
         hist.uid,
         hist.uidnumber,
         hist.mdbid,
         hist.comment,
         hist.date 
         FROM doorpersonhistory AS hist 
         LEFT JOIN doorperson AS authors ON (hist.author = authors.id) 
         LEFT JOIN doorperson AS user ON (hist.person = user.id)
         ORDER by hist.date DESC
         LIMIT ' . $count . '
      ';

   echo '<h2>Personen Verlauf</h2>
         <table cellpadding="5" cellspacing="0">
         <tr>
            <td>Date</td>
            <td>Author</td>
            <td>User</td>
            <td>Changes</td>
         </tr>';

   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '
         <tr>
         <td>' . $row['date'] . '</td>
         <td>' . $row['author'] . '</td>
         <td>' . $row['username'] . '</td>
         <td>';
         foreach(array('name', 'uid', 'uidnumber', 'mdbid', 'comment') as $item){
            if($row[$item] !== NULL) echo ' ' . $item . ' ';
         }
      echo '</td></tr>';
   }

   echo '</table>';
}

?>
