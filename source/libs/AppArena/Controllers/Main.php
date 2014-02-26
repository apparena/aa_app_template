<?php
namespace Apparena\Controllers;

Class Main extends \Apparena\Controller
{
    public function indexAction()
    {
        $this->callApi();
        $this->_data = array(
            'app_navigation' => $this->render('sections/navigation'),
            'app_header'     => __c('header_custom'),
            'app_footer'     => __c('footer_custom'),
            'app_terms_box'  => $this->render('sections/terms_box', array('link' => __t('footer_terms', '<a href="#/page/app/terms">' . __t('terms') . '</a>'))),
            'app_content'    => $this->render('pages/index'),
        );

        if (__c('show_comments') === '1')
        {
            $this->_data['app_comments_box'] = $this->render('sections/comments_box', array(
                'title'           => __c('fb_comments_title'),
                'href'            => \Apparena\Api\Instance::init()->getData()->share_url,
                'comments_amount' => __c('fb_comments_amount'),
            ));
        }

        if (__c('branding_activated') === '1')
        {
            $this->_data['app_branding_footer'] = $this->render('sections/branding_box', array(
                'content' => __c('branding_footer'),
            ));
        }
    }

    public function missingIdAction()
    {
        $this->config('templates.base', 'error');
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
        $this->redirect('/' . \Apparena\App::$i_id . '/' . \Apparena\App::$_locale);
    }
}
