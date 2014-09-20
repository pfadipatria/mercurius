<?php

function render($view, $template){
   var_dump($view);
   ob_start();
   include __DIR__.'/../templates/'.$template.'.phtml';
   $result = ob_get_contents();
   ob_end_clean();

   return $result;
}
