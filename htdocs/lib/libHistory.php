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

