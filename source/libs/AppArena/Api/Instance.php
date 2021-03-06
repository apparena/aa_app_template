<?php
/**
 * Instance
 *
 * Stores API instance data
 *
 * @category    AppArena
 * @package     Api
 * @subpackage  Instance
 *
 * @see         https://wiki.app-arena.com/display/developer/API+Reference
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @version     1.0.0 (25.02.14 - 11:45)
 */
namespace Apparena\Api;

class Instance
{
    public $config;
    public $data;
    public $locale;
    public $fb;
    public $env;
    static private $_instance = null;

    /**
     * __construct
     *
     * @access private
     */
    private function __construct()
    {
    }

    /**
     * get class instance (singleton instance)
     *
     * @return Instance
     */
    static public function init()
    {
        if (null === self::$_instance)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @param mixed $config
     */
    /*public function setConfig($config)
    {
        $this->_config = $config;
    }*/

    /**
     * @return mixed
     */
    /*public function getConfig()
    {
        return $this->_config;
    }*/

    /**
     * @param mixed $data
     */
    /*public function setData($data)
    {
        $this->_data = $data;
    }*/

    /**
     * @param array $data
     */
    public function addData(array $data)
    {
        $this->data = (object)array_merge((array)$this->data, $data);
    }

    /**
     * @return mixed
     */
    /*public function getData()
    {
        return $this->_data;
    }*/

    /**
     * @param mixed $fb
     */
    /*public function setFb($fb)
    {
        $this->_fb = $fb;
    }*/

    /**
     * @return mixed
     */
    /*public function getFb()
    {
        return $this->_fb;
    }*/

    /**
     * @param mixed $locale
     */
    /*public function setLocale($locale)
    {
        $this->_locale = $locale;
    }*/

    /**
     * @return mixed
     */
    /*public function getLocale()
    {
        return $this->_locale;
    }*/

    /**
     * @param mixed $env
     */
    /*public function setEnv($env)
    {
        $this->_env = $env;
    }*/

    /**
     * @return mixed
     */
    /*public function getEnv()
    {
        return $this->_env;
    }*/

    /**
     * disable clone function (singleton)
     */
    private function __clone() { }
} 