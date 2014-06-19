<?php
namespace Apparena\Controllers;

Class Main extends \Apparena\Controller
{
    public function indexAction()
    {
        $this->callApi();
        $this->_data = array('app_content' => $this->render('pages/index'));

        // ToDo: DEMO PART START. please remove this, before deployment
        if (file_exists(ROOT_PATH . '/includes/demolinks.php'))
        {
            $demo_links = include_once ROOT_PATH . '/includes/demolinks.php';
            $this->_data['app_demo_content'] = $demo_links;
        }
        // ToDo: DEMO PART END.

        $this->addBasicLayoutData();
    }

    /**
     * handle facebook calls with defined instance id in app settings
     * This is a special case to use one instance in more than one facebook pages
     *
     * @param $i_id
     * @param $locale
     */
    public function idbyfbAction($i_id, $locale)
    {
        \Apparena\App::$i_id = $i_id;
        \Apparena\App::setLocale($locale, $this);
        $this->MissingIdAction();
    }

    /**
     * Action when instance ID is missing, for example in facebook tabs and canvas pages.
     * On facebook, we detect the fb id and get the instance id from the appmanager api
     * and redirect the call to the right url. Otherwise we display an error page.
     */
    public function MissingIdAction()
    {
        global $db;

        /*pr('###### PARAMS ######');
        pr($this->request->params());*/

        if ($this->isFacebook())
        {
            /*pr('###### _sign_request ######');
            pr($this->_sign_request);
            pr('###### $_app_data ######');
            pr(\Apparena\App::$_app_data);*/

            $this->defineApi();

            if (isset($this->_sign_request->page))
            {
                \Apparena\App::$api->setFbPageId($this->_sign_request->page->id);

                $instance = (array)\Apparena\App::$api->getInstanceFromFacebook('data');

                if (!empty($instance[0]))
                {
                    $instance = $instance[0];
                }

                // set locale
                if (\Apparena\App::$_app_data !== null && isset(\Apparena\App::$_app_data->locale))
                {
                    \Apparena\App::setLocale(\Apparena\App::$_app_data->locale, $this);
                }
                else
                {
                    \Apparena\App::setLocale($instance->locale, $this);
                }

                // redirect
                $this->redirect('/' . $instance->i_id . '/' . \Apparena\App::$locale . '/?signed_request=' . $this->_sign_request->sign_request, 301);
                /*if ($instance->activate === '1')
                {
                    $this->redirect('/' . $instance->i_id . '/' . \Apparena\App::$locale . '/?signed_request=' . $this->_sign_request->sign_request, 301);
                }
                else
                {
                    $this->redirect('/expired/', 301);
                }*/
            }
            elseif ($this->request->get('request_ids') !== '')
            {
                $fb_request_id = explode(",", $this->request->get('request_ids'));
                // check if more than one ID exists
                if (is_array($fb_request_id) == true)
                {
                    //$fb_request_id = array_pop($fb_request_id); // the most recent one is the last one
                    $fb_request_id = $fb_request_id[0]; // the most recent one is the first one
                }

                $sql = "SELECT
                            *
                        FROM
                            mod_facebook_friends
                        WHERE
                            request_id = :request_id
                        LIMIT 1
                        ";

                $stmt = $db->prepare($sql);
                $stmt->bindParam(':request_id', $fb_request_id, \PDO::PARAM_INT);
                $stmt->execute();

                /*pr('###### $fb_request_id ######');
                pr($fb_request_id);
                pr('###### $stmt->fetchObject() ######');
                pr($stmt->fetchObject());*/

                if ($stmt->rowCount() > 0)
                {
                    $result              = $stmt->fetchObject();
                    \Apparena\App::$i_id = $result->i_id;
                    $this->fb_invited_by = $result->auth_uid;

                    if ($this->request->get('fb_locale') !== '')
                    {
                        \Apparena\App::setLocale($this->request->get('fb_locale'), $this);
                    }

                    $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/?signed_request=' . $this->_sign_request->sign_request, 301);
                }
            }
        }
        elseif (!empty($_SERVER['i_id']))
        {
            $this->redirect('/' . $_SERVER['i_id'] . '/' . \Apparena\App::$locale . '/', 301);
        }

        $this->config('templates.base', 'pages/error');
        $this->_data   = array(
            'title' => 'Ohhh damn!',
            'desc'  => 'Your instance ID was not found in our archive. Sorry for that!'
        );
        $this->_status = 404;
    }

    public function browserAction()
    {
        define('CHECKBROWSER', false);
        $this->callApi();
        $this->config('templates.base', $this->config('templates.small'));
        $this->_data = array(
            'app_content' => $this->render('browser', array(
                    'old_browser_page' => __c('old_browser_page'),
                )),
        );
    }

    public function expiredAction()
    {
        define('CHECKINSTANCE', false);
        $this->callApi();
        $this->config('templates.base', $this->config('templates.small'));
        $this->_data = array(
            'app_content' => $this->render('expired', array(
                    'expired_instance' => __c('expired_instance'),
                )),
        );
    }

    public function notFoundAction()
    {
        if (!is_null(\Apparena\App::$i_id) && \Apparena\App::$i_id > 0)
        {
            $this->callApi();
        }
        $this->_status = 404;
        $this->config('templates.base', $this->config('templates.small'));
        $this->_data = array(
            'app_content' => $this->render($this->_status, array()),
        );
    }

    public function missingLanguageAction()
    {
        $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$locale);
    }
}
