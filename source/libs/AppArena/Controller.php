<?php
namespace Apparena;

// include apparena helper functions
require ROOT_PATH . '/libs/AppArena/Helper/aa_helper.php';

Class Controller extends \Slim\Slim
{
    protected $_render = true;
    protected $_status = 200;
    protected $_request;
    protected $_data = array();
    protected $_sign_request = null;
    protected $_link_count = 0;

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
        $this->map('/(:wildcard+)', function ()
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
            'meta_title'       => __c('general_title'),
            'meta_description' => __c('general_desc'),
            'meta_canonical'   => $this->_request->getScheme() . '://' . $this->_request->getHost() . $this->_request->getPath(),
            'layout_css'       => $this->_request->getRootUri() . '/' . \Apparena\App::$i_id . '/assets/css/style/',
        ), $data);
        echo $this->render($this->config('templates.base'), $settings, $status);
    }

    protected function defineApi()
    {
        \Apparena\App::$api = \Apparena\Api\AppManager::init(array(
            'aa_app_id'     => APP_ID,
            'aa_app_secret' => APP_SECRET,
            'i_id'          => \Apparena\App::$i_id,
            'locale'        => \Apparena\App::$locale
        ));
    }

    protected function callApi()
    {
        $this->defineApi();
        $instance = \Apparena\Api\Instance::init();
        $api      = \Apparena\App::$api;

        // fill instance class and get data by api
        $instance->data   = $api->getInstance('data');
        $instance->config = $api->getConfig('data');
        $instance->locale = $api->getTranslation('data');

        // add additionals
        $instance->addData(array('page_tab_url' => ''));
        if(!empty($instance->data->fb_page_url))
        {
            $instance->addData(array('page_tab_url' => $instance->data->fb_page_url . '?sk=app_' . $instance->data->fb_app_id));
        }
        $instance->addData(array('share_url' => $instance->data->fb_canvas_url . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/share/'));

        $instance = $this->defineInstanceEnv($instance);

        if ($this->isFacebook())
        {
            $instance = $this->defineInstanceFb($instance);
        }

        if(!defined('CHECKINSTANCE'))
        {
            $this->checkInstance($instance->data);
        }
        if (!defined('CHECKBROWSER'))
        {
            $this->checkBrowserSupport($instance->env);
        }

        // define some basic constance's that we get over config values
        define('GP_CLIENT_ID', __c('gp_client_id'));
        define('GP_API_KEY', __c('gp_api_key'));
        define('TW_CONSUMER_KEY', __c('tw_consumer_key'));
        define('TW_CONSUMER_SECRET', __c('tw_consumer_secret'));
    }

    protected function defineInstanceFb($instance)
    {
        $fb_data = array(
            "is_fb_user_admin" => \Apparena\Helper\Facebook::is_fb_user_admin(),
            "is_fb_user_fan"   => \Apparena\Helper\Facebook::is_fb_user_fan(),
            "signed_request"   => $this->_sign_request,
        );
        if (isset($this->_sign_request->page))
        {
            $fb_data['page'] = $this->_sign_request->page;
        }
        if (isset($this->_sign_request->user))
        {
            $fb_data['user'] = $this->_sign_request->user;
        }
        if (isset($this->_sign_request->user_id))
        {
            $fb_data['fb_user_id'] = $this->_sign_request->user_id;
        }
        $instance->facebook = new \stdClass();
        foreach ($fb_data as $key => $value)
        {
            #$fb[$key] = $value;
            $instance->facebook->$key = $value;
        }

        return $instance;
    }

    protected function defineInstanceEnv($instance)
    {
        $instance->env           = $this->environment;
        $instance->env->mode     = $this->getMode();
        $instance->env->base_url = $instance->data->fb_canvas_url . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/';
        $instance->env->base     = 'website';

        if ($this->isFacebook())
        {
            if (isset($this->_sign_request->page))
            {
                $instance->env->base_url = $instance->data->page_tab_url;
                $instance->env->base     = 'page';
            }
            else
            {
                $instance->env->base_url = "https://apps.facebook.com/" . $instance->data->fb_app_namespace . "/?i_id=" . \Apparena\App::$i_id;
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
        $instance->env->browser = getBrowser();

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
            $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/expired/');
        }
        elseif ($aa_inst_expiration_date < $current_date)
        {
            $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/expired/');
        }
        elseif ($aa_instance->active === '0')
        {
            $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/expired/');
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
     * check browser version and redirect on old browser
     */
    protected function checkBrowserSupport($env)
    {
        $browser        = strtolower($env->browser->name);
        $version        = strtolower($env->browser->version);
        $device         = strtolower($env->device->type);
        $check_settings = require_once ROOT_PATH . '/configs/browser-config.php';

        $checkBrowser = function ($device) use ($check_settings, $browser, $version)
        {
            // if no settings exist for this browser, return true
            if (!is_array($check_settings[$device]) || empty($check_settings[$device][$browser]))
            {
                return true;
            }

            if (is_array($check_settings[$device]) && !empty($check_settings[$device][$browser]))
            {
                $check_settings = $check_settings[$device];
                if ($check_settings[$browser]['operator'] === '>' && $check_settings[$browser]['version'] > $version)
                {
                    return true;
                }
                elseif ($check_settings[$browser]['operator'] === '<' && $check_settings[$browser]['version'] < $version)
                {
                    return true;
                }
                elseif ($check_settings[$browser]['version'] == $version)
                {
                    return true;
                }
            }

            return false;
        };

        if (!$this->request->isAjax() && __c('activate_browser_detection') === '1' && ($checkBrowser('all') === false || $checkBrowser($device) === false))
        {
            $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/browser/');
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

    protected function getRespClass()
    {
        $this->_link_count++;
        if ($this->_link_count === 1)
        {
            return array(
                'normal'   => 'hidden-sm /*visible-xs*/',
                'dropdown' => 'visible-sm',
            );
        }
        elseif ($this->_link_count > 1 && $this->_link_count < 4)
        {
            return array(
                'normal'   => 'hidden-md hidden-sm /*visible-xs*/',
                'dropdown' => 'visible-md visible-sm',
            );
        }
        else
        {
            return array(
                'normal'   => 'visible-xs',
                'dropdown' => 'hidden-xs',
            );
        }
    }

    /**
     * define basic variables and settings for layout rendering
     */
    protected function addBasicLayoutData()
    {
        $instance    = \Apparena\Api\Instance::init();
        $custom_tabs = explode(',', __c('navigation_pagetab_selector'));
        $links       = array();
        $class       = $this->getRespClass();
        $links[]     = array(
            'url'        => '#',
            'main_class' => 'home',
            'resp_class' => $class['normal'],
            'drop_class' => $class['dropdown'],
            'text'       => __t('home'),
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
                    $class   = $this->getRespClass();
                    $links[] = array(
                        'url'        => $url,
                        'main_class' => $identifier,
                        'resp_class' => $class['normal'],
                        'drop_class' => $class['dropdown'],
                        'text'       => __c($identifier . '_name')
                    );
                }

                if ($identifier === 'imprint' || $identifier === '' || $identifier === 'privacy' || $identifier === 'terms')
                {
                    $class   = $this->getRespClass();
                    $links[] = array(
                        'url'        => '#/mod/static/' . $identifier,
                        'main_class' => 'app-' . $identifier,
                        'resp_class' => $class['normal'],
                        'drop_class' => $class['dropdown'],
                        'text'       => __t($identifier)
                    );
                }
            }
        }

        // add greetingcards
        if (__c('greetingcard_activated') === '1')
        {
            $class   = $this->getRespClass();
            $links[] = array(
                'url'        => '#/mod/greetingcards',
                'main_class' => 'greetingcards',
                'resp_class' => $class['normal'],
                'drop_class' => $class['dropdown'],
                'text'       => __t('tab-greetingcards')
            );
        }
        // add profile
        if (__c('profile_activated') === '1')
        {
            $class   = $this->getRespClass();
            $links[] = array(
                'url'        => '#/mod/profile',
                'main_class' => 'nav-profile',
                'resp_class' => $class['normal'],
                'drop_class' => $class['dropdown'],
                'text'       => __t('profile')
            );
        }

        /**
         * some links only for mobile version
         */
        // admin
        $links[] = array(
            'url'        => '#/mod/admin',
            'main_class' => 'nav-admin hide',
            'resp_class' => 'visible-xs',
            'drop_class' => 'hide',
            'text'       => __t('admin')
        );
        // profile
        $links[] = array(
            'url'        => '#/mod/profile',
            'main_class' => 'nav-admin hide',
            'resp_class' => 'visible-xs',
            'drop_class' => 'hide',
            'text'       => __t('settings')
        );
        // login
        $links[] = array(
            'url'        => '#/app/login',
            'main_class' => 'nav-login',
            'resp_class' => 'visible-xs',
            'drop_class' => 'hide',
            'text'       => __t('login')
        );
        // logout
        $links[] = array(
            'url'        => '#/mod/auth/logout',
            'main_class' => 'nav-logout hide',
            'resp_class' => 'visible-xs',
            'drop_class' => 'hide',
            'text'       => __t('logout')
        );

        // define dropdown showing
        $show_dropdown = '';
        if ($this->_link_count <= 3)
        {
            $show_dropdown = 'hidden-lg';
        }
        elseif ($this->_link_count <= 1)
        {
            $show_dropdown = 'hidden-md hidden-sm';
        }

        $navigation = array(
            'customer_logo_square' => __c('customer_logo_square', 'src'),
            'navi'                 => __t('navi'),
            'links'                => $links,
            'app_i_id'             => \Apparena\App::$i_id,
        );
        // show login and admin buttons only if auth module is installed
        if (file_exists('modules/aa_app_mod_auth/'))
        {
            $navigation['login'] = $this->render('sections/nav_login', array(
                'login'            => __t('login'),
                'admin'            => __t('admin'),
                //'logout'      => __t('logout'),
                'user'             => md5(''),
                'show_login'       => '',
                'show_admin'       => 'hide',
                'show_profil'      => 'hide',
                'show_logout'      => 'hide',
                'show_dropdown'    => $show_dropdown,
                'settings'         => __t('settings'),
                'settings_popover' => $this->render('sections/popovers/settings', array(
                        'logout' => __t('logout')
                    )),
            ));
        }

        // language
        $languages = explode(',', __c('language_selection'));
        if (is_array($languages) && count($languages) > 1)
        {
            $language_elements = array();
            foreach ($languages AS $locale)
            {
                if (\Apparena\App::$locale !== $locale)
                {
                    $language_elements[] = array(
                        'flag' => $this->render('sections/nav_language_element', array(
                                'url'   => str_replace(\Apparena\App::$locale, $locale, $instance->data->share_url) . $instance->env->base . '/',
                                'class' => $locale,
                                'name'  => __t('lang_' . $locale),
                            ))
                    );
                }
            }

            $navigation['language'] = $this->render('sections/nav_language', array(
                'name'     => ($instance->env->device->type === 'mobile') ? __t('language') : '',
                'position' => ($instance->env->device->type !== 'mobile') ? 'pull-right' : '',
                'locale'   => \Apparena\App::$locale,
                'flags'    => $language_elements,
            ));
        }

        $this->_data = array_merge($this->_data, array(
            'url_path'          => $this->environment()->offsetGet('SCRIPT_NAME'),
            'app_navigation'    => $this->render('sections/navigation', $navigation),
            'app_terms_box'     => $this->render('sections/terms_box', array('link' => __t('footer_terms', '<a href="#/mod/static/terms">' . __t('terms') . '</a>'))),
            'app_i_id'          => \Apparena\App::$i_id,
            'app_base_path'     => $this->environment()->offsetGet('SCRIPT_NAME'),
            'app_url_path'      => $this->_request->getPath(),
            'layout_body_class' => __c('background_image_select'),
        ));

        if (strpos(__c('navigation_pagetab_selector'), 'terms') !== false)
        {
            $this->_data['terms'] = '<li><a href="#/mod/static/terms"><span class="name">' . __t('terms') . '</span></a></li>';
        }
        if (strpos(__c('navigation_pagetab_selector'), 'privacy') !== false)
        {
            $this->_data['privacy'] = '<li><a href="#/mod/static/privacy"><span class="name">' . __t('privacy') . '</span></a></li>';
        }
        if (strpos(__c('navigation_pagetab_selector'), 'imprint') !== false)
        {
            $this->_data['imprint'] = '<li><a href="#/mod/static/imprint"><span class="name">' . __t('imprint') . '</span></a></li>';
        }

        if (__c('branding_activated') === '1')
        {
            $this->_data['app_branding_footer'] = $this->render('sections/branding_box', array(
                'content' => __c('branding_footer'),
            ));
        }

        // set global body classes
        // set device type
        if (!empty($instance->env->device->type))
        {
            $this->_data['layout_body_class'] .= ' ' . strtolower($instance->env->device->type);
        }
        // set env base
        if (!empty($instance->env->base))
        {
            $this->_data['layout_body_class'] .= ' ' . strtolower($instance->env->base);
        }
        // set browser
        if (!empty($instance->env->browser))
        {
            $this->_data['layout_body_class'] .= ' ' . strtolower($instance->env->browser->name);
            $this->_data['layout_body_class'] .= ' ' . strtolower($instance->env->browser->platform);
        }
        $this->_data['layout_body_class'] = trim($this->_data['layout_body_class']);
    }

    /**
     * check facebook sign request
     * @return bool
     */
    protected function isFacebook()
    {
        $signed_request = $this->request->params('signed_request');
        // check signed_request
        if (!empty($signed_request))
        {
            $this->_sign_request = \Apparena\Helper\Facebook::parse_signed_request($signed_request);
            if (!is_null($this->_sign_request))
            {
                $this->_sign_request->sign_request = $signed_request;
                \Apparena\App::$_signed_request    = $this->_sign_request;

                if (!empty($this->_sign_request->app_data))
                {
                    \Apparena\App::$_app_data = json_decode($this->_sign_request->app_data);
                }

                return true;
            }
        }

        return false;
    }
}