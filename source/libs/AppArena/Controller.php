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
            'layout_css'       => $this->_request->getRootUri() . '/' . \Apparena\App::$i_id . '/assets/css/style/',
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

        // fill instance class and get data by api
        $instance->data   = \Apparena\App::$api->getInstance('data');
        $instance->config = \Apparena\App::$api->getConfig('data');
        $instance->locale = \Apparena\App::$api->getTranslation('data');
        $instance         = $this->defineInstanceEnv($instance);
        $this->checkInstance($instance->data);

        // add additionals
        $instance->addData(array('page_tab_url' => $instance->data->fb_page_url . "?sk=app_" . $instance->data->fb_app_id));
        $instance->addData(array('share_url' => $instance->data->fb_canvas_url . "share.php?i_id=" . \Apparena\App::$i_id));

        // define some basic constance's that we get over config values
        define('GP_CLIENT_ID', __c('gp_client_id'));
        define('GP_API_KEY', __c('gp_api_key'));
        define('TW_CONSUMER_KEY', __c('tw_consumer_key'));
        define('TW_CONSUMER_SECRET', __c('tw_consumer_secret'));
    }

    protected function defineInstanceEnv($instance)
    {
        $instance->env           = $this->environment;
        $instance->env->mode     = $this->getMode();
        $instance->env->base_url = $instance->data->fb_canvas_url . "?i_id=" . \Apparena\App::$i_id;
        $instance->env->base     = 'website';

        if (isset($_REQUEST['signed_request']))
        {
            if (isset($fb_signed_request['page']))
            {
                $instance->env->base_url = $instance->data->page_tab_url;
                $instance->env->base     = 'page';
            }
            else
            {
                $instance->env->base_url = "https://apps.facebook.com/" . $instance->data->fb_app_url . "/?i_id=" . \Apparena\App::$i_id;
                $instance->env->base     = 'canvas';
            }
        }

        include_once ROOT_PATH . '/libs/Mobile_Detect.php';
        $detector                    = new \Mobile_Detect;
        $instance->env->device       = new \stdClass();
        $instance->env->device->type = "desktop";
        if ($detector->isMobile())
        {
            $instance->env->device->type = 'mobile';
        }
        if ($detector->isTablet())
        {
            $instance->env->device->type = 'tablet';
        }
        // Add browser info to the env
        #$instance->env->browser = getBrowser();

        return $instance;
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

    protected function addBasicLayoutData()
    {
        $instance    = \Apparena\Api\Instance::init();
        $custom_tabs = explode(',', __c('navigation_pagetab_selector'));
        $links       = array();
        $links[]     = array(
            'url'   => '#/',
            'class' => 'home',
            'text'  => __t('home')
        );

        if (!empty($custom_tabs[0]))
        {
            foreach ($custom_tabs AS $identifier)
            {
                if (strpos($identifier, 'navigation_tab_') !== false)
                {
                    $url = __c($identifier . '_link_intern');
                    if ($identifier === 'navigation_tab_1' || $identifier === 'navigation_tab_2' || $identifier === 'navigation_tab_3')
                    {
                        $external_link = __c($identifier . '_link_extern');
                        if (!empty($external_link))
                        {
                            $url = $external_link;
                        }
                    }

                    $links[] = array(
                        'url'   => $url,
                        'class' => $identifier,
                        'text'  => __c($identifier . '_name')
                    );
                }

                if ($identifier === 'imprint' || $identifier === '' || $identifier === 'privacy' || $identifier === 'terms')
                {
                    $links[] = array(
                        'url'   => '#/page/app/' . $identifier,
                        'class' => 'app-' . $identifier,
                        'text'  => __t($identifier)
                    );
                }
            }
        }

        // add greetingcards
        if (__c('greetingcard_activated') === '1')
        {
            $links[] = array(
                'url'   => '#/page/greetingcards',
                'class' => 'greetingcards',
                'text'  => __t('tab-greetingcards')
            );
        }
        // add profile
        if (__c('greetingcard_activated') === '1')
        {
            $links[] = array(
                'url'   => '#/page/profile',
                'class' => 'nav-profile',
                'text'  => __t('profile')
            );
        }

        $navigation = array(
            'customer_logo_square' => __c('customer_logo_square'),
            'navi'                 => __t('navi'),
            'links'                => $links,
        );
        // show login and admin buttons only if auth module is installed
        if (file_exists('modules/aa_app_mod_auth/'))
        {
            $navigation['login'] = $this->render('sections/nav_login', array(
                'login'       => __t('login'),
                'admin'       => __t('admin'),
                'logout'      => __t('logout'),
                'user'        => md5(''),
                'show_login'  => '',
                'show_admin'  => 'hide',
                'show_profil' => 'hide',
                'show_logout' => 'hide',
            ));
        }

        // language
        $languages = explode(',', __c('language_selection'));
        if (is_array($languages) && count($languages) > 1)
        {
            $language_elements = array();
            foreach ($languages AS $locale)
            {
                if (\Apparena\App::$_locale !== $locale)
                {
                    $param_name  = 'locale';
                    $param_value = $locale;
                    if ($instance->env->base !== 'website')
                    {
                        $param_name  = 'app_data';
                        $param_value = urlencode('{"locale":"' . $locale . '"}');
                    }

                    $language_elements[] = array(
                        'flag' => $this->render('sections/nav_language_element', array(
                                'url'   => $instance->data->share_url . '&page=' . $instance->env->base . '&' . $param_name . '=' . $param_value,
                                'class' => \Apparena\App::$_locale,
                                'name'  => __t('lang_' . \Apparena\App::$_locale),
                            ))
                    );
                }
            }

            // ToDo: we need $device!
            $navigation['language'] = $this->render('sections/nav_language', array(
                'name'     => ('$device' === 'mobile') ? __t('language') : '',
                'position' => ('$device' !== 'mobile') ? 'pull-right' : '',
                'flags'    => $language_elements,
            ));
        }

        $this->_data = array_merge($this->_data, array(
            'url_path'       => $this->environment()->offsetGet('SCRIPT_NAME'),
            'app_navigation' => $this->render('sections/navigation', $navigation),
            'app_header'     => __c('header_custom'),
            'app_footer'     => __c('footer_custom'),
            'app_terms_box'  => $this->render('sections/terms_box', array('link' => __t('footer_terms', '<a href="#/page/app/terms">' . __t('terms') . '</a>'))),
            'app_i_id'       => \Apparena\App::$i_id,
            'app_base_path'  => $this->environment()->offsetGet('SCRIPT_NAME'),
            'app_locale'     => \Apparena\App::$_locale,
        ));

        if (__c('show_comments') === '1')
        {
            $this->_data['app_comments_box'] = $this->render('sections/comments_box', array(
                'title'           => __c('fb_comments_title'),
                'href'            => $instance->data->share_url,
                'comments_amount' => __c('fb_comments_amount'),
            ));
        }

        if (__c('branding_activated') === '1')
        {
            $this->_data['app_branding_footer'] = $this->render('sections/branding_box', array(
                'content' => __c('branding_footer'),
            ));
        }
    }
}