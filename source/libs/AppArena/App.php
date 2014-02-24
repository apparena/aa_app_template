<?php
namespace Apparena;

use \PDO as PDO;

class App
{
    public static $_i_id = null;
    public static $_api = array();
    public static $_locale = APP_DEFAULT_LOCALE;
    public static $_signed_request = null;

    public function __construct()
    {
        // TODO: Implement __construct() method.
    }

    public static function autoload($className)
    {
        $className = str_replace(array(__NAMESPACE__ . '\\', '\\'), array('', '/'), $className);
        $filename = ROOT_PATH . "/libs/AppArena/" . $className . ".php";
        if (file_exists($filename))
        {
            require $filename;
        }
    }

    public static function getDatabase($db_user, $db_host, $db_name, $db_pass, $db_option)
    {
        $db = new \Apparena\Systems\Database($db_user, $db_host, $db_name, $db_pass, $db_option);
        // set all returned value keys to lower cases
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        // return all query requests automatically as object
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $db;
    }

    public static function setLocale($locale = APP_DEFAULT_LOCALE)
    {
        if (!empty(self::$_signed_request['app_data']))
        {
            $app_data = json_decode(self::$_signed_request['app_data'], true);
        }

        if (!empty($_GET['locale']))
        {
            $locale  = $_GET['locale'];
        }
        else
        {
            if (!empty($app_data['locale']))
            {
                $locale  = $app_data['locale'];
            }
            else
            {
                if (!is_null(self::$_i_id) && !empty($_COOKIE['aa_inst_locale_' . self::$_i_id]))
                {
                    $locale  = $_COOKIE['aa_inst_locale_' . self::$_i_id];
                }
            }
        }
        self::$_locale = $locale;
    }
}