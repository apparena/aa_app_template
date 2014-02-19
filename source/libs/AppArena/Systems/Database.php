<?php
/**
 * Database
 *
 * database connecton wrapper for php pdo class
 *
 * @category    utils
 * @package     database
 * @subpackage  wrapper
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @version     1.0.0 (19.08.13 - 10:25)
 */
namespace Apparena\Systems;

use \PDO AS PDO;

class Database extends PDO
{
    const DEFAULT_PORT = '3306';
    const DEFAULT_TYPE = 'mysql';
    const DSN_MYSQL = '';
    protected $_db_conf = array();
    protected $_connection = null;
    protected $_db_handle = null;

    public function __construct($user, $host, $database, $password = '', $option = array())
    {
        // ToDo[maXus]: interface erzeugen und dann einzelne dsn klassen davon ableiten - 19.08.13
        $db = parent::__construct("mysql:dbname=" . $database . ";host=" . $host . "; port=" . $option['port'], $user, $password, $option['pdo']);

        // set default error mode
        $this->setErrorMode();

        return $db;
    }

    public function setErrorMode($mode = 'default')
    {
        switch ($mode)
        {
            case 'silent':
                $mode = PDO::ERRMODE_SILENT;
                break;
            case 'warn':
            case 'warning':
                $mode = PDO::ERRMODE_WARNING;
                break;
            default:
                $mode = PDO::ERRMODE_EXCEPTION;
                break;
        }
        $this->setAttribute(PDO::ATTR_ERRMODE, $mode);
    }

    public function init($handler)
    {
        // ToDo[maXus]: prüfen, ob treiber im system vorhanden mit getAvailableDrivers() - 19.08.13
        /*if (!isset(self::$dbConf[$active])) throw new PDOException("No supported connection scheme");

        $dbConf = self::$dbConf[$active];

        $this->connect($dbConf);*/
    }

    public function connect($handler)
    {
        /*try
        {
            if(is_null($this->_connection
            {
                //$db = new PDO($db_server, $db_user, $db_pass, $db_option);
                $db = parent::__construct();

                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $this->_connection = $db;
            }
        }
        catch (\PDOException $e)
        {
            throw new \PDOException("Connection Exception: " . $e->getMessage());
        }*/
    }

    public function disconnect($connection)
    {
        //ToDo[maXus]: verbindungen prüfen, sollte kein parameter gesetzt sein, werden ALLE Verbindungen geschlossen - 19.08.13
        /*$this->_connection = null;
        unset(self::$instances[$this->active]);*/
    }
}