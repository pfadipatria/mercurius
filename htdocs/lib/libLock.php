<?php

function showLockListPage(){

   $locks = new \SKeyManager\Repository\LockRepository;

   $view = array(
        'header' => getHeader('locks', 'list'),
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
      'keys' => $denies->getDeniesByLock($lock->getId())
   );

   $permissionView = array(
      'title' => _('Allowed on Keys'),
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
   $view = array(
      'header' => getHeader('lock', ''),
      'footer' => getFooter()
   );

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $message = '';
      $id = null;
      if (array_key_exists('id',$_POST)) {
         $id = $_POST['id'];
      }
      $lock = new SKeyManager\Entity\Lock($id);
      try {
         if (array_key_exists('id',$_POST)) {
            $lock->load();
         }
         $lock->setNumber($_POST['number']);
         $lock->setStatusId($_POST['statusid']);
         $lock->setComment($_POST['comment']);
         $result = $lock->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! Der Eintrag wurde aktualisiert.');
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

?>
