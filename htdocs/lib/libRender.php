<?php

/**
 * We will be using $view during the outputbuffer therefor:
 * 
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function render($view, $template){
   ob_start();
   include __DIR__.'/../templates/'.$template.'.phtml';
   $result = ob_get_contents();
   ob_end_clean();

   return $result;
}
