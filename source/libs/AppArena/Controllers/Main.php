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

    public function MissingIdAction()
    {
        if ($this->isFacebook())
        {
            $this->defineApi();
            \Apparena\App::$api->setFbPageId($this->_sign_request->page->id);
            $instance = (array)\Apparena\App::$api->getInstanceFromFacebook('data');

            if (!empty($instance[0]))
            {
                $instance = $instance[0];
            }

            \Apparena\App::setLocale($instance->locale, $this);

            // redirect
            if ($instance->activate === '1')
            {
                $this->redirect('/' . $instance->i_id . '/' . \Apparena\App::$locale . '/?signed_request=' . $this->_sign_request->sign_request, 301);
            }
            else
            {
                $this->redirect('/expired/', 301);
            }
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
        $this->config('templates.base', 'browser');
    }

    public function expiredAction()
    {
        $this->config('templates.base', 'expired');
    }

    public function notFoundAction()
    {
        $this->_status = 404;
        $this->config('templates.base', $this->_status);
    }

    public function missingLanguageAction()
    {
        $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$locale);
    }
}
