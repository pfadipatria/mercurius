<?php

function showHelpDefaultPage(){

   global $config;

   $supportMail = isset($config['supportMail']) ? $config['supportMail'] : '' ;
   $supportName = isset($config['supportName']) ? $config['supportName'] : $supportMail ;

   $helpView = array(
      'title' => _('Help'),
      'supportName' => $supportName,
      'supportMail' => $supportMail
   );

   $view = array(
        'header' => getHeader('help', ''),
        'body' => render($helpView, 'help_default'),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

