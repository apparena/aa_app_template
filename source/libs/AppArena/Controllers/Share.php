<?php
/**
 * Prepares a page with Facebook Meta Data
 * This page receives the instance id as GET-Parameter and creates a page perfectly fitted for a like or send button to like
 * So if a friend of the user which liked this page, clicks the link to that, page he will be redirected.
 */
namespace Apparena\Controllers;

Class Share extends \Apparena\Controller
{
    public function indexAction()
    {
        $this->callApi();
        $instance = \Apparena\Api\Instance::init();

        // basic variables
        $redirect_url = $instance->data->page_tab_url;
        $og_type      = 'website';
        $fb_share_url = $instance->data->share_url;

        // variable modifications
        // redirect only desktops to facebook
        if (__c('app_using_on') === 'website'
            || ($instance->env->device->type !== 'desktop' && __c('app_using_on') !== 'facebook')
            || (!empty($_GET['page']) && $_GET['page'] === 'website')
        )
        {
            $redirect_url = $instance->data->fb_canvas_url . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/';
        }

        // Check if app_data exists and concatinate it to the sharing url
        if (isset($_GET['app_data']))
        {
            $fb_share_url = $this->addToUri($fb_share_url, 'app_data=' . urlencode($_GET['app_data']));
            $redirect_url = $this->addToUri($redirect_url, 'app_data=' . urlencode($_GET['app_data']));
        }

        if (!empty($_GET['og-object']))
        {
            $og_type      = $instance->data->fb_app_namespace . ':' . $_GET['og-object'];
            $fb_share_url = $this->addToUri($fb_share_url, 'og-object=' . urlencode($_GET['og-object']));
        }

        $this->config('templates.base', 'share');
        $this->_data = array(
            'i_id'              => \Apparena\App::$i_id,
            'fb_app_namespace'  => $instance->data->fb_app_namespace,
            'fb_share_url'      => $fb_share_url,
            'fb_app_id'         => $instance->data->fb_app_id,
            'og_type'           => $og_type,
            'share_image'       => __c('share_image', 'src'),
            'share_title'       => __c('general_title'),
            'share_description' => __c('general_desc'),
            'redirect_url'      => $redirect_url,
        );
    }

    protected function addToUri($uri, $extention)
    {
        if (!empty($extention))
        {
            if (trpos($uri, '?') === false)
            {
                $uri .= '?';
            }
            else
            {
                $uri .= '?';
            }
        }

        return $uri . $extention;
    }
}