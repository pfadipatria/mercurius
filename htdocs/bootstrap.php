<?php

define('skmName', 'SKeyManager');
define('skmVersion', '0.1.1');

require_once 'lib/libAll.php';

readConfigFiles();

function bootstrap(){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    routing(loggedIn());
}
