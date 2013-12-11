<?php
/**
 * AppManager API call
 *
 * connect to app-arena.com app-manager RESTful API
 *
 * @category    api
 * @package     appmanager
 *
 * @see         https://wiki.app-arena.com/display/developer/API+Reference
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @version     1.0.0 (01.10.13 - 19:29)
 */
namespace com\apparena\api;

class AA_AppManager
{
    protected $client = null; //soap client
    protected $server_url = 'https://www.app-arena.com/manager/server/api.php'; //soap server url
    protected $error_msg = ''; // Error message on failed soap call

    //this params will transport each call
    protected $api_params = array(
        'aa_app_id' => false, 'aa_app_secret' => false, 'aa_inst_id' => false, 'fb_page_id' => false, 'locale' => false,
    );

    /**
     * Class constructor to establish the app-manager connection
     *
     * @param array $params All facebook parameters to initialize the app-manager
     */
    function __construct($params)
    {
        // void
    }

    /**
     * Set current localization for the app-manager connection
     */
    function setLocale($locale)
    {
        // void
    }

    /**
     * Try get fb page id from $_REQUEST['signed_request']. This will only work in fan page tabs
     *
     * @return string|boolean   fb_page_id for success and false for failed
     */
    private function getFbPageId()
    {
        // void
    }

    /**
     * Initialize the app-manager connection. Class can be overwritten to use a different soap server url
     */
    private function init()
    {
        // void
    }

    /**
     * Change the soap server url before initializing the connection
     */
    public function setServerUrl($url)
    {
        // void
    }

    /**
     * Get the soap server url
     */
    function getServerUrl()
    {
        // void
    }

    /**
     * Initialize the soap client
     */
    private function initCLient()
    {
        // void
    }

    /**
     * Call a soap server method. If failed, return false and set error_msg
     *
     * @param  string        $method
     * @param  array|boolean $params  which for the $method
     *
     * @return boolean  true or false, when false,you can call  getErrorMsg
     */
    private function call($method, $params = array())
    {
        // void
    }

    /**
     * Returns the error message
     *
     * @return string Error message
     */
    function getErrorMsg()
    {
        // void
    }

    /**
     * Get app's current aa_inst_id
     *
     * @return int
     */
    function getInstanceId()
    {
        // void
    }

    /**
     * Returns all instance information in an array
     *
     * @return array All available instance information
     */
    function getInstance()
    {
        // void
    }

    /**
     * Get content for the current instance
     *
     * @params Mix identifiers , if false , get all config data, if is config identifiers array, only get the value of these identifiers
     *
     * @return array
     */
    function getConfig($identifiers = false, $locale = false)
    {
        // void
    }

    /**
     * Get all config elements filtered by type
     *
     * @param type Type of config elements: text, css, html, image, checkbox, select, multiselect, color, date
     *
     * @return array All config values of submitted type
     */
    function getConfigByType($type)
    {
        // void
    }

    /**
     * Get config element by config identifier
     *
     * @param String Identifier of the config element
     *
     * @return array One single config element
     */
    function getConfigById($identifier)
    {
    }

    /**
     * get Translate
     *
     * @param string $locale False for app model's default locale
     *
     * @return array Returns all available string translations of the current app-models
     */
    function getTranslation($locale = false)
    {
        // void
    }
}