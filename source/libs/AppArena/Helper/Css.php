<?php
/**
 * Css
 *
 * CLASSDESCRIPTION
 *
 * @category    CATEGORY NAME
 * @package     PACKAGE NAME
 * @subpackage  PACKAGE NAME
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @copyright   Copyright (c) 2009-2014 Marcus Merchel (http://www.marcusmerchel.de/)
 * @link        http://www.marcusmerchel.de/
 * @license     http://www.marcusmerchel.de/licence/
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
        $base_path = \Slim\Slim::getInstance()->environment()->offsetGet('SCRIPT_NAME');
        $config    = require_once ROOT_PATH . '/configs/css-config.php';

        $this->add($config['import']);
        $this->_config = $config;
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
        return $less->compile($this->_data);
    }

    protected function addGroupData($group)
    {
        foreach ($this->_files[$group] AS $file)
        {
            if ($group === 'config')
            {
                $this->_data .= __c($file);
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