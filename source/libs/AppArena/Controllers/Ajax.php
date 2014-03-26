<?php
/**
 * handle all requests from "ajax/restfull"
 */
namespace Apparena\Controllers;

define('_VALID_CALL', true);

Class Ajax extends \Apparena\Controller
{
    protected $_header;
    protected $_params;

    public function before($i_id = 0, $lang = APP_DEFAULT_LOCALE)
    {
        if ($this->request()->isAjax() === false)
        {
            // Request has not been made by Ajax.
            $this->redirect('/error/', 403);
        }

        // disable template rendering
        $this->_render = false;

        // define header information
        $this->_header                              = $this->response();
        $this->_header['Content-Description']       = 'File Transfer';
        $this->_header['Content-Type']              = 'application/json';
        $this->_header['Content-Transfer-Encoding'] = 'binary';
        $this->_header['Expires']                   = '0';
        $this->_header['Cache-Control']             = 'must-revalidate';
        $this->_header['Pragma']                    = 'public';

        // in case backbone is sending params in body
        if ($this->request->getMediaType() === 'application/json')
        {
            // get the passed data, which will be a STRING of json
            $bodyStr = $this->request->getBody();

            // convert it to an OBJECT
            $bodyJson = json_decode($bodyStr, true);

            // now make it an ARRAY
            $bodyArr = (array)$bodyJson;

            // merge passed data array with post array
            $this->_params = array_merge($this->request->post(), $bodyArr);
        }

        parent::before($i_id, $lang);
    }

    public function indexAction()
    {
        global $db;

        $post = $this->request()->post();

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
            $_POST        = $this->request->post();
            $i_id         = $this->request->post('i_id');
            $current_date = \Apparena\App::getCurrentDate();
            include_once($path);

            $this->_header ['Content-Disposition'] = 'attachment; filename=' . $action . '.json';
        }
        catch (\Exception $e)
        {
            $this->_header ['Content-Disposition'] = 'attachment; filename=error.json';
            $return['message']                     = $e->getMessage();
            $return['trace']                       = $e->getTrace();
        }

        $this->response->write(json_encode($return));
    }

    public function getAction()
    {
        $args   = func_get_args();
        $params = array_merge($args, $this->request->get());
        $this->startAjaxCallOn($args[2], $args[3], $params);
    }

    public function putAction()
    {
        $args = func_get_args();
        $this->startAjaxCallOn($args[2], $args[3], $this->_params);
    }

    public function postAction()
    {
        $args = func_get_args();
        $this->startAjaxCallOn($args[2], $args[3], $this->_params);
    }

    public function deleteAction()
    {
        $args = func_get_args();
        $this->startAjaxCallOn($args[2], $args[3], $this->_params);
    }

    protected function startAjaxCallOn($class, $method, $params = array())
    {
        // create default return statement
        $return = array(
            'code'    => 0,
            'status'  => 'error',
            'message' => ''
        );

        try
        {
            if (!empty($class) && !empty($method))
            {
                $filename = $class . $method;
                $class    = __NAMESPACE__ . '\\' . ucfirst($class);
                $method   = strtolower($method) . 'Action';
                if (is_callable(array($class, $method)))
                {
                    $class  = new $class();
                    $return = call_user_func_array(array($class, $method), $params);
                    #$return = array_merge($return, $result);
                    $this->_header ['Content-Disposition'] = 'attachment; filename=' . strtolower($filename) . '.json';
                }
                else
                {
                    throw new \Exception('Class not callable: ' . $class);
                }
            }
            else
            {
                throw new \Exception('Ajax call needs 2 parameters.');
            }
        }
        catch (\Exception $e)
        {
            $this->_header ['Content-Disposition'] = 'attachment; filename=error.json';
            $return['message']                     = $e->getMessage();
            $return['trace']                       = $e->getTrace();
        }

        $this->response->write(json_encode($return));
    }
}