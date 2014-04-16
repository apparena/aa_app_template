<?php
/**
 * Cache
 *
 * handle all caches in different types (File, Memcache, APC)
 *
 * @category    AppArena
 * @package     Helper
 * @subpackage  Cache
 *
 * @see         http://www.appalizr.com/
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @version     1.0.0 (28.02.14 - 14:58)
 */
namespace Apparena\Helper;

class Cache
{
    protected $_prefix;
    protected $_type;
    protected $_path;
    protected $_name;
    protected $_value;
    static private $_class_instance = null;

    /**
     * constructor
     */
    private function __construct()
    {
        $this->_path = ROOT_PATH . '/tmp/cache/';
        $this->_type = 'file';
    }

    /**
     * get class instance (singleton instance)
     *
     * @param string $prefix
     *
     * @return Cache
     */
    static public function init($prefix = '')
    {
        if (null === self::$_class_instance)
        {
            self::$_class_instance = new self();
        }

        self::$_class_instance->setPrefix($prefix);

        return self::$_class_instance;
    }

    public function setPrefix($prefix)
    {
        if (!empty($prefix))
        {
            $prefix = '_' . $prefix;
        }
        $this->_prefix = \Apparena\App::$i_id . $prefix;
    }

    public function check($name)
    {
        $this->setName($name);
        if (file_exists($this->_path . $this->_name))
        {
            return true;
        }

        return false;
    }

    protected function setName($name)
    {
        $this->_name = $this->_prefix . '_' . md5($name);

        return $this;
    }

    protected function setValue(array $value)
    {
        $value        = json_encode($value);
        $this->_value = base64_encode($value);

        return $this;
    }

    protected function decodeValue($value)
    {
        $value = base64_decode($value);

        return json_decode($value);
    }

    public function add($name, $value)
    {
        $this->setName($name)->setValue($value);
        switch ($this->_type)
        {
            case 'apc':
                // filecaching
                break;
            case 'memcache':
                // filecaching
                break;
            default:
                $this->fileCache();
                break;
        }
    }

    public function get($name)
    {
        $this->setName($name);
        switch ($this->_type)
        {
            case 'apc':
                // filecaching
                break;
            case 'memcache':
                // filecaching
                break;
            default:
                $value = $this->getFileCache();
                break;
        }

        return $this->decodeValue($value);
    }

    protected function fileCache()
    {
        file_put_contents($this->_path . $this->_name, $this->_value);
    }

    protected function getFileCache()
    {
        return file_get_contents($this->_path . $this->_name);
    }

    protected function memcacheCache()
    {
    }

    protected function apcCache()
    {
    }

    public function clean($id)
    {
        $recursiveIterator = new \RecursiveDirectoryIterator($this->_path);
        foreach ($recursiveIterator as $element)
        {
            if ($element->getFilename() !== '.' && $element->getFilename() !== '..' && $element->getFilename() !== 'void.txt')
            {
                if ($id !== 'all')
                {
                    // clean only the instance cache
                    $id_length = strlen((string)$id);
                    if(substr($element->getFilename(), 0, $id_length) === $id)
                    {
                        unlink($element->getPath() . '/' . $element->getFilename());
                        continue;
                    }
                }
                else
                {
                    // clean the hole cache
                    unlink($element->getPath() . '/' . $element->getFilename());
                }
            }
        }
    }

    /**
     * disable clone function (singleton)
     */
    private function __clone() { }
} 