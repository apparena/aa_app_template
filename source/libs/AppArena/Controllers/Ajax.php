<?php
/**
 * handle all requests from "ajax/restfull"
 */
namespace Apparena\Controllers;

Class Ajax extends \Apparena\Controller
{
    public function indexAction()
    {
        if ($this->request()->isAjax() === false)
        {
            // Request has not been made by Ajax.
            $this->redirect('/error/', 403);
        }

        $this->_render = false;
        $post          = $this->request()->post();

        // define header information
        $res                              = $this->response();
        $res['Content-Description']       = 'File Transfer';
        $res['Content-Type']              = 'application/json';
        $res['Content-Transfer-Encoding'] = 'binary';
        $res['Expires']                   = '0';
        $res['Cache-Control']             = 'must-revalidate';
        $res['Pragma']                    = 'public';

        try
        {
            // create default return statement
            $return = array(
                'code'    => 0,
                'status'  => 'error',
                'message' => ''
            );

            $path = ROOT_PATH . DS . 'modules' . DS . $post['module'] . DS . 'libs';

            if (empty($post['action']))
            {
                throw new \Exception('action is not defined in call');
            }

            $action = $post['action'];
            $path .= DS . $action . '.php';

            if (!file_exists($path))
            {
                throw new \Exception($path . ' not exist');
            }
            include_once($path);

            $res['Content-Disposition'] = 'attachment; filename=' . $action . '.json';
        }
        catch (\Exception $e)
        {
            $res['Content-Disposition'] = 'attachment; filename=error.json';
            $return['message']          = $e->getMessage();
            $return['trace']            = $e->getTrace();
        }

        $this->response->write(json_encode($return));
    }
}