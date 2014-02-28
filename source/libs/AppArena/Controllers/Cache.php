<?php
namespace Apparena\Controllers;

Class Cache extends \Apparena\Controller
{
    public function indexAction()
    {
        \Apparena\Helper\Cache::init()->clean('all');
    }

    public function instanceAction($i_id)
    {
        \Apparena\Helper\Cache::init()->clean($i_id);
    }

    public function before($i_id = 0, $lang = APP_DEFAULT_LOCALE)
    {
        // disable this method call
    }

    public function after()
    {
        // disable this method call
    }
}
