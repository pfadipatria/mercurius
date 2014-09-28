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

   $history = new \SKeyManager\Repository\HistoryRepository;

   $personView = array(
     'person' => $person
   );

   $keyView = array(
      'keys' => $person->getKeys()
   );

   $historyView = array(
      'story' => $history->getByPersonId($person->getId())
   );

   $view = array(
      'title' => $person->getName(),
      'person' => render($personView, 'person_entry'),
      'keys' => render($keyView, 'person_keylist'),
      'history' => render($historyView, 'history_list')
   );

   return render($view, 'person_layout');
}

function showPersonEditPage($personId = '0'){
   global $activeUserId;

   $view = array(
      'header' => getHeader('person', ''),
      'footer' => getFooter()
   );

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $history = new \SKeyManager\Entity\History();
      $message = '';
      $id = null;
      if (array_key_exists('id',$_POST)) {
         $id = $_POST['id'];
      }
      $person = new SKeyManager\Entity\Person($id);
      try {
         $history->setComment('Person erstellt');
         if (array_key_exists('id',$_POST)) {
            $person->load();
            $history->setComment('Person aktualisiert');
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
         $history->setPersonId($person->getId())->setAuthorId($activeUserId)->save();
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
   global $activeUserId;

   $deletable = false;

   $person = new \SKeyManager\Entity\Person($personId);
   $person->load();

   $view = array(
      'header' => getHeader('person', $personId, 'delete'),
      'footer' => getFooter()
   );


   if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['id'] && $_POST['confirm'] == true) {
      $personId = $_POST['id'];
      $message = '';
      $name = '';
      $history = new \SKeyManager\Entity\History();
      $person = new SKeyManager\Entity\Person($personId);
      try {
         $person->load();
         $name = $person->getName();
         $history->setPersonId($person->getId())->setAuthorId($activeUserId)->setComment('Person wurde gelöscht: '.$name.' ('.$person->getId().')');
         $result = $person->delete();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! '.$name.' wurde gelöscht.');
         $history->save();
         $view['body'] = getPersonList();
         $view['header'] = getHeader('person');
      } else {
         $view['danger'] = _('Fehler! Der Eintrag konnte nicht gelöscht werden.'.$message);
         $view['body'] = getPersonDetails($person);
         $view['header'] = getHeader('person', $personId);
      }
   } else {
      // Check for conditions to be true for person deletion
      // The person must not own any keys
      $person->getKeys() ? $deletable = false : $deletable = true;
      if ($deletable) {
         $deleteView = array(
            'personDetails' => render(array('person' => $person), 'person_entry'),
            'person' => $person,
         );
         $view['body'] = render($deleteView, 'person_delete');
      } else {
         $view['danger'] = sprintf(_('%s can not be deleted (he probably still owns keys).'), $person->getName());
         $view['body'] = getPersonDetails($person);
      }
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

?>
