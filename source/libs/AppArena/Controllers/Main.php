<?php
namespace Apparena\Controllers;

Class Main extends \Apparena\Controller
{
    public function indexAction()
    {
        $this->callApi();
        $this->_data = array('app_content' => $this->render('pages/index'));
        $this->addBasicLayoutData();
    }

    public function MissingIdAction()
    {
        if ($this->isFacebook())
        {
            /*$fb_data = array(
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
            $fb = array();+
            foreach ($fb_data as $key => $value)
            {
                $fb[$key] = $value;
            }*/

            $this->defineApi();
            \Apparena\App::$api->setFbPageId($this->_sign_request->page->id);
            $instance = (array) \Apparena\App::$api->getInstanceFromFacebook('data');
            if(!empty($instance[0]))
            {
                $instance = $instance[0];
            }

            // redirect
            if ($instance->activate === '1')
            {
                $this->redirect('/' . $instance->i_id . '/' . $instance->locale . '/', 301);
            }
            else
            {
                $this->redirect('/expired/', 301);
            }
        }

        $this->config('templates.base', 'pages/error');
        $this->_data   = array(
            'title' => 'Ohhh damn!',
            'desc'  => 'Your instance ID was not found in our archive. Sorry for that!'
        );
        $this->_status = 404;
    }

    public function browserAction()
    {
        $this->config('templates.base', 'browser');
    }

    public function expiredAction()
    {
        $this->config('templates.base', 'expired');
    }

    public function notFoundAction()
    {
        $this->_status = 404;
        $this->config('templates.base', $this->_status);
    }

    public function missingLanguageAction()
    {
        $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$locale);
    }
}
