<?php
/**
 * Prepares a page with Facebook Meta Data
 * This page receives the instance id as GET-Parameter and creates a page perfectly fitted for a like or send button to like
 * So if a friend of the user which liked this page, clicks the link to that, page he will be redirected.
 */
namespace Apparena\Controllers;

/**
 * Class Share
 * @package Apparena\Controllers
 */
Class Share extends \Apparena\Controller
{
    /**
     * indexAction
     *
     * basic share options
     *
     * @param integer $i_id instance id but not used
     * @param string  $lang language, but not used
     * @param string  $base opengraph $base, like website
     */
    public function indexAction($i_id, $lang, $base = '')
    {
        $this->callApi();
        $instance = \Apparena\Api\Instance::init();

        // basic variables
        $redirect_url = $instance->data->page_tab_url;
        $fb_share_url = $instance->data->share_url;
        $params = $this->_request->params();

        // variable modifications
        // redirect only desktops to facebook
        if (__c('app_using_on') === 'website'
            || ($instance->env->device->type !== 'desktop' && __c('app_using_on') !== 'facebook')
            || (!empty($base) && $base === 'website')
        )
        {
            $redirect_url = $instance->data->fb_canvas_url . \Apparena\App::$i_id . '/' . \Apparena\App::$locale . '/';

            if (is_array($params))
            {
                // add all params for share url as GET aparams
                foreach ($params AS $key => $value)
                {
                    $redirect_url = $this->addToUri($redirect_url, $key . '=' . $value);
                }
            }
        }

        // Check if app_data exists and concatenate it to the sharing url
        if ($base !== 'website')
        {
            if (is_array($params))
            {
                // unset unneeded keys
                unset($params['signed_request']);
                unset($params['app_data']);
                unset($params['locale']);
                // add all params for share url as GET aparams
                foreach ($params AS $key => $value)
                {
                    $fb_share_url = $this->addToUri($fb_share_url, $key . '=' . $value);
                }
            }
            else
            {
                $params = array();
            }
            // add language param to use is into app_data param for facebook
            $params = array_merge($params, array('locale' => \Apparena\App::$locale));
            // add all params as json data to facebook url
            $redirect_url = $this->addToUri($redirect_url, 'app_data=' . urlencode(json_encode($params)));
        }

        $og_object = $this->_request->get('og-object');
        $og_type   = 'website';
        if (!empty($og_object))
        {
            $og_type      = $instance->data->fb_app_namespace . ':' . $og_object;
            $fb_share_url = $this->addToUri($fb_share_url, 'og-object=' . urlencode($og_object));
        }

        $share_image   = __c('share_image', 'src');
        if(!is_null($this->_request->get('share_image')))
        {
            $share_image = $this->_request->get('share_image');
        }

        $general_title = __c('general_title');
        if (!is_null($this->_request->get('general_title')))
        {
            $general_title = $this->_request->get('general_title');
        }

        $general_desc  = __c('general_desc');
        if (!is_null($this->_request->get('general_desc')))
        {
            $general_desc = $this->_request->get('general_desc');
        }

        $this->config('templates.base', 'share');

        $this->_data = array(
            'i_id'              => \Apparena\App::$i_id,
            'fb_app_namespace'  => $instance->data->fb_app_namespace,
            'fb_share_url'      => $fb_share_url,
            'fb_app_id'         => $instance->data->fb_app_id,
            'og_type'           => $og_type,
            'share_image'       => $share_image,
            'share_title'       => $general_title,
            'share_description' => $general_desc,
            'redirect_url'      => $redirect_url,
        );

        $this->response->setStatus(200);
    }

    /**
     * addToUri
     *
     * Add new parameters to url and decide which separator will be used
     *
     * @param string $uri       url
     * @param string $extention new parameter
     *
     * @return string return parameter with right separator
     */
    protected function addToUri($uri, $extention)
    {
        if (!empty($extention))
        {
            if (strpos($uri, '?') === false)
            {
                $uri .= '?';
            }
            else
            {
                $uri .= '&';
            }
        }

        return $uri . $extention;
    }
}