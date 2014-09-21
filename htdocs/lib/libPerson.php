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

function getPersonList($people = null){

    $people = new \SKeyManager\Repository\PersonRepository;

    $view = array(
        'people' => $people->getAll()
    );

    return render($view, 'person_list');
}

function showPersonDetailsPage($personId = '0'){

   $person = new \SKeyManager\Entity\Person($personId);
   $person->load();

   $view = array(
      'header' => getHeader('person', $personId),
      'body' => getPersonDetails($person),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
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
         $person->setUid($_POST['uid']);
         $person->setUidNumber($_POST['uidnumber']);
         $person->setMdbId($_POST['mdbid']);
         $person->setComment($_POST['comment']);
         $result = $person->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! Der Eintrag wurde aktualisiert.');
         $newPerson = new \SKeyManager\Entity\Person($person->getId());
         $newPerson->load();
         $view['body'] = getPersonDetails($newPerson);
      } else {
         $view['danger'] = _('Fehler! Der Eintrag konnte nicht aktualisiert werden.'.$message);
         $view['body'] = getPersonEdit($person);
      }
   } else {
      if ($personId === '0') {
         $view['body'] = getPersonEdit();
      } else {
         $person = new \SKeyManager\Entity\Person($personId);
         $person->load();
         $view['body'] = getPersonEdit($person);
      }
   }

   echo render($view, 'layout');
}

function getPersonEdit($person = null){
   $hasData = false;
   if ($person !== null) {
      $hasData = true;
      $view['title'] = $person->getName();
   } else {
      $person = new \SKeyManager\Entity\Person();
      $view['title'] = _('Add a new Person');
   }

   $view['hasData'] = $hasData;
   $view['person'] = $person;

   return render($view, 'person_edit');
}

function showPersonDeletePage($personId = '0'){
   $deletable = false;

   $person = new \SKeyManager\Entity\Person($personId);
   $person->load();

   // Check for conditions to be true for person deletion
   // The person must not own any keys
   $person->getKeys() ? $deletable = false : $deletable = true;

   $view = array(
      'header' => getHeader('person', $personId, 'delete'),
      'footer' => getFooter()
   );

   if ($deletable) {
      $view['body'] = '<p>Confirm user deletion</p>';
   } else {
      $view['danger'] = echo sprintf(_('%s can not be deleted (he probably still owns keys).'), $person->getName());
      $view['body'] = getPersonDetails($person);
   }

   echo render($view, 'layout');
}

function getPersonDelete($person = null){

   $deletable = false;
   $person->getKeys() ? $deletable = false : $deletable = true;

   $view = array(
      'personDetails' => render(array('person' => $person), 'person_entry'),
      'person' => $person,
      'deletable' => $deletable
   );

   return render($view, 'person_delete');
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

function printPersonSearch(){

    echo '<h2>Person suchen</h2>
          <table cellpadding="5" cellspacing="0">
          <tr><td align="center"><input name="query" id="query" type="text" size="30" maxlength="30"></td></tr>
          <tr><td align="center"><a href="javascript:void(0)" onClick="document.location = \'/person/search/\' + document.getElementById(\'query\').value;">Suchen</a></td>
          </table>';
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
