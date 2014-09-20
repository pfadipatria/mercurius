<?php

require_once 'lib/libAll.php';

function bootstrap(){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    routing(loggedIn());
}
