<?php
namespace Apparena\Controllers;

Class Assets extends \Apparena\Controller
{
    public function before($i_id = 0, $lang = APP_DEFAULT_LOCALE)
    {
        parent::before($i_id, $lang);
        $this->_render = false;
        $this->callApi();
    }

    public function cssAction($id, $filename)
    {
        switch ($filename)
        {
            case 'style':
                $this->style();
                break;
        }
    }

    public function jsAction($id, $lang, $filename)
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

        $res                              = $this->response();
        $res['Content-Description']       = 'File Transfer';
        $res['Content-Type']              = 'text/css';
        $res['Content-Disposition']       = 'attachment; filename=style.css';
        $res['Content-Transfer-Encoding'] = 'binary';
        $res['Expires']                   = '0';
        $res['Cache-Control']             = 'must-revalidate';
        $res['Pragma']                    = 'public';

        $this->response->write($css->getCompiled());
    }

    protected function apimodel()
    {
        // ToDo: Add FB stuff
        $aa      = \Apparena\Api\Instance::init();
        $aaForJs = (object)array(
            'locale'   => $aa->locale,
            'config'   => $aa->config,
            'instance' => $aa->data,
            'env'      => $this->environment,
            'fb'       => false,
            'app_data' => false,
            'params'   => false,
            'user'     => (object)array(
                    'ip'    => $this->request()->getIp(),
                    'agent' => $this->request()->getUserAgent()
                ),
            'gp'       => (object)array(
                    'api_key'   => GP_API_KEY,
                    'client_id' => GP_CLIENT_ID
                )
        );

        if ($this->isFacebook())
        {
            $aaForJs->app_data = \Apparena\App::$_app_data;
        }

        if (isset($aa->facebook))
        {
            $aaForJs->fb = $aa->facebook;
            /*$aaForJs->fb->request_id       = $fb_request_id;
            $aaForJs->fb->invited_by       = $fb_invited_by;*/
        }

        // save current time as timestamp in JS varible to handle temporary uid
        $aaForJs->timestamp = \Apparena\App::getCurrentDate();
        // create a unique id to use as temporary uid
        $aaForJs->uid_temp = md5(\Apparena\App::$i_id . uniqid() . \Apparena\App::getCurrentDate()->getTimestamp());

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

        // generate admin key for admin button
        $aaForJs->custom = (object)array('admin_key' => md5(\Apparena\App::$i_id . '_' . APP_SECRET));

        $res                              = $this->response();
        $res['Content-Description']       = 'File Transfer';
        $res['Content-Type']              = 'application/json';
        $res['Content-Disposition']       = 'attachment; filename=api.json';
        $res['Content-Transfer-Encoding'] = 'binary';
        $res['Expires']                   = '0';
        $res['Cache-Control']             = 'must-revalidate';
        $res['Pragma']                    = 'public';

        $this->response->write(json_encode($aaForJs));
    }
}
