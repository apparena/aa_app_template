<?php
namespace Apparena\Controllers;

Class Assets extends \Apparena\Controller
{
    public function cssAction($id, $filename)
    {
        switch ($filename)
        {
            case 'style':
                $this->style();
                break;
        }
        $this->_render = false;
    }

    public function jsAction($id, $filename)
    {
        switch ($filename)
        {
            case 'api':
                $this->apimodel();
                break;
        }
        $this->_render = false;
    }

    protected function style()
    {
        $css = new \Apparena\Helper\Css();
        echo $css->getCompiled();
    }

    protected function apimodel()
    {
        $aaForJs = (object)array(
            'locale'   => $aa->locale,
            'config'   => $aa->config,
            'instance' => $aa->instance,
            'env'      => $aa->env,
            'fb'       => false,
            'app_data' => false,
/*            'user'     => (object)array(
                    'ip'    => get_client_ip(),
                    'agent' => $_SERVER['HTTP_USER_AGENT']
                ),*/
            'gp'       => (object)array(
                    'api_key'   => GP_API_KEY,
                    'client_id' => GP_CLIENT_ID
                )
        );

        #$aaForJs->env->mode = ENV_MODE;

        /*if (isset($aa->fb))
        {
            $aaForJs->fb                   = $aa->fb;
            $aaForJs->fb->request_id       = $fb_request_id;
            $aaForJs->fb->invited_by       = $fb_invited_by;
            $aaForJs->fb->invited_for_door = $invited_for_door;
        }

        if (!empty($_GET['app_data']))
        {
            $aaForJs->app_data = $_GET['app_data'];
        }
        else
        {
            if (!empty($fb_signed_request['app_data']))
            {
                $aaForJs->app_data = $fb_signed_request['app_data'];
            }
        }*/

        // save current time as timestamp in JS varible to handle temporary uid
        $aaForJs->timestamp = $current_date->getTimestamp();
        // create a unique id to use as temporary uid
        $aaForJs->uid_temp = md5($i_id . uniqid() . $current_date->getTimestamp());

        // delete some important variables
        if (isset($aaForJs->instance->aa_app_secret))
        {
            unset($aaForJs->instance->aa_app_secret);
        }
        if (isset($aaForJs->instance->fb_app_secret))
        {
            unset($aaForJs->instance->fb_app_secret);
        }

        // add basic app admins
        if (isset($aaForJs->config->admin_mails))
        {
            $aaForJs->config->admin_mails->value = $aaForJs->config->admin_mails->value . ',' . APP_ADMINS;
        }
        else
        {
            pr('Missing app wizard config "admin_mails"');
        }

        // show admin button or login form
        $show_admin  = 'hide';
        $show_profil = 'hide';
        $show_login  = '';
        $show_logout = 'hide';
        if (!empty($_SESSION['login']['gid']) && $_SESSION['login']['gid'] === 'admin')
        {
            $show_admin  = '';
            $show_profil = '';
            $show_login  = 'hide';
        }
        elseif (!empty($_SESSION['login']['gid']) && $_SESSION['login']['gid'] === 'user')
        {
            $show_admin  = 'hide';
            $show_login  = 'hide';
            $show_profil = '';
        }
        $user = '';
        if (!empty($_SESSION['login']['user']['mail']))
        {
            $user = $_SESSION['login']['user']['mail'];
        }

        // generate admin key for admin button
        $aaForJs->custom = (object)array('admin_key' => md5($i_id . '_' . $aa_app_secret));
    }
}
