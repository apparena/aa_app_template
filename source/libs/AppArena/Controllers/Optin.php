<?php
namespace Apparena\Controllers;

/**
 * Class Optin
 * this is a optin controller for the module optivo to handle newsletter and reminder optins
 * @package Apparena\Controllers
 */
Class Optin extends \Apparena\Controller
{
    public function indexAction($i_id, $locale, $key)
    {
        global $db;

        $this->callApi();
        $this->addBasicLayoutData();

        $instance = \Apparena\Api\Instance::init();

        if (strlen($key) === 32)
        {
            $sql = "SELECT
                        auth_uid,
                        type
                    FROM
                        mod_optivo_optin
                    WHERE
                        secret = :secret
                    LIMIT 1
                    ";

            $stmt = $db->prepare($sql);
            
            $stmt->bindParam(':secret', $key, \PDO::PARAM_STR, 32);
            $stmt->execute();

            if ($stmt->rowCount() === 1)
            {
                $return = $stmt->fetchObject();
                unset($stmt);

                // update optin value in user data
                $sql = "UPDATE
                            mod_auth_user_data
                        SET
                            optin_" . $return->type . " = 1
                        WHERE
                            auth_uid = " . $return->auth_uid . "
                        ";
                $db->query($sql);

                // clear table entry
                $sql = "DELETE FROM
                            mod_optivo_optin
                        WHERE
                            secret = " . $db->quote($key) . "
                        ";
                $db->query($sql);

                // write additionally a log entry
                $sql = "INSERT INTO
                            mod_log_user
                        SET
                            auth_uid = :auth_uid,
                            auth_uid_temp = :auth_uid_temp,
                            i_id = :i_id,
                            data = :data,
                            scope = :scope,
                            code = :status_code,
                            ip = INET_ATON(:ip),
                            date_added = FROM_UNIXTIME(:date_added)
                        ";

                // prepare some variables
                $uid           = (int)$return->auth_uid;
                $ip            = $this->request->getIp();
                $date_added    = \Apparena\App::getCurrentDate()->getTimestamp();
                $data          = json_encode(array('optin_code' => $key));
                $scope         = 'user_optin_' . $return->type;
                $status_code   = 7001;
                $auth_uid_temp = md5(\Apparena\App::$i_id . uniqid() . \Apparena\App::getCurrentDate()->getTimestamp());

                $stmt = $db->prepare($sql);
                $stmt->bindParam(':auth_uid', $uid, \PDO::PARAM_INT);
                $stmt->bindParam(':auth_uid_temp', $auth_uid_temp, \PDO::PARAM_STR, 32);
                $stmt->bindParam(':i_id', \Apparena\App::$i_id, \PDO::PARAM_INT);
                $stmt->bindParam(':data', $data, \PDO::PARAM_STR);
                $stmt->bindParam(':scope', $scope, \PDO::PARAM_STR);
                $stmt->bindParam(':status_code', $status_code, \PDO::PARAM_INT);
                $stmt->bindParam(':ip', $ip, \PDO::PARAM_INT);
                $stmt->bindParam(':date_added', $date_added, \PDO::PARAM_INT);
                $stmt->execute();

                $this->_data = array_merge($this->_data, array(
                    'app_content' => $this->render('optin', array(
                            'text'   => __c('optin_' . $return->type),
                            'button' => __t('btn_terminal'),
                            'link'   => $instance->data->share_url,
                        )),
                ));
            }
            else
            {
                $this->redirect($instance->data->share_url);
            }
        }
        else
        {
            $this->redirect($instance->data->share_url);
        }
    }
}
