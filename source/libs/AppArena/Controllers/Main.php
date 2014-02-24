<?php
namespace Apparena\Controllers;

Class Main extends \Apparena\Controller
{
    public function indexAction()
    {
        $this->callApi();
        $content     = $this->render("index", array("title" => 'Startseite', "name" => "Home"));
        $this->_data = array('app_content' => $content);
    }

    public function missingIdAction()
    {
        $this->config('templates.base', 'error');
        $this->_data   = array(
            "title" => 'Ohhh damn!',
            "desc"  => "Your instance ID was not found in our archive. Sorry for that!"
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
        $this->redirect('/' . \Apparena\App::$_i_id . '/' . \Apparena\App::$_locale);
    }
}
