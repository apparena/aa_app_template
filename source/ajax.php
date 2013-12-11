<?php
/**
 * ajax.php
 *
 * handle all requests from "ajax/restfull"
 */

define('DS', DIRECTORY_SEPARATOR);
//define('ROOT', dirname(__FILE__));

// set content type to json file
header('Content-Type: application/json; charset=utf-8');
// disable browser caching
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
try
{
    require_once("init.php");

    if (!defined('ENV_MODE') || ENV_MODE !== 'dev')
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
        {
            // Request has not been made by Ajax.
            die('Direct Access is not allowed.');
        }
    }

    if (defined('ENV_MODE') && ENV_MODE === 'dev' && isset($_GET['debug']))
    {
        echo '<pre>';
        print_r($_GET);
        echo '</pre>';

        echo '<pre>';
        print_r($_POST);
        echo '</pre>';

        echo '<pre>';
        print_r($_SERVER['REQUEST_METHOD']);
        echo '</pre>';

        echo '<pre>';
        print_r($_SERVER['REQUEST_URI']);
        echo '</pre>';
    }

    // create default return statement
    $return = array(
        'code'    => 0,
        'status'  => 'error',
        'message' => ''
    );
    $path   = ROOT_PATH;

    if (defined('ENV_MODE') && ENV_MODE === 'dev' && !empty($_GET['module']))
    {
        $path .= DS . 'modules' . DS . $_GET['module'] . DS . 'libs';
    }
    elseif (!empty($_POST['module']))
    {
        $path .= DS . 'modules' . DS . $_POST['module'] . DS . 'libs';
    }

    if (!defined('ENV_MODE') || ENV_MODE !== 'dev')
    {
        if (empty($_POST['action']))
        {
            throw new Exception('action is not defined in call');
        }
    }
    if (defined('ENV_MODE') && ENV_MODE === 'dev' && !empty($_GET['action']))
    {
        $action = $_GET['action'];
    }
    else
    {
        $action = $_POST['action'];
    }

    $path .= DS . $action . '.php';

    if (!file_exists($path))
    {
        throw new Exception($path . ' not exist');
    }
    include_once($path);

    if (defined('ENV_MODE') && ENV_MODE === 'dev' && !empty($_GET['module']))
    {
        pr($return);
    }
    else
    {
        // attache json file as download
        header("Content-Disposition:attachment;filename='" . $action . ".json'");
        echo json_encode($return);
    }
}
catch (Exception $e)
{

    // attache json file as download
    $return['message'] = $e->getMessage();
    $return['trace']   = $e->getTrace();
    if (defined('ENV_MODE') && ENV_MODE === 'dev' && !empty($_GET['module']))
    {
        pr($return);
    }
    else
    {
        header("Content-Disposition:attachment;filename='error.json'");
        echo json_encode($return);
    }
}