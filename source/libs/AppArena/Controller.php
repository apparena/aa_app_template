<?php
namespace Apparena;

Class Controller extends \Slim\Slim
{
    #protected $data;

    public function __construct()
    {
        $settings = require ROOT_PATH . '/configs/slim-config.php';
        /*if (isset($settings['model']))
        {
            $this->data = $settings['model'];
        }*/
        parent::__construct($settings);

        if (!empty($_SERVER['APP_ENV']))
        {
            parent::config('mode', $_SERVER['APP_ENV']);
        }
    }

    public function render($name, $data = array(), $status = null)
    {
        if (strpos($name, '.phtml') === false)
        {
            $name = $name . '.phtml';
        }
        parent::render($name, $data, $status);
    }
}

