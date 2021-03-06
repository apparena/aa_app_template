<?php
/**
 * Css
 *
 * handles css sources and compiling
 *
 * @category    AppArena
 * @package     Helper
 * @subpackage  Css
 *
 * @see         http://www.appalizr.com/
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @version     1.0.0 (25.02.14 - 13:42)
 */
namespace Apparena\Helper;

class Css
{
    protected $_data;
    protected $_config;
    protected $_filename;
    protected $_checksum;
    protected $_files = array(
        'main'   => array(),
        'file'   => array(),
        'config' => array(),
    );

    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        $config = $this->getConfigs();
        $this->add($config['import']);
        $this->_config = $config;
    }

    protected function getConfigs()
    {
        $env               = \Slim\Environment::getInstance();
        $request           = new \Slim\Http\Request($env);
        $base_path         = $request->getRootUri();
        $return            = require_once ROOT_PATH . '/configs/css-config.php';
        $path              = ROOT_PATH . '/modules/';
        $recursiveIterator = new \RecursiveDirectoryIterator($path);

        foreach ($recursiveIterator as $element)
        {
            $file = $element->getPathName() . '/configs/css-config.php';
            if ($element->isDir() && file_exists($file) && $element->getFilename() !== '.' && $element->getFilename() !== '..')
            {
                // include file
                $result = require_once $file;

                // merge array key import
                if (isset($result['import']) && is_array($result['import']))
                {
                    $return['import'] = array_merge($return['import'], $result['import']);
                }

                // merge array key replace
                if (isset($result['replace']) && is_array($result['replace']))
                {
                    $return['replace'] = array_merge($return['replace'], $result['replace']);
                }
            }
        }

        return $return;
    }

    public function add($file, $type = null)
    {
        if (is_array($file))
        {
            foreach ($file AS $arr_file => $arr_type)
            {
                $this->add($arr_file, $arr_type);
            }
        }
        else
        {
            array_push($this->_files[$type], $file);
        }

        return $this;
    }

    public function getCompiled()
    {
        $cache     = \Apparena\Helper\Cache::init('css');
        $cachename = md5('style');
        if ($cache->check($cachename))
        {
            // cache exist, get data from them
            $cachedata = $cache->get($cachename);

            return $cachedata[0];
        }
        else
        {
            #set_time_limit(0);
            ini_set('max_execution_time', 5200);
            // cache not exist, create data
            $this->_data = '';
            $this->addGroupData('main')->addGroupData('file')->addGroupData('config')->replacePaths();

            // init lessphp compiler
            require_once ROOT_PATH . '/libs/lessc.inc.php';
            $less = new \lessc;
            // compress type
            $less->setFormatter("compressed");
            /**
             * remove block comments with false,
             * otherwise true to let comment blocks stay and put them to the top
             * (for licence informations and so on)
             * Block comments are / !* ... * / (without spaces)
             */
            $less->setPreserveComments(false);

            // compile $_data
            $return = $less->compile($this->_data);
            $cache->add($cachename, array($return));

            return $return;
        }
    }

    protected function addGroupData($group)
    {
        foreach ($this->_files[$group] AS $file)
        {
            if ($group === 'config')
            {
                if(__c($file) === false && __c($file, 'src') !== false)
                {
                    $this->_data .= file_get_contents(__c($file, 'src'));
                }
                else
                {
                    $this->_data .= __c($file);
                }
            }
            elseif (file_exists(ROOT_PATH . $file))
            {
                $this->_data .= file_get_contents(ROOT_PATH . $file);
            }
        }

        return $this;
    }

    protected function replacePaths()
    {
        if (!empty($this->_config['replace']) && is_array($this->_config['replace']))
        {
            foreach ($this->_config['replace'] AS $search => $replace)
            {
                $this->_data = str_replace($search, $replace, $this->_data);
            }
        }
    }
} 