<?php
// todo: put all this stuff in separate files or better in classes!

// cache busting
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/**
 * Setup the environment
 */
date_default_timezone_set('Europe/Berlin'); // Set timezone
ini_set('session.gc_probability', 0); // Disable session expired check
header('P3P: CP=CAO PSA OUR'); // Fix IE save cookie in iframe problem

define("ROOT_PATH", realpath(dirname(__FILE__))); // Set include path
define('_VALID_CALL', 'true');

$ajax_request = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
{
    $ajax_request = true;
}
define('AJAX', $ajax_request);

set_include_path(ROOT_PATH . '/libs/' . PATH_SEPARATOR);

/**
 * Include necessary libraries
 */
if (file_exists(ROOT_PATH . '/configs/app-config.php'))
{
    require_once ROOT_PATH . '/configs/app-config.php';

    if (ENV_MODE === 'dev')
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(-1);
    }
}
else
{
    throw new Exception('Config file not exist. Please rename app-config_sample.php to app-config.php in /configs/ and fill it with live ;)');
}
require_once ROOT_PATH . '/libs/AppArena/Helper/aa_helper.php';
require_once ROOT_PATH . '/libs/AppArena/Helper/fb_helper.php';
require_once ROOT_PATH . '/libs/AppArena/AppManager/AppManager.php';
//require_once ROOT_PATH . '/libs/Zend/Translate.php';

// register global and magic quote escaping
global_escape();

/* Try to init some basic variables */
$aa         = new stdClass();
$aa_inst_id = false;
$dev_message = array();

/**
 * Setup mysql database connection
 */
if ($db_activated)
{
    require_once ROOT_PATH . '/libs/AppArena/System/class.database.php';

    try
    {
        $db = new \com\apparena\system\Database($db_user, $db_host, $db_name, $db_pass, $db_option);
        // set all returned value keys to lower cases
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        // return all query requests automatically as object
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
    catch (PDOException $e)
    {
        if (ENV_MODE !== 'product')
        {
            echo '<pre>';
            print_r($e->getMessage());
            echo '</pre>';
        }
        exit();
    }
}

// Try to get the instance id from GET-Parameter
if (!empty($_REQUEST['aa_inst_id']))
{
    $aa_inst_id = $_REQUEST['aa_inst_id'];
}

// check redirections for friendsrequests
// ToDo
//require_once ROOT_PATH . '/fb_friendsrequest_return.php';

/* Initialize and set Facebook information in the session */
if (isset ($_REQUEST["signed_request"]))
{
    $aa->fb            = array();
    $fb_signed_request = parse_signed_request($_REQUEST["signed_request"]);
    $is_fb_user_admin  = is_fb_user_admin();
    $is_fb_user_fan    = is_fb_user_fan();
    $fb_data           = array(
        "is_fb_user_admin" => $is_fb_user_admin,
        "is_fb_user_fan"   => $is_fb_user_fan,
        "signed_request"   => $fb_signed_request,
    );
    if (isset($fb_signed_request['page']))
    {
        $fb_data['page'] = $fb_signed_request['page'];
    }
    if (isset($fb_signed_request['user']))
    {
        $fb_data['user'] = $fb_signed_request['user'];
    }
    if (isset($fb_signed_request['user_id']))
    {
        $fb_data['fb_user_id'] = $fb_signed_request['user_id'];
    }
    foreach ($fb_data as $k => $v)
    {
        $aa->fb[$k] = $v;
    }
}

/* Initialize localization */
$change_lang = true;
$cur_locale  = $aa_default_locale;
if (!empty($aa->fb['signed_request']['app_data']))
{
    $app_data = json_decode($aa->fb['signed_request']['app_data'], true);
}

if (!empty($_GET['locale']))
{
    $cur_locale  = $_GET['locale'];
    $change_lang = false;
}
else
{
    if (!empty($app_data['locale']))
    {
        $cur_locale  = $app_data['locale'];
        $change_lang = false;
    }
    else
    {
        if (!empty($aa_inst_id) && !empty($_COOKIE['aa_inst_locale_' . $aa_inst_id]))
        {
            $cur_locale  = $_COOKIE['aa_inst_locale_' . $aa_inst_id];
            $change_lang = false;
        }
    }
}

/*  Connect to App-Arena.com App-Manager and init session */
$appmanager = new com\apparena\api\AA_AppManager(array(
                                                      'aa_app_id'     => $aa_app_id,
                                                      'aa_app_secret' => $aa_app_secret,
                                                      'aa_inst_id'    => $aa_inst_id,
                                                      'locale'        => $cur_locale
                                                 ));

/* Start session and initialize App-Manager content */
$aa_instance = $appmanager->getInstance('data');

/* Catch error, in case there is no instance */
if (empty($aa_instance->i_id) && $aa_instance === 'instance not activated')
{
    throw new Exception('instance not activated');
}
elseif (empty($aa_instance->i_id) && $aa_instance === 'instance not exist')
{
    throw new Exception('instance not exist');
}
elseif (empty($aa_instance->i_id))
{
    throw new Exception('aa_inst_id not given or wrong in ' . __FILE__ . ' in line ' . __LINE__);
}

if (empty($aa_inst_id))
{
    $aa_inst_id = $aa_instance->i_id;
}

$aa_scope = 'aa_' . $aa_inst_id;
session_name($aa_scope);
session_start();

//$fb_temp = $aa->fb;

$aa->instance               = $aa_instance;
$aa->instance->page_tab_url = $aa->instance->fb_page_url . "?sk=app_" . $aa->instance->fb_app_id;
$aa->instance->share_url    = $aa->instance->fb_canvas_url . "share.php?aa_inst_id=" . $aa_inst_id;

if ($change_lang)
{
    $cur_locale = $aa->instance->locale;
    $appmanager->setLocale($cur_locale);
}
$aa->locale = $appmanager->getTranslation('data');

$aa->config = $appmanager->getConfig('data');
//$aa->fb     = $fb_temp;

// store locale value in cookie
setcookie('aa_inst_locale_' . $aa_inst_id, $cur_locale, 0, '/', $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), true);

$current_date            = new DateTime('now', new DateTimeZone($aa_default_timezone));
$aa_inst_expiration_date = new DateTime($aa->instance->expiration_date, new DateTimeZone($aa_default_timezone));
// if instance is expired, redirect to expired.php
if ($aa_inst_expiration_date < $current_date && !defined('REDIRECT'))
{
    hrd('expired.php?aa_inst_id=' . $aa_inst_id);
}

if (__c('use_only_https') === '1' && isSSL() === false)
{
    $url = str_replace('http://', 'https://', $aa->instance->fb_canvas_url . "?aa_inst_id=" . $aa_inst_id);
    if (ENV_MODE === 'product')
    {
        hrd($url);
    }
}

/* Collect environment information */
require_once 'libs/Mobile_Detect.php';
$aa->env = new stdClass();
$detector = new Mobile_Detect;
if (isset($_REQUEST['signed_request']))
{
    if (isset($fb_signed_request['page']))
    {
        $aa->env->base_url = $aa->instance->page_tab_url;
        $aa->env->base     = 'page';
    }
    else
    {
        $aa->env->base_url = "https://apps.facebook.com/" . $aa->instance->fb_app_url . "/?aa_inst_id=" . $aa_inst_id;
        $aa->env->base     = 'canvas';
    }
}
else
{
    $aa->env->base_url = $aa->instance->fb_canvas_url . "?aa_inst_id=" . $aa_inst_id;
    $aa->env->base     = 'website';
}
$aa->env->device         = new stdClass();
$aa->env->device->type = "desktop";
if ($detector->isMobile())
{
    $aa->env->device->type = 'mobile';
}
if ($detector->isTablet())
{
    $aa->env->device->type = 'tablet';
}
// Add browser info to the env
$aa->env->browser = getBrowser();

// check browser version and redirect on old browser
$browser = strtolower($aa->env->browser->name);
$version = strtolower($aa->env->browser->version);
$device  = strtolower($aa->env->device->type);

// ToDo[maXus]: put this in a config file - 02.10.13
if (!defined('REDIRECT')
    && (($browser === 'firefox' && $version < 5)
        || ($browser === 'msie' && $version < 8)
        || ($browser === 'chrome' && $version < 10)
        || ($browser === 'opera' && $version < 10)
        || ($browser === 'safari' && $version < 5 && $device === 'desktop')
        || ($browser === 'netscape'))
)
{
    if (__c('activate_browser_detection') === '1')
    {
        hrd('browser.php?aa_inst_id=' . $aa_inst_id);
    }
}

/* Setup the translation objects */
/*$aa_locale = new Zend_Translate('array', (array) $aa->locale, $cur_locale);
$aa_locale->setLocale($cur_locale);

$aa_translate            = new StdClass();
$aa_translate->translate = $aa_locale;*/

// set global body classes
$classbody = '';
// set device type
if (!empty($aa->env->device->type))
{
    $classbody .= ' ' . strtolower($aa->env->device->type);
}
// set env base
if (!empty($aa->env->base))
{
    $classbody .= ' ' . strtolower($aa->env->base);
}
// set browser
if (!empty($aa->env->browser))
{
    $classbody .= ' ' . strtolower($aa->env->browser->name);
    $classbody .= ' ' . strtolower($aa->env->browser->platform);
}
$classbody = trim($classbody);

if (AJAX === false && !defined('REDIRECTION'))
{
    /**
     * compile less from appmanager
     */
    require_once ROOT_PATH . '/configs/css-config.php';

    $css_file                  = array();
    $css_file['checksum_path'] = ROOT_PATH . '/tmp/cache/css_' . md5('style_md5' . $aa_inst_id);
    $css_file['name']          = 'tmp/cache/css_' . md5('style' . $aa_inst_id) . '.css';

    if (!file_exists($css_file['checksum_path']))
    {
        file_put_contents(ROOT_PATH . '/' . $css_file['name'], '');
        file_put_contents($css_file['checksum_path'], md5(''));
        file_put_contents($css_file['checksum_path'] . '_int', md5(''));
    }
    $css_file['value']             = file_get_contents(ROOT_PATH . '/' . $css_file['name']);
    $css_file['checksum_config']   = file_get_contents($css_file['checksum_path']);
    $css_file['checksum_internal'] = file_get_contents($css_file['checksum_path'] . '_int');

    // build checksum from config values
    // get all sources and put them into a collection variable
    $css_collector = '';
    if (is_array($css_import))
    {
        foreach ($css_import AS $import_file => $type)
        {
            if ($import_file === 'main')
            {
                $css_collector .= file_get_contents(ROOT_PATH . $type);
                continue;
            }

            if ($type === 'file' && file_exists(ROOT_PATH . $import_file))
            {
                $css_collector .= file_get_contents(ROOT_PATH . $import_file);
                continue;
            }

            if ($type === 'config' && __c($import_file) !== false)
            {
                $css_collector .= __c($import_file);
                continue;
            }
        }
    }
    $config_checksum = md5($css_collector . __c('app_base_color') . __c('app_base_color_bright'));
    $css_checksum    = $css_file['checksum_internal'];
    if (!empty($css_import['main']) && file_exists(ROOT_PATH . $css_import['main']))
    {
        $css_checksum = md5(file_get_contents(ROOT_PATH . $css_import['main']));
    }

    if (ENV_MODE === 'dev')
    {
        // only for development and debugging
        if ($config_checksum !== $css_file['checksum_config'])
        {
            $dev_message[] = 'config ist unterschiedlich';
            $dev_message[] = $config_checksum . ' !== ' . $css_file['checksum_config'];
        }
        if ($css_checksum !== $css_file['checksum_internal'])
        {
            $dev_message[] = 'internal ist unterschiedlich';
            $dev_message[] = $css_checksum . ' !== ' . $css_file['checksum_internal'];
        }
    }

    if ($config_checksum !== $css_file['checksum_config'] || $css_checksum !== $css_file['checksum_internal'])
    {
        if (ENV_MODE === 'dev')
        {
            // only for development and debugging
            $dev_message[] = 'compile';
        }
        require_once ROOT_PATH . '/libs/lessc.inc.php';

        // init lessphp compiler
        $less = new lessc;
        // compress type
        $less->setFormatter("compressed");
        /**
         * remove block comments with false,
         * otherwise true to let comment blocks stay and put them to the top
         * (for licence informations and so on)
         * Block comments are / !* ... * / (without spaces)
         */
        $less->setPreserveComments(false);

        // replace some appmanager variables with right css/less code
        foreach($css_path_replacements AS $search => $replace)
        {
            $css_collector = str_replace($search, $replace, $css_collector);
        }

        // compile collection variable and save them as file
        $compiled_source = $less->compile($css_collector);
        file_put_contents(ROOT_PATH . '/' . $css_file['name'], $compiled_source);

        // check chmod and change them if them needed
        $perms = substr(sprintf('%o', fileperms(ROOT_PATH . '/' . $css_file['name'])), -4);
        if ((int)$perms < 644)
        {
            chmod(ROOT_PATH . '/' . $css_file['name'], 0644);
        }

        // create checksum to stop compiling on each app call
        file_put_contents($css_file['checksum_path'], $config_checksum);
        file_put_contents($css_file['checksum_path'] . '_int', $css_checksum);
    }
}

define('GP_CLIENT_ID', __c('gp_client_id'));
define('GP_API_KEY', __c('gp_api_key'));
define('TW_CONSUMER_KEY', __c('tw_consumer_key'));
define('TW_CONSUMER_SECRET', __c('tw_consumer_secret'));