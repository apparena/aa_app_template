<?php
/**
 * AppManager API call
 *
 * connect to app-arena.com app-manager RESTful API
 *
 * @category    AppArena
 * @package     Api
 * @subpackage  AppManager
 *
 * @see         https://wiki.app-arena.com/display/developer/API+Reference
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @version     1.0.0 (01.10.13 - 19:29)
 */
namespace Apparena\Api;

class AppManager
{
    const SERVER_URL = 'http://manager.app-arena.com/api/v1/instances/';
    #const CACHE_TIME = '86400'; //24h
    #const CACHE_PREFIX = 'api_';
    #protected $cache_path = null;

    //this params will transport each call
    protected $_api_params = array(
        'aa_app_id'     => null,
        'aa_app_secret' => null,
        'i_id'          => null,
        'fb_page_id'    => null,
        'locale'        => null,
    );
    #protected $_config = null;
    #protected $_translation = array();
    #protected $_instance = null;
    static private $_class_instance = null;
    public $isAjax = false;
    public $hash;

    /**
     * Class constructor to establish the app-manager connection
     *
     * @param array $params All facebook parameters to initialize the app-manager
     */
    private function __construct($params)
    {
        // set cache path
        $this->cache_path = ROOT_PATH . '/tmp/cache/';

        // set params
        $this->setAppId($params)->setLocale($params)->setInstanceId($params)->setFbPageId($params)
             ->setAppSecret($params);
    }

    /**
     * get class instance (singleton instance)
     *
     * @param array $params All facebook parameters to initialize the app-manager
     *
     * @return AppManager
     */
    static public function init($params)
    {
        if (null === self::$_class_instance)
        {
            self::$_class_instance = new self($params);
        }

        return self::$_class_instance;
    }

    public function setLocale($locale)
    {
        $this->setParam($locale, 'locale');

        return $this;
    }

    public function setAppId($id)
    {
        $this->setParam($id, 'aa_app_id');

        return $this;
    }

    protected function setParam($param, $key)
    {
        if ($this->isArray($param, $key) === false)
        {
            $this->_api_params[$key] = $param;
        }
        $this->setHash();
    }

    protected function setHash()
    {
        $this->hash = md5(implode(' ', $this->_api_params));
    }

    protected function isArray($array, $key)
    {
        if (is_array($array))
        {
            if (array_key_exists($key, $this->_api_params) && array_key_exists($key, $array))
            {
                $this->_api_params[$key] = $array[$key];

                return true;
            }

            return null;
        }

        return false;
    }

    public function getAppId()
    {
        if ($this->_api_params['aa_app_id'] === null)
        {
            throw new \Exception('App ID is not defined');
        }

        return $this->_api_params['aa_app_id'];
    }

    public function getAppSecret()
    {
        if ($this->_api_params['aa_app_secret'] === null)
        {
            throw new \Exception('App-Secret is not defined');
        }

        return $this->_api_params['aa_app_secret'];
    }

    public function setAppSecret($secret)
    {
        $this->setParam($secret, 'aa_app_secret');

        return $this;
    }

    public function getFbPageId()
    {
        if ($this->_api_params['fb_page_id'] === null)
        {
            throw new \Exception('Facebook Page ID is not defined');
        }

        return $this->_api_params['fb_page_id'];
    }

    public function setFbPageId($id)
    {
        $this->setParam($id, 'fb_page_id');

        return $this;
    }

    public function setInstanceId($id)
    {
        $this->setParam($id, 'i_id');

        return $this;
    }

    public function getInstance($type = 'all')
    {
        $scope    = '.json';
        $instance = $this->call($scope);

        return $this->defineReturn($instance, $type);
    }

    public function call($scope)
    {
        // first on ajax calls, try to get call from cache
        /*$filename = self::CACHE_PREFIX . md5($scope . implode('-', $this->_api_params));
        if ($this->isAjax === true || defined('REDIRECTION') || !empty($_GET['cache']))
        {
            $return = $this->getCachedFile($this->cache_path . $filename);
            if (!empty($return))
            {
                return $return;
            }
        }*/

        // cache is empty or not called, get resource now from API
        $return = json_decode(@file_get_contents(self::SERVER_URL . $this->getInstanceId() . $scope));
        if ($return === null)
        {
            throw new \Exception('API Call error: "' . self::SERVER_URL . $this->getInstanceId() . $scope . '"');
        }

        // cache result
        /*$cachedfile = json_encode($return);
        file_put_contents($this->cache_path . $filename, $cachedfile);*/

        return $return;
    }

    protected function defineReturn($data, $return_type)
    {
        if (empty($data))
        {
            return false;
        }

        if ($return_type === 'all')
        {
            return $data;
        }

        return $data->$return_type;
    }

    /**
     * get call return from cache
     *
     * @param      $filename
     * @param bool $timecheck
     *
     * @return bool|mixed
     */
    /*protected function getCachedFile($filename, $timecheck = true)
    {
        if (file_exists($filename) && ($timecheck === false || (time() - filemtime($filename)) < self::CACHE_TIME))
        {
            // get cached file
            $cachedfile = file_get_contents($filename);

            // return as stdClass object
            return json_decode($cachedfile);
        }

        return false;
    }*/

    public function getInstanceId()
    {
        if ($this->_api_params['i_id'] === null)
        {
            throw new \Exception('Instance ID is not defined');
        }

        return $this->_api_params['i_id'];
    }

    public function getConfig($type = 'all')
    {
        $scope  = '/config.json?limit=0';
        $config = $this->call($scope);

        return $this->defineReturn($config, $type);
    }

    public function getTranslation($type = 'all')
    {
        $locale = $this->getLocale();

        $scope       = '/locale.json?locale=' . $this->getLocale() . '&limit=0';
        $translation = $this->call($scope);

        $return = $this->defineReturn($translation, $type);

        if ($return !== false)
        {
            $return        = (object)$return->$locale;
            $return->index = $this->createTranslationIndex($translation->data);
        }

        return $return;
    }

    protected function createTranslationIndex($translations)
    {
        $locale = $this->getLocale();
        $index  = new \stdClass();

        foreach ($translations->$locale AS $key => $value)
        {
            $value         = md5($value->l_id);
            $index->$value = $key;
        }

        return $index;
    }

    public function getLocale()
    {
        if ($this->_api_params['locale'] === null)
        {
            throw new \Exception('Locale is not defined');
        }

        return $this->_api_params['locale'];
    }

    public function getConfigById($id)
    {
        // void
    }

    public function getTranslationById($id)
    {
        // void
    }

    public function translate($id, $locale)
    {
        // void
    }

    /**
     * disable clone function (singleton)
     */
    private function __clone() { }
}