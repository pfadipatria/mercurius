<?php

function showHistoryListPage(){

   $story = new \SKeyManager\Repository\HistoryRepository;

   $view = array(
        'header' => getHeader('history', 'list'),
        'body' => getHistoryList($story),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

function getHistoryList($story = null){

    $view = array(
        'story' => $story->getAll()
    );

    return render($view, 'history_list');
}

function showHistoryCommentPage($keyId = null, $lockId = null, $personId = null) {
   global $activeUserId;

   $view = array(
      'header' => getHeader('history', ''),
      'footer' => getFooter()
   );

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $message = '';
      $history = new \SKeyManager\Entity\History();
      try {
         if (isset($_POST['keyid'])) { $history->setKeyId($_POST['keyid']); }
         if (isset($_POST['lockid'])) { $history->setLockId($_POST['lockid']); }
         if (isset($_POST['personid'])) { $history->setPersonId($_POST['personid']); }
         if (isset($_POST['comment'])) { $history->setComment($_POST['comment']); }
         $history->setAuthorId($activeUserId);
         $result = $history->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! The comment has been added');
         $story = new \SKeyManager\Repository\HistoryRepository;
         $view['body'] = getHistoryList($story);
      } else {
         $view['danger'] = _('ERROR! The comment could not been added'.$message);
         $view['body'] = showHistoryCommentPage($keyId, $lockId, $personId);
      }
   } else {
      $entry = new \SKeyManager\Entity\History;
      $entry->setkeyId($keyId)->setLockId($lockId)->setPersonId($personId);
      $view['body'] = getHistoryCommentAdd($entry);
   }

    echo render($view, 'layout');
}

function getHistoryCommentAdd($entry = null) {

   $view = array(
      'title' => _('Add a comment'),
      'entry' => $entry
   );

   return render($view, 'history_comment');
}
