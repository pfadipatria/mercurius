<?php

function readConfigFiles() {
   global $config;

   // Set default values if there are no files present
   $defaultConfig = array(
      'siteName' => 'SKeyManager',
      'siteSubTitle' => '',
      'pageHeadline' => '',
      'dbhost' => 'localhost',
      'dbuser' => 'skeymanager_example',
      'dbpass' => 'skeymanager_example',
      'supportName' => 'your admin',
      'supportMail' => ''
   );

   // Read files
   $sampleConfigFile = __DIR__.'/../config/main.sample.ini';
   $sampleConfig = file_exists($sampleConfigFile) ? parse_ini_file($sampleConfigFile) : array() ;
   $mainConfigFile = __DIR__.'/../config/main.ini';
   $mainConfig = file_exists($mainConfigFile) ? parse_ini_file($mainConfigFile) : array() ;

   // Prefer sample config over default
   $sampleConfig = array_replace($defaultConfig, $sampleConfig);

   // Prefer main config over the examples and fill them into the global variable
   $config = array_replace($sampleConfig, $mainConfig);

}
