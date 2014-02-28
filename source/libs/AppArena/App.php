<?php
namespace Apparena;

use \PDO as PDO;

class App
{
    public static $i_id = null;
    public static $api = array();
    public static $locale = APP_DEFAULT_LOCALE;
    protected  static $_signed_request = null;
    protected  static $_current_date = null;
    const COOKIE_NAME = 'aa_inst_locale_';

    private function __construct()
    {
    }

    /**
     * AppArena class autoloader
     *
     * @param $className
     */
    public static function autoload($className)
    {
        $className = str_replace(array(__NAMESPACE__ . '\\', '\\'), array('', '/'), $className);
        $filename  = ROOT_PATH . "/libs/AppArena/" . $className . ".php";
        if (file_exists($filename))
        {
            require $filename;
        }
    }

    /**
     * get database connection
     *
     * @param string $db_user
     * @param string $db_host
     * @param string $db_name
     * @param string $db_pass
     * @param array  $db_option
     *
     * @return Systems\Database
     */
    public static function getDatabase($db_user, $db_host, $db_name, $db_pass, $db_option)
    {
        $db = new \Apparena\Systems\Database($db_user, $db_host, $db_name, $db_pass, $db_option);
        // set all returned value keys to lower cases
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        // return all query requests automatically as object
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $db;
    }

    /**
     * define current language for API response
     *
     * @param string $locale
     * @param null   $slim
     */
    // TODO: implement an interface to the controller structure adn check them here in $slim
    public static function setLocale($locale = APP_DEFAULT_LOCALE, $slim = null)
    {
        if (!empty(self::$_signed_request['app_data']))
        {
            $app_data = json_decode(self::$_signed_request['app_data'], true);
        }

        $cookiename = self::COOKIE_NAME . self::$i_id;
        $cookie     = $slim->getCookie($cookiename);

        if (!empty($app_data['locale']))
        {
            $locale = $app_data['locale'];
        }
        elseif (!is_null($slim) && !is_null(self::$i_id) && !empty($cookie))
        {
            $locale = $cookie;
        }
        $slim->setCookie($cookiename, $locale);
        self::$locale = $locale;
    }

    public static function getCurrentDate()
    {
        if(is_null(self::$_current_date))
        {
            self::$_current_date = new \DateTime('now', new \DateTimeZone(APP_BASIC_TIMEZONE));
        }

        return self::$_current_date;
    }
}