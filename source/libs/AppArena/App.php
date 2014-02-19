<?php
namespace Apparena;

use \PDO as PDO;

class App
{
    private $_i_id = null;
    private $_config = array();
    private $_instance = array();
    private $_locale = array();
    private $_fb = array();

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
}