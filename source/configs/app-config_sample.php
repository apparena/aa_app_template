<?php
/**
 * Setup your app-model access credentials here
 */
$aa_activated        = true;
$aa_app_id           = 0;
$aa_app_secret       = "";
$aa_default_locale   = "de_DE";
$aa_default_timezone = "Europe/Berlin";

/**
 * Setup your database access data
 */
$db_activated = true;
$db_host      = "";
$db_name      = "";
$db_user      = "";
$db_pass      = "";
$db_option    = array(
    'port' => '3306', // default port
    'type' => 'mysql', // database driver
    'pdo'  => array( // default driver attributes
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    )
);

// define app admins
define('APP_ADMINS', 'kontakt@marcusmerchel.de');

// define ENV (dev|stage|product)
$env_mode = 'product';
if(!empty($_SERVER['APP_ENV']))
{
    $env_mode = $_SERVER['APP_ENV'];
}
define('ENV_MODE', $env_mode);

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