<?php
namespace Apparena;

// include apparena helper functions
require ROOT_PATH . '/libs/AppArena/Helper/aa_helper.php';

Class Controller extends \Slim\Slim
{
    protected $_render = true;
    protected $_status = 302;
    protected $_request;
    protected $_data = array();

    public function __construct()
    {
        $env            = \Slim\Environment::getInstance();
        $this->_request = new \Slim\Http\Request($env);
        $settings       = require ROOT_PATH . '/configs/slim-config.php';
        parent::__construct($settings);

        if (!empty($_SERVER['APP_ENV']))
        {
            parent::config('mode', $_SERVER['APP_ENV']);
        }
    }

    /**
     * starts some slim processes to get slim functionality
     * This thinks are not really necessary for our app
     */
    public function __destruct()
    {
        // define an internal slim route, to disable slims error handling
        $this->map('/:wildcard+', function ()
        {
            // Do nothing
        })->via('GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS');

        // start slim to start internal functions, we need to use
        $this->run();
    }

    /**
     * setup some things before we call the main method
     *
     * @param int    $i_id API instance ID
     * @param string $lang language settings
     */
    public function before($i_id = 0, $lang = APP_DEFAULT_LOCALE)
    {
        // check uri on last character. Is there no / at the end, redirect the page
        $uri       = $this->_request->getResourceUri();
        $last      = substr($uri, -1);
        $extension = substr($uri, -4);
        if ($uri === "" || ($last !== '/' && $extension !== '.php' && $extension !== 'html'))
        {
            $this->redirect($this->_request->getResourceUri() . '/', 301);
        }

        // set API instance ID
        \Apparena\App::$i_id = $i_id;

        // define language
        \Apparena\App::setLocale($lang, $this);
    }

    /**
     * setup some things after we call the main method
     */
    public function after()
    {
        // render automatically templates
        if ($this->_render)
        {
            $this->display($this->_data, $this->_status);
        }
    }

    public function render($template, $data = array(), $status = null)
    {
        if (strpos($template, '.html') === false)
        {
            $template = $template . '.html';
        }

        if (!is_null($status))
        {
            $this->response->setStatus($status);
        }
        $this->view->appendData($data);

        return $this->view->fetch($template, $data);
    }

    public function display($data = array(), $status = null)
    {
        $settings = array_merge(array(
            'meta_title'       => $this->config('metatags')->meta_title,
            'meta_description' => $this->config('metatags')->meta_description,
            'meta_canonical'   => $this->config('metatags')->meta_canonical,
            'layout_css'   => $this->_request->getRootUri() . '/' . \Apparena\App::$i_id . '/assets/css/style/',
        ), $data);
        echo $this->render($this->config('templates.base'), $settings, $status);
    }

    protected function callApi()
    {
        $instance = \Apparena\Api\Instance::init();

        \Apparena\App::$api = \Apparena\Api\AppManager::init(array(
            'aa_app_id'     => APP_ID,
            'aa_app_secret' => APP_SECRET,
            'i_id'          => \Apparena\App::$i_id,
            'locale'        => \Apparena\App::$_locale
        ));

        \Apparena\App::$api->isAjax = $this->_request->isAjax();

        $instance->setData(\Apparena\App::$api->getInstance('data'));
        $instance->setConfig(\Apparena\App::$api->getConfig('data'));
        $instance->setLocale(\Apparena\App::$api->getTranslation('data'));
        $instance->setData(\Apparena\App::$api->getInstance('data'));
        $this->checkInstance($instance->getData());

        // define some basic constance's that we get over config values
        define('GP_CLIENT_ID', __c('gp_client_id'));
        define('GP_API_KEY', __c('gp_api_key'));
        define('TW_CONSUMER_KEY', __c('tw_consumer_key'));
        define('TW_CONSUMER_SECRET', __c('tw_consumer_secret'));
    }

    /**
     * Check instance and redirect on errors to a special error page
     */
    protected function checkInstance($aa_instance)
    {
        $timezone                = new \DateTimeZone(APP_BASIC_TIMEZONE);
        $current_date            = new \DateTime('now', $timezone);
        $aa_inst_expiration_date = new \DateTime($aa_instance->expiration_date, $timezone);

        if (empty($aa_instance->i_id) && $aa_instance === 'instance not activated')
        {
            $this->redirect('/expired/');
        }
        elseif ($aa_inst_expiration_date < $current_date)
        {
            $this->redirect('/expired/');
        }
        elseif (empty($aa_instance->i_id) && $aa_instance === 'instance not exist')
        {
            $this->redirect('/error/');
        }
        elseif (empty($aa_instance->i_id))
        {
            $this->redirect('/error/');
        }
    }

    /**
     * Redirect (overwritten the slim standard)
     *
     * This method immediately redirects to a new URL. By default,
     * this issues a 302 Found response; this is considered the default
     * generic redirect response. You may also specify another valid
     * 3xx status code if you want. This method will automatically set the
     * HTTP Location header for you using the URL parameter.
     *
     * @param  string $url    The destination URL
     * @param  int    $status The HTTP redirect status code (optional)
     */
    public function redirect($url = '/', $status = 302)
    {
        $this->cleanBuffer();
        $this->response->setStatus($status);
        $this->response->setBody($this->response->getMessageForCode($status));
        header("HTTP/" . $this->config('http.version') . " " . $this->response->getMessageForCode($status));
        header("Location: " . $this->_request->getRootUri() . $url);
        header("Connection: close");
        exit();
    }
}