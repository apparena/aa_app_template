<?php
namespace Apparena\Controllers;

Class Main extends \Apparena\Controller
{
    public function indexAction()
    {
        $this->render("index", array("title" => 'Startseite', "name" => "Home"));
    }

    public function browserAction()
    {
        #$this->render("test", array("title" => $title, "name" => "Test"));
    }

    public function expiredAction()
    {
        #$this->render("test", array("title" => "GET", "name" => "Test 2"));
    }

    public function notFoundAction()
    {
        $this->render("error", array(), 404);
    }
}

