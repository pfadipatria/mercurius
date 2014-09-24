<?php

function readConfigFiles() {
   global $config;

   $sampleConfigFile = __DIR__.'/../config/main.sample.ini';
   $sampleConfig = file_exists($sampleConfigFile) ? parse_ini_file($sampleConfigFile) : array() ;
   $mainConfigFile = __DIR__.'/../config/main.ini';
   $mainConfig = file_exists($mainConfigFile) ? parse_ini_file($mainConfigFile) : array() ;

   $config = array_replace($sampleConfig, $mainConfig);

}
