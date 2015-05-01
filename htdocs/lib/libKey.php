<?php

function showKeyListPage(){

   $keys = new \SKeyManager\Repository\KeyRepository;

   $view = array(
        'header' => getHeader('key', 'list'),
        'body' => getKeyList($keys),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

function getKeyList($keys = null){

    $view = array(
        'keys' => $keys->getAll()
    );

    return render($view, 'key_list');
}

function showKeyDetailsPage($keyId = '0'){

   $key = new \SKeyManager\Entity\Key($keyId);
   $key->load();

   $view = array(
      'header' => getHeader('key', $keyId),
      'body' => getKeyDetails($key),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

function getKeyDetails($key = null){

   $allows = new \SKeyManager\Repository\PermissionRepository;
   $denied = new \SKeyManager\Repository\PermissionRepository;

   $keyView = array(
     'key' => $key
   );

   $permissionView = array(
      'title' => _('Permissions to Locks'),
      'titleColor' => 'green',
      'keyLocation' => $key->getLocation(),
      'locks' => $allows->getAllowedByKeyId($key->getId())
   );

   $denialView = array(
      'title' => _('Denied by Locks'),
      'titleColor' => 'red',
      'keyLocation' => $key->getLocation(),
      'locks' => $denied->getDeniedByKeyId($key->getId())
   );

   $view = array(
      'key' => render($keyView, 'key_entry'),
      'permissions' => render($permissionView, 'key_locklist'),
      'denials' => render($denialView, 'key_locklist')
   );

   return render($view, 'key_layout');
}

function showKeyEditPage($keyId = '0'){
   global $activeUserId;

   $view = array(
      'header' => getHeader('key', ''),
      'footer' => getFooter()
   );

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $message = '';
      $history = new \SKeyManager\Entity\History();
      $id = null;
      if (array_key_exists('id',$_POST)) {
         $id = $_POST['id'];
      }
      $key = new SKeyManager\Entity\Key($id);
      try {
         $history->setComment('Schlüssel erstellt');
         if (array_key_exists('id',$_POST)) {
            $key->load();
            $history->setComment('Schlüssel aktualisiert');
         }
         $key->setElNumber($_POST['elnumber']);
         $key->setCode($_POST['code']);
         $key->setStatusId($_POST['statusid']);
         $key->setType($_POST['type']);
         $key->setColorId($_POST['colorid']);
         $key->setComment($_POST['comment']);
         $oldHolderId = $key->getHolderId();
         $key->setHolderId($_POST['holderid']);
         $key->setDHolderId($_POST['dholderid']);
         $key->setMechId($_POST['mechid']);
         $key->setCommunication($_POST['com']);
         $result = $key->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! Der Eintrag wurde aktualisiert.');
         $newKey = new \SKeyManager\Entity\Key($key->getId());
         $newKey->load();
            error_log('holder changed old: '.$oldHolderId.' new '.$newKey->getHolderId().' ');
         // Also add the person id to the history if the holder has changed
         if ($oldHolderId != $newKey->getHolderId()) {
            // Create history for the old holder
            $oldHistory = new \SKeyManager\Entity\History();
            $oldHistory->setKeyId($key->getId())->setPersonId($oldHolderId)->setComment(_('Removed holder'))->setAuthorId($activeUserId)->save();
            // Create history for the new key
            $historyPersonId = $key->getHolderId() ? $key->getHolderId() : $newKey->getHolderId();
            $history->setPersonId($historyPersonId);
         }
         $history->setKeyId($key->getId())->setAuthorId($activeUserId)->save();
         $view['body'] = getKeyDetails($newKey);
      } else {
         $view['danger'] = _('Fehler! Der Eintrag konnte nicht aktualisiert werden.'.$message);
         $view['body'] = getKeyEdit($key);
      }
   } else {
      if ($keyId === '0') {
         $view['body'] = getKeyEdit();
      } else {
         $key = new \SKeyManager\Entity\Key($keyId);
         $key->load();
         $view['body'] = getKeyEdit($key);
      }
   }

   echo render($view, 'layout');
}

function getKeyEdit($key = null){
   $hasData = false;
   if ($key !== null) {
      $hasData = true;
      $view['title'] = $key->getName();
   } else {
      $key = new \SKeyManager\Entity\Key();
      $view['title'] = _('Add a new Key');
   }

   $view['hasData'] = $hasData;
   $view['key'] = $key;

   return render($view, 'key_edit');
}

function showKeyAllowPage($keyId = '0'){

   $key = new \SKeyManager\Entity\Key($keyId);
   $key->load();

   $view = array(
      'header' => getHeader('key', $keyId),
      'body' => getKeyAllow($key),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

/**
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
function getKeyAllow($key = null){
   global $activeUserId;

   $view = array();

   // User contrib on http://php.net/manual/en/function.preg-grep.php
   function preg_grep_keys($pattern, $input, $flags = 0) {
       return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $permValues = preg_grep_keys('/^select/', $_POST);
      // var_dump($permValues);

      $message = '';
      $keyId = null;
      if (array_key_exists('keyid',$_POST)) {
         $keyId = $_POST['keyid'];
         $perms = new \SKeyManager\Repository\PermissionRepository;
         try {
            foreach($permValues as $lockId => $statusId){
               $result = $perms->setAllowPermission($keyId, substr($lockId, 6), 'allows', $statusId);
            }
         } catch (Exception $exception) {
            $result = false;
            $message = ' ('.$exception->getMessage().')';
         }

         if($result){
            $view['success'] = _('OK! Der Eintrag wurde aktualisiert.');
            $history = new \SKeyManager\Entity\History();
            $history->setKeyId($keyId)->setComment('Schlüssel Permission aktualisiert')->setAuthorId($activeUserId)->save();
         } else {
            $view['danger'] = _('Fehler! Der Eintrag konnte nicht aktualisiert werden.'.$message);
         }

      }
   }

   $locks = new \SKeyManager\Repository\LockRepository;
   $perms = new \SKeyManager\Repository\PermissionRepository;

   $lockLlist = array();
   foreach($locks->getAll() as $lock) {
      $lockList[$lock->getId()]['name'] = $lock->getFullName();
      $lockList[$lock->getId()]['lockid'] = $lock->getId();
      // @TODO getKeyAllowedOnLock should only return ONE permission entity
      $perm = $perms->getKeyAllowedOnLock($key->getId(), $lock->getId());
      $lockList[$lock->getId()]['permid'] = $perm ? $perm['0']->getId() : null;
      $lockList[$lock->getId()]['statusid'] = $perm ? $perm['0']->getStatusId() : null;
   }

   // var_dump($lockList);
   $permView = array(
      'keyid' => $key->getId(),
      'permlist' => $lockList
   );

   $view['title'] = sprintf(_('Change allowed locks on %s'), $key->getName());
   $view['perm'] = render($permView, 'perm_edit');


   return render($view, 'perm_layout');
}

function showKeyDeletePage($keyId = '0'){

   $key = new \SKeyManager\Entity\Key($keyId);
   $key->load();

   $view = array(
      'header' => getHeader('key', $keyId),
      'danger' => _('Do not delete keys at this point, just mark them as dismissed.'),
      'body' => getKeyDetails($key),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

function showKeyDeleteHolderPage($keyId = '0'){
   global $activeUserId;

   # @TODO check dependencies
   # $deletable = false;
   $deletable = true;

   $view = array(
      'header' => getHeader('key', $keyId),
      'footer' => getFooter()
   );



   if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['id'] && $_POST['confirm'] == true) {
      $history = new \SKeyManager\Entity\History();
      $message = '';
      $id = null;
      if ($keyId) {
         $id = $keyId;
      }
      $key = new SKeyManager\Entity\Key($id);
      try {
         $historyComment = 'Deleted holder';
         if ($keyId) {
            $key->load();
            $historyComment = sprintf(_('Deleted holder %s'), $key->getHolderName());
         }
         $history->setComment($historyComment);
         $key->setHolderId('');
         $result = $key->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! The holder has been removed');
         $history->setKeyId($key->getId())->setAuthorId($activeUserId)->save();
         $newKey = new \SKeyManager\Entity\Key($key->getId());
         $newKey->load();
         $view['body'] = getKeyDetails($newKey);
      } else {
         $view['danger'] = _('ERROR! Could not remove holder.'.$message);
         $view['body'] = getKeyDetails($keyId);
      }
   } else {
      // Check for conditions to be true for holder deletion
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

function showPermissionMatrixPage(){

   $keys = new \SKeyManager\Repository\KeyRepository;
   $locks = new \SKeyManager\Repository\LockRepository;
   $perms = new \SKeyManager\Repository\PermissionRepository;

   $view = array(
        'header' => getHeader('key', 'permissions'),
        'body' => getPermissionMatrix($keys, $locks, $perms),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

function getPermissionMatrix($keys = null, $locks = null, $perms = null){

    $numberOfLocks = count($locks->getAll());

    $permArray = array();

    foreach($perms->getAll() as $perm) {
         $permArray[$perm->getKeyId()][$perm->getLockId()] = $perm->getSymbol();
    }

    $view = array(
        'keys' => $keys->getAll(),
        'locks' => $locks->getAll(),
        'perms' => $permArray,
        'numberOfLocks' => $numberOfLocks
    );

    return render($view, 'perm_matrix');
}

?>
