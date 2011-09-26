<?php
define('BASE_PATH', realpath(dirname(dirname(__FILE__))));

define('APPLICATION_PATH', BASE_PATH . '/application');
//define('APPLICATION_ENV', 'production');
define('APPLICATION_ENV', 'development');

set_include_path(BASE_PATH . '/library');

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()->run();



