<?php
namespace Apparena\Controllers;

Class Main extends \Apparena\Controller
{
    public function indexAction()
    {
        $content = $this->render("index", array("title" => 'Startseite', "name" => "Home"));
        $this->display(array('app_content' => $content));
    }

    public function missingIdAction()
    {
        $this->config('templates.base', 'error');
        $this->display(array(
            "title" => 'Ohhh damn!',
            "desc"  => "Your instance ID was not found in our archive. Sorry for that!"
        ), 404);
    }

    public function browserAction()
    {
        $this->config('templates.base', 'browser');
        $this->display(array());
    }

    public function expiredAction()
    {
        $this->config('templates.base', 'expired');
        $this->display(array());
    }

    public function notFoundAction()
    {
        $this->config('templates.base', '404');
        $this->display(array(), 404);
    }
}

