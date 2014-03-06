<?php
/**
 * cache busting
 */
/*header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");*/

/**
 * Setup the environment
 */
date_default_timezone_set('Europe/Berlin'); // Set timezone
ini_set('session.gc_probability', 0); // Disable session expired check
header('P3P: CP=CAO PSA OUR'); // Fix IE save cookie in iframe problem

define('ROOT_PATH', str_replace('/includes', '', realpath(dirname(__FILE__)))); // Set include path
#define('_VALID_CALL', 'true');

require_once ROOT_PATH . '/configs/app-config.php';
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

if (DB_ACTIVATED)
{
    $db = \Apparena\App::getDatabase($db_user, $db_host, $db_name, $db_pass, $db_option);
}

$router->run();



/*try
{
    require_once("init.php");
}
catch (Exception $e)
{
    if ((defined('ENV_MODE') && ENV_MODE !== 'product') || (!empty($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] !== 'product'))
    {
        echo '<pre style="background-color: #F2DEDE;border-color: #EBCCD1;color: #B94A48;padding: 15px;border: 1px solid rgba(0, 0, 0, 0);border-radius: 4px;margin-bottom: 20px;padding: 15px;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;font-size: 14px;line-height: 1.42857;">';
        print_r($e->getMessage());
        echo '</pre>';

        echo '<pre style="background-color: #FCF8E3;border-color: #FAEBCC;color: #C09853;padding: 15px;border: 1px solid rgba(0, 0, 0, 0);border-radius: 4px;margin-bottom: 20px;padding: 15px;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;font-size: 14px;line-height: 1.42857;">';
        print_r($e->getTrace());
        echo '</pre>';
    }
    exit();
}*/

/*
 * prepare the aa object for js
 */
/*$aaForJs = (object)array(
    'locale'   => $aa->locale,
    'config'   => $aa->config,
    'instance' => $aa->instance,
    'env'      => $aa->env,
    'fb'       => false,
    'app_data' => false,
    'user'     => (object)array(
            'ip'    => get_client_ip(),
            'agent' => $_SERVER['HTTP_USER_AGENT']
        ),
    'gp'       => (object)array(
            'api_key'   => GP_API_KEY,
            'client_id' => GP_CLIENT_ID
        )
);

$aaForJs->env->mode = ENV_MODE;

if (isset($aa->fb))
{
    $aaForJs->fb                   = $aa->fb;
    $aaForJs->fb->request_id       = $fb_request_id;
    $aaForJs->fb->invited_by       = $fb_invited_by;
    $aaForJs->fb->invited_for_door = $invited_for_door;
}

if (!empty($_GET['app_data']))
{
    $aaForJs->app_data = $_GET['app_data'];
}
else
{
    if (!empty($fb_signed_request['app_data']))
    {
        $aaForJs->app_data = $fb_signed_request['app_data'];
    }
}

// save current time as timestamp in JS varible to handle temporary uid
$aaForJs->timestamp = $current_date->getTimestamp();
// create a unique id to use as temporary uid
$aaForJs->uid_temp = md5($i_id . uniqid() . $current_date->getTimestamp());

// delete some important variables
if (isset($aaForJs->instance->aa_app_secret))
{
    unset($aaForJs->instance->aa_app_secret);
}
if (isset($aaForJs->instance->fb_app_secret))
{
    unset($aaForJs->instance->fb_app_secret);
}

// add basic app admins
if (isset($aaForJs->config->admin_mails))
{
    $aaForJs->config->admin_mails->value = $aaForJs->config->admin_mails->value . ',' . APP_ADMINS;
}
else
{
    pr('Missing app wizard config "admin_mails"');
}

// show admin button or login form
$show_admin  = 'hide';
$show_profil = 'hide';
$show_login  = '';
$show_logout = 'hide';
if (!empty($_SESSION['login']['gid']) && $_SESSION['login']['gid'] === 'admin')
{
    $show_admin  = '';
    $show_profil = '';
    $show_login  = 'hide';
}
elseif (!empty($_SESSION['login']['gid']) && $_SESSION['login']['gid'] === 'user')
{
    $show_admin  = 'hide';
    $show_login  = 'hide';
    $show_profil = '';
}
$user = '';
if (!empty($_SESSION['login']['user']['mail']))
{
    $user = $_SESSION['login']['user']['mail'];
}

// generate admin key for admin button
$aaForJs->custom = (object)array('admin_key' => md5($i_id . '_' . $aa_app_secret));*/
