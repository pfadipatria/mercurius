<?php

function showHelpDefaultPage(){

   global $config;

   $supportName = isset($config['supportName']) ? $config['supportName'] : '<a generic support name>' ;
   $supportMail = isset($config['supportMail']) ? $config['supportMail'] : 'support@test.com' ;

   $helpView = array(
      $title = _('Help / About'),
      $supportName = $supportName,
      $supportMail = $supportMail
   );

   $view = array(
        'header' => getHeader('help', ''),
        'body' => render($helpView, 'help_default'),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

