<?php

function showPrefListPage(){

   $view = array(
        'header' => getHeader('pref', ''),
        'body' => getPrefList(),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

function getPrefList(){

   $view = array(
      'title' => _('Preferences'),
      'subTitle' => _('This is an overview of all helper tables/functions that can be change in future (as soon as this page is more developed.<br><b>Don\'t get confused!</b> Most of these informations/values are for KeyAdmins / lock system internal purposes only.')

   );

   return render($view, 'pref_list');
}
