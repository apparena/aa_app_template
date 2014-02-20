<?php
namespace Apparena;

// include apparena helper functions
require ROOT_PATH . '/libs/AppArena/Helper/aa_helper.php';

Class Controller extends \Slim\Slim
{
    #protected $data;

    public function __construct()
    {
        #require_once ROOT_PATH . '/configs/app-config.php';
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
            'meta_title'       => $this->config('metatags')->meta_title,
            'meta_description' => $this->config('metatags')->meta_description,
            'meta_canonical'   => $this->config('metatags')->meta_canonical,
        ), $data);
        echo $this->render($this->config('templates.base'), $data, $status);
    }
}