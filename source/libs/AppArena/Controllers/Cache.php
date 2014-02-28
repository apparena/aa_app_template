<?php
namespace Apparena\Controllers;

Class Cache extends \Apparena\Controller
{
    public function indexAction()
    {
        $this->_render = false;
        \Apparena\Helper\Cache::init()->clean('all');
    }

    public function instanceAction($i_id)
    {
        $this->_render = false;
        \Apparena\Helper\Cache::init()->clean($i_id);
    }
}
