<?php
/**
 * User
 *
 * get or set user information
 *
 * @category    utils
 * @package     users
 * @subpackage  user
 *
 * @author      "Marcus Merchel" <kontakt@marcusmerchel.de>
 * @version     1.0.0 (15.08.13 - 15:18)
 */
namespace Apparena\Users;

class User
{
    const TBL_USER          = 'mod_auth_user';
    const TBL_USER_DATA     = 'mod_auth_user_data';
    const ROW_USER_UID      = 'uid';
    const ROW_USER_INSTANCE = 'i_id';
    const ROW_USER_DATA_UID = 'auth_uid';

    /**
     * constructor
     */
    public function __construct()
    {
    }

    /**
     * get back all userinformation by user id
     * if user not exist, return false
     *
     * @param int $uid user id
     * @access static public
     *
     * @return bool|object
     */
    static public function getById($uid)
    {
        global $db;

        // prepare statement
        $sql = "SELECT
                    *
                FROM
                    " . self::TBL_USER . " AS user
                LEFT JOIN
                    " . self::TBL_USER_DATA . " AS data
                    ON data." . self::ROW_USER_DATA_UID . " = user." . self::ROW_USER_UID . "
                WHERE
                    i_id = :i_id
                AND user." . self::ROW_USER_UID . " = :" . self::ROW_USER_UID . "
                LIMIT 1
               ";
        $stmt = $db->prepare($sql);

        // bind data to prepared statement
        $i_id = \Apparena\App::$i_id;
        $stmt->bindParam(':i_id', $i_id, \PDO::PARAM_INT);
        $stmt->bindParam(':' . self::ROW_USER_UID, $uid, \PDO::PARAM_INT);
        if($stmt->execute())
        {
            return $stmt->fetchObject();
        }
        else
        {
            return false;
        }
    }
}