<?php
namespace Apparena\Controllers;

Class Main extends \Apparena\Controller
{
    public function indexAction()
    {
        $this->callApi();
        $this->addBasicLayoutData();
    }

    public function missingIdAction()
    {
        $this->config('templates.base', 'error');
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
        $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$_locale);
    }
}
