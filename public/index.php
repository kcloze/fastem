<?php
//这里必须设定运行目录，强制写死，可以加快运行速度
define('BASE_PATH', '/var/www/f.netroby.com/');

define('APPLICATION_PATH', BASE_PATH . 'application');
//define('APPLICATION_ENV', 'production');
define('APPLICATION_ENV', 'development');

set_include_path(BASE_PATH . 'library');

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()->run();



