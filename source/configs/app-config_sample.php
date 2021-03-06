<?php
/**
 * Setup your app-model access credentials here
 */
define('APP_ID', 0);
define('APP_SECRET', '');
define('APP_BASIC_PATH', '/');
// define app admins
define('APP_ADMINS', '');
// date and time settings
define('APP_BASIC_TIMEZONE', 'Europe/Berlin');
define('APP_DEFAULT_LOCALE', 'de_DE');
define('DEBUG', true);
define('LOG_LEVEL', true);
// define ENV (dev|stage|product)
$env_mode = 'product';
if (!empty($_SERVER['APP_ENV']))
{
    $env_mode = $_SERVER['APP_ENV'];
}
define('ENV_MODE', $env_mode);

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
    $db_host           = "";
    $db_name           = "";
    $db_user           = "";
    $db_pass           = "";
    $db_option['port'] = '3306'; // default port
}

// settings optivo mailing
define('OPTIVO_SERVER', '');
define('OPTIVO_PORT', 22);
define('OPTIVO_USER', '');
define('OPTIVO_AUTH', '');
define('OPTIVO_UPLOAD_PATH', '/incoming');
define('OPTIVO_PUBLIC_KEY', '');
define('OPTIVO_PRIVATE_KEY', '');
define('OPTIVO_UPLOAD_REMINDER_FILE', 'reminder.csv');

// settings prämiedirekt api
define('PD_USER', '');
define('PD_PASSWORD', '');

// settings bitly api
define('BITLY_USER', '');
define('BITLY_KEY', '');