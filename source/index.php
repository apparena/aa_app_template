<?php
/**
 * Setup the environment
 */
date_default_timezone_set('Europe/Berlin'); // Set timezone
ini_set('session.gc_probability', 0); // Disable session expired check
header('P3P: CP=CAO PSA OUR'); // Fix IE save cookie in iframe problem

// cache busting
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

define('ROOT_PATH', realpath(dirname(__FILE__))); // Set include path
define('DS', DIRECTORY_SEPARATOR);

/**
 * Include necessary libraries
 */
if (file_exists(ROOT_PATH . '/configs/app-config.php'))
{
    require_once ROOT_PATH . '/configs/app-config.php';

    if (ENV_MODE === 'dev')
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(-1);
    }
}
else
{
    throw new Exception('Config file not exist. Please rename app-config_sample.php to app-config.php in /configs/ and fill it with live ;)');
}
require_once ROOT_PATH . '/libs/Slim/Slim.php';
require_once ROOT_PATH . '/libs/AppArena/App.php';

// configuration autoloaders
spl_autoload_register('\Apparena\App::autoload');
\Slim\Slim::registerAutoloader();

// set routes
$router = new \Apparena\Router();
$routes = require_once ROOT_PATH . '/configs/routes.php';
$router->addRoutes($routes);
$router->set404Handler("Main:notFound");

$db = null;
if (DB_ACTIVATED)
{
    $db = \Apparena\App::getDatabase($db_user, $db_host, $db_name, $db_pass, $db_option);
}

$router->run();