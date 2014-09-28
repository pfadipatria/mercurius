<?php

function showSearchPage($entity = null){

   if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['q'])) {
      $query = $_GET['q'];

      $view = array(
           'header' => getHeader($entity, 'search'),
           'body' => getSearchResult($entity, $query),
           'footer' => getFooter()
       );

   } else {
      $view = array(
           'header' => getHeader($entity, 'search'),
           'body' => getSearchForm($entity),
           'footer' => getFooter()
       );
   }
   echo render($view, 'layout');
}

function getSearchForm($entity = null){

   $view = array(
      'entity' => $entity
   );

   return render($view, 'search_form');
}

function getSearchResult($entity = '', $query){

   $search = new \SKeyManager\Repository\SearchRepository;
   $keys = '';
   $locks = '';
   $people = '';

   if(empty($entity) || $entity == 'key') {
      $viewKeys = array(
         'keys' => $search->getKeys($query)
      );
      $keys = render($viewKeys, 'key_list');
   }

   if(empty($entity) || $entity == 'lock') {
      $viewLocks = array(
         'locks' => $search->getLocks($query)
      );
      $locks = render($viewLocks, 'lock_list');
   }

   if(empty($entity) || $entity == 'person') {
      $viewPeople = array(
         'people' => $search->getPeople($query)
      );
      $people = render($viewPeople, 'person_list');
   }

   $title = _('Result for  \''.$query.'\'');
   $title .= $entity ? ' on '.$entity : '';

   $view = array(
      'title' => $title,
      'keys' => $keys,
      'locks' => $locks,
      'people' => $people
   );

   return render($view, 'search_layout');
}
