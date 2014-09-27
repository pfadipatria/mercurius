<?php

function showLockListPage(){

   $locks = new \SKeyManager\Repository\LockRepository;

   $view = array(
        'header' => getHeader('lock', 'list'),
        'body' => getLockList($locks),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

function getLockList($locks = null){

    $view = array(
        'locks' => $locks->getAll()
    );

    return render($view, 'lock_list');
}

function showLockDetailsPage($lockId = '0'){

   $lock = new \SKeyManager\Entity\Lock($lockId);
   $lock->load();

   $view = array(
      'header' => getHeader('lock', $lockId),
      'body' => getLockDetails($lock),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

function getLockDetails($lock = null){

   $denies = new \SKeyManager\Repository\PermissionRepository;
   $allowed = new \SKeyManager\Repository\PermissionRepository;

   $lockView = array(
     'lock' => $lock
   );

   $denialView = array(
      'title' => _('Denials for Keys'),
      'titleColor' => 'red',
      'lockLocation' => $lock->getLocation(),
      'keys' => $denies->getDeniesByLock($lock->getId())
   );

   $permissionView = array(
      'title' => _('Allowed on Keys'),
      'titleColor' => 'green',
      'lockLocation' => $lock->getLocation(),
      'keys' => $allowed->getAllowsByLock($lock->getId())
   );

   $view = array(
      'lock' => render($lockView, 'lock_entry'),
      'denials' => render($denialView, 'lock_keylist'),
      'allowed' => render($permissionView, 'lock_keylist')
   );

   return render($view, 'lock_layout');
}

function showLockEditPage($lockId = '0'){
   global $activeUserId;

   $view = array(
      'header' => getHeader('lock', ''),
      'footer' => getFooter()
   );

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $message = '';
      $history = new \SKeyManager\Entity\History();
      $id = null;
      if (array_key_exists('id',$_POST)) {
         $id = $_POST['id'];
      }
      $lock = new SKeyManager\Entity\Lock($id);
      try {
         $history->setComment('Schloss erstellt');
         if (array_key_exists('id',$_POST)) {
            $lock->load();
            $history->setComment('Schloss aktualisiert');
         }
         $lock->setNumber($_POST['number']);
         $lock->setStatusId($_POST['statusid']);
         $lock->setCode($_POST['code']);
         $lock->setVenueId($_POST['venueid']);
         $lock->setHasBatteries($_POST['hasbatteries']);
         $lock->setName($_POST['name']);
         $lock->setType($_POST['type']);
         $lock->setPosition($_POST['position']);
         $lock->setComment($_POST['comment']);
         $result = $lock->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! Der Eintrag wurde aktualisiert.');
         $history->setLockId($lock->getId())->setAuthorId($activeUserId)->save();
         $newLock = new \SKeyManager\Entity\Lock($lock->getId());
         $newLock->load();
         $view['body'] = getLockDetails($newLock);
      } else {
         $view['danger'] = _('Fehler! Der Eintrag konnte nicht aktualisiert werden.'.$message);
         $view['body'] = getLockEdit($lock);
      }
   } else {
      if ($lockId === '0') {
         $view['body'] = getLockEdit();
      } else {
         $lock = new \SKeyManager\Entity\Lock($lockId);
         $lock->load();
         $view['body'] = getLockEdit($lock);
      }
   }

   echo render($view, 'layout');
}

function getLockEdit($lock = null){
   $hasData = false;
   if ($lock !== null) {
      $hasData = true;
      $view['title'] = $lock->getFullName();
   } else {
      $lock = new \SKeyManager\Entity\Lock();
      $view['title'] = _('Add a new Lock');
   }

   $view['hasData'] = $hasData;
   $view['lock'] = $lock;

   return render($view, 'lock_edit');
}

function showLockDenyPage($lockId = '0'){

   $lock = new \SKeyManager\Entity\Lock($lockId);
   $lock->load();

   $view = array(
      'header' => getHeader('lock', $lockId),
      'body' => getLockDeny($lock),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

function getLockDeny($lock = null){
   global $activeUserId;

   $view = array();

   // User contrib on http://php.net/manual/en/function.preg-grep.php
   function preg_grep_keys($pattern, $input, $flags = 0) {
       return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $permValues = preg_grep_keys('/^select/', $_POST);
      $message = '';
      $lockId = null;
      if (array_key_exists('lockid',$_POST)) {
         $lockId = $_POST['lockid'];
         $perms = new \SKeyManager\Repository\PermissionRepository;
         try {
            foreach($permValues as $keyId => $statusId){
               $result = $perms->setDenyPermission($lockId, substr($keyId, 6), $statusId);
            }
         } catch (Exception $exception) {
            $result = false;
            $message = ' ('.$exception->getMessage().')';
         }

         if($result){
            $view['success'] = _('OK! The entry has been updated.');
            $history = new \SKeyManager\Entity\History();
            $history->setKeyId($keyId)->setComment('Updated lock permission')->setAuthorId($activeUserId)->save();
         } else {
            $view['danger'] = _('ERROR! Could not update the entry.'.$message);
         }

      }
   }

   $keys = new \SKeyManager\Repository\KeyRepository;
   $perms = new \SKeyManager\Repository\PermissionRepository;

   $keylist = array();
   foreach($keys->getAll() as $key) {
      $keylist[$key->getId()]['name'] = $key->getName();
      $keylist[$key->getId()]['keyid'] = $key->getId();
      // @TODO getKeyAllowedOnLock should only return ONE permission entity
      $perm = $perms->getLockDeniedForKey($lock->getId(), $key->getId());
      $keylist[$key->getId()]['permid'] = $perm ? $perm['0']->getId() : null;
      $keylist[$key->getId()]['statusid'] = $perm ? $perm['0']->getStatusId() : null;
   }

   $permView = array(
      'lockid' => $lock->getId(),
      'permlist' => $keylist
   );

   $view['title'] = sprintf(_('Change denied keys on %s'), $lock->getName());
   $view['perm'] = render($permView, 'lock_perm_edit');


   return render($view, 'perm_layout');
}

function showLockDeletePage($lockId = '0'){

   $lock = new \SKeyManager\Entity\Lock($lockId);
   $lock->load();

   $view = array(
      'header' => getHeader('lock', $lockId),
      'danger' => _('Do not delete locks at this point, just mark them as inactive.'),
      'body' => getLockDetails($lock),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

?>
