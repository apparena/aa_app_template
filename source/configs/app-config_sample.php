<?php
/**
 * Setup your app-model access credentials here
 */
// app model ID - see appmanager
define('APP_ID', 0);
// app model secret key - see appmanager
define('APP_SECRET', '');
// basic url path for cookies
define('APP_BASIC_PATH', '/aa_app_template/source/');
// define global app admins
define('APP_ADMINS', '');
// date and time settings
define('APP_BASIC_TIMEZONE', 'Europe/Berlin');
define('APP_DEFAULT_LOCALE', 'de_DE');
// define ENV (dev|stage|product)
define('ENV_MODE', 'dev');
define('DEBUG', true);
define('LOG_LEVEL', true);

/**
 * Setup your database access data
 */
define('DB_ACTIVATED', true);
$db_option = array(
    'type' => 'mysql', // database driver
    'pdo'  => array(
        // default driver attributes
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    )
);
if (!empty($_SERVER['APP_ENV_SERVER']) && $_SERVER['APP_ENV_SERVER'] === 'vagrant')
{
    $db_host           = "localhost";
    $db_name           = "app";
    $db_user           = "app";
    $db_pass           = "app";
    $db_option['port'] = '3306'; // default port
}
else
{
    $db_host           = "localhost";
    $db_name           = "app_template";
    $db_user           = "root";
    $db_pass           = "root";
    $db_option['port'] = '3306'; // default port
}

/**
 * extension setting
 */
// settings bitly api
define('BITLY_USER', '');
define('BITLY_KEY', '');

/**
 * settings for optivo mailing
 */
// optivo server API url
define('OPTIVO_SERVER', 'ftpapi.broadmail.de');
define('OPTIVO_PORT', 22);
// optivo username
define('OPTIVO_USER', '');
// upload path on the optivo server
define('OPTIVO_UPLOAD_PATH', '/incoming');
// path to the public optivo ssh key that is stored on the optivo server
define('OPTIVO_PUBLIC_KEY', '/var/www/ssh/optivo.pub');
// path to the private optivo ssh key that is stored on the optivo server
define('OPTIVO_PRIVATE_KEY', '/var/www/ssh/optivo');
define('OPTIVO_UPLOAD_REMINDER_FILE', 'reminder.csv');
define('OPTIVO_AUTH', '');

/*
// settings prämiedirekt api
define('PD_USER', '');
define('PD_PASSWORD', '');
*/