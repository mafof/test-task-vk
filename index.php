<?php
    ini_set('error_reporting', E_CORE_ERROR);
    ini_set('display_errors', 1);
    require_once __DIR__.'/vendor/autoload.php';
    require_once 'ConfigApp.php';
    new \App\Core();
?>