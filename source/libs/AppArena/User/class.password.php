<?php
//ToDo Wir brauchen eine Validierungsfunction für passwörter . Vor und Nachnamen, Geburtsdatum dynamisch als Inhalt verbieten, mindestlänge, sicherheitsstufe mit Sonderzeichen, Groß -/Kleinbuchstaben etc .
/**
 * password
 *
 * password handler with bcrypt and salt
 *
 * @category    utils
 * @package     user
 * @subpackage  password
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @version     1.0.0 (15.08.13 - 15:18)
 */
namespace com\apparena\utils\user\password;

class Password
{
    /**
     * default encrypt rounds
     * must be between 04 and 31
     */
    const ENCRYPT_ROUNDS = 10;
    /**
     * default blowfish length
     */
    const BLOWFISH_LENGTH = 22;
    /**
     * default crypt algo
     */
    const BLOWFISH_MODE = '$2y';
    /**
     * fallback crypt algo for php version lower 5.3.7
     */
    const BLOWFISH_MODE_FALLBACK = '$2a';
    /**
     * set used salt characters
     */
    const SALT_CHARS = './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    /**
     * set default Blowfish-Modi for bcrypt
     * use $2y instent of $2a if you have PHP>5.3.6
     *
     * @var string
     * @see http://de.php.net/manual/de/function.crypt.php
     */
    private $_blowfish_mode;
    /**
     * set the encrypt rounds
     *
     * @var string
     */
    private $_work_factor;
    /**
     * save internal generated salt
     *
     * @var null || string
     */
    private $_dynamic_salt = null;
    /**
     * save external script salt
     *
     * @var null || string
     */
    private $_static_salt = '';
    /**
     * store generatet hashstring
     *
     * @var string
     */
    private $_hash = null;

    /**
     * constructor
     *
     * @param string $salt
     * @param int    $rounds
     *
     * @throws \Exception
     */
    public function __construct($salt = null, $rounds = self::ENCRYPT_ROUNDS)
    {
        if (empty($salt) || is_null($salt))
        {
            throw new \Exception('Static scriptsalt is not given in ' . __METHOD__);
        }

        // start properties
        $this->setCryptMode()->setWorkFactor($rounds)->setDynamicSalt()->setStaticSalt($salt);
    }

    /**
     * @return $this
     */
    public function setCryptMode()
    {
        $this->_blowfish_mode = self::BLOWFISH_MODE;

        if (version_compare(phpversion(), '5.3.7', '<'))
        {
            $this->_blowfish_mode = self::BLOWFISH_MODE_FALLBACK;
        }

        return $this;
    }

    /**
     * @param string $password user password
     * @param string $user     user information like a username or the email addy
     *
     * @return string
     */
    public function encode($password, $user)
    {
        // some predefinings
        $salt_dynamic = $this->getDynamicSalt();
        $rounds       = $this->getWorkFactor();
        $hashmode     = $this->getCryptMode();

        // get hashed password string
        $password = $this->getHashedPassword($password, $user);

        // at the end, return a encrypted token with a individual internal salt
        $crypted_hash = crypt($password, $hashmode . $rounds . $salt_dynamic);

        $this->setHash($crypted_hash);

        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getWorkFactor()
    {
        $rounds = $this->_work_factor;
        if (empty($rounds))
        {
            throw new \Exception('Blowfish work factor "rounds" is not set in ' . __METHOD__);
        }
        return $rounds;
    }

    /**
     * @param $work_factor
     *
     * @return $this
     * @throws \Exception
     */
    public function setWorkFactor($work_factor)
    {
        if (!is_numeric($work_factor))
        {
            throw new \Exception('Blowfish work factor "rounds" must be numeric in ' . __METHOD__);
        }
        if ($work_factor < 4 || $work_factor > 31)
        {
            throw new \Exception('Blowfish work factor "rounds" must be between 4 and 31, best case is between 8 and 12 in ' . __METHOD__);
        }

        // if value is lower than 10, set 0 as prefix
        if ($work_factor < 10)
        {
            $work_factor = '0' . $work_factor;
        }

        $this->_work_factor = '$' . $work_factor;

        return $this;
    }

    /**
     * returnes $this->_blowfish_mode, if empty, than returns default value
     *
     * @return string $this->_blowfish_mode
     * @throws \Exception
     */
    public function getCryptMode()
    {
        $mode = $this->_blowfish_mode;
        if (empty($mode))
        {
            throw new \Exception('Blowfish mode is not set in ' . __METHOD__);
        }
        return $mode;
    }

    /**
     * set static salt from application
     *
     * @param string $salt given from outsite as static salt
     *
     * @return $this
     */
    public function setStaticSalt($salt)
    {
        $this->_static_salt = $salt;
        return $this;
    }

    /**
     * @param string $password user password
     * @param string $user     user information lik username or email addy
     * @param string $token    password token
     *
     * @return bool
     * @throws \Exception
     */
    public function check($password, $user, $token)
    {
        if (empty($token))
        {
            throw new \Exception('Token parameters is missing in ' . __METHOD__);
        }

        // get hashed password string
        $string = $this->getHashedPassword($password, $user);

        // return true or false after hash equal check
        return crypt($string, substr($token, 0, 30)) === $token;
    }

    /**
     * return stored password hash
     *
     * @return null|string
     * @throws \Exception
     */
    public function getHash()
    {
        if (empty($this->_hash) || $this->_hash === null)
        {
            throw new \Exception('hash was currently not generated');
        }
        return $this->_hash;
    }

    /**
     * getDynamicSalt
     *
     * @return string
     */
    private function getDynamicSalt()
    {
        return '$' . $this->_dynamic_salt;
    }

    /**
     * getHashedPassword
     *
     * @param $password
     * @param $email
     *
     * @return string
     * @throws \Exception
     */
    private function getHashedPassword($password, $email)
    {
        if (empty($password) || empty($email))
        {
            throw new \Exception('Parameters are not set correctly. Password or email is missing in ' . __METHOD__);
        }

        $salt_static = $this->getStaticSalt();

        // first modify the password and make it stronger. include the email into the password. with each email changing, password must be changed too
        $stronger_password = str_pad($password, strlen($password) * 4, sha1($email), STR_PAD_BOTH);

        // now create a hash with the stronger password and the script salt
        return hash_hmac("whirlpool", $stronger_password, $salt_static, true);
    }

    /**
     * getStaticSalt
     *
     * @return null
     */
    private function getStaticSalt()
    {
        return $this->_static_salt;
    }

    /**
     * setDynamicSalt
     *
     * @return $this
     */
    private function setDynamicSalt()
    {
        /*$length = strlen(self::SALT_CHARS);
        $characters = self::SALT_CHARS;
        $salt = '';
        for ($count = 0; $count < self::BLOWFISH_LENGTH; $count++)
        {
            #$salt .= $characters[mt_rand(0, $length)];                                              // 1.05 - 1.1s
            $salt .= substr(str_shuffle(self::SALT_CHARS), 0, 1);                                    // 0.9s
        }*/

        /*$count = 0;
        $salt = '';
        $characters = self::SALT_CHARS;
        $length = strlen(self::SALT_CHARS);
        while ($count < self::BLOWFISH_LENGTH)
        {
            // pick one character
            #$salt .= $characters[mt_rand(0, $length)];                                             // 1.05 - 1.1s
            $salt .= substr(str_shuffle(self::SALT_CHARS), 0, 1);                                   // 0.9s

            $count++;
        }*/

        #$salt_characters = str_repeat(self::SALT_CHARS, strlen(self::SALT_CHARS));
        $salt_characters = str_repeat(self::SALT_CHARS, 5);
        $salt            = substr(str_shuffle($salt_characters), 0, self::BLOWFISH_LENGTH + 1); // 1.15s

        #$salt            = substr(str_shuffle(self::SALT_CHARS), 0, self::BLOWFISH_LENGTH + 1);      // 0.04s

        $this->_dynamic_salt = $salt;

        return $this;
    }

    /**
     * store encoded password into $_hash
     *
     * @param $hash
     *
     * @return $this
     * @throws \Exception
     */
    private function setHash($hash)
    {
        if (empty($hash))
        {
            throw new \Exception('Something went wrong with the encoding. Hash is empty.');
        }
        $this->_hash = $hash;

        return $this;
    }
}