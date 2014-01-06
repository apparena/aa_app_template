<?php
/** translate functions **/
function __t()
{
    global $aa;

    // START version for old API version
    $translate = json_decode(json_encode($aa->locale), true);
    $index     = $aa->locale->index;

    $args = func_get_args();
    $num  = func_num_args();

    if ($num === 0)
    {
        return '';
    }

    $hash = md5($args[0]);

    // if translation not exist, return key
    if(empty($index->$hash))
    {
        return $args[0];
    }
    $pos  = $index->$hash;
    $text = $translate[$pos]['value'];
    // END version for old API version

    // ToDo - version for new API version, remove all above if you activate this one! REMOVE createTranslationIndex() from AppManager class too!
    /*$translate = $aa->locale;
    $key = $args[0];
    $text = $translate->$key->value;*/

    if ($num > 1)
    {
        unset($args[0]);
        $param = '"' . implode('","', $args) . '"';
        $text  = sprintf($text, $param);
    }

    return $text;
}

/*
 *translate, but print directly
*/
function __pt()
{
    if (func_num_args() == 0)
    {
        echo '';

        return false;
    }
    echo call_user_func_array("__t", func_get_args());

    return true;
}

/*
 * returned given config value, or given key
*/
function __c($config, $key = 'value')
{
    global $aa;

    if (empty($config))
    {
        throw new Exception('$config is empty in config helper');
    }

    if (!empty($aa->config->$config->$key))
    {
        return $aa->config->$config->$key;
    }

    return false;
}

/*
 * print given config value, or given key
*/
function __pc($config, $key = 'value')
{
    $output = __c($config, $key);

    if ($output !== false)
    {
        echo $output;
    }
}

function getBrowser()
{
    $ub       = "Other";
    $u_agent  = $_SERVER['HTTP_USER_AGENT'];
    $bname    = 'Unknown';
    $platform = 'Unknown';
    $version  = "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent))
    {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent))
    {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent))
    {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent))
    {
        $bname = 'Internet Explorer';
        $ub    = "MSIE";
    }
    elseif (preg_match('/Firefox/i', $u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub    = "Firefox";
    }
    elseif (preg_match('/Chrome/i', $u_agent))
    {
        $bname = 'Google Chrome';
        $ub    = "Chrome";
    }
    elseif (preg_match('/Safari/i', $u_agent))
    {
        $bname = 'Apple Safari';
        $ub    = "Safari";
    }
    elseif (preg_match('/Opera/i', $u_agent))
    {
        $bname = 'Opera';
        $ub    = "Opera";
    }
    elseif (preg_match('/Netscape/i', $u_agent))
    {
        $bname = 'Netscape';
        $ub    = "Netscape";
    }

    // finally get the correct version number
    $known   = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches))
    {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1)
    {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub))
        {
            $version = $matches['version'][0];
        }
        elseif (isset($matches['version'][1]))
        {
            $version = $matches['version'][1];
        }
    }
    else
    {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "")
    {
        $version = "?";
    }

    if ($version)
    {
        $tmpVersion = explode(".", $version);
    }
    if (is_array($tmpVersion))
    {
        $majVersion = $tmpVersion[0];
    }
    else
    {
        $majVersion = $version;
    }

    return (object)array(
        'userAgent' => $u_agent,
        'name'      => $ub,
        'version'   => intval($majVersion),
        'platform'  => $platform,
        'pattern'   => $pattern
    );
}

/**
 * get client's ip
 */
function get_client_ip()
{
    // Get client ip address
    $client_ip = '';
    if (isset($_SERVER["REMOTE_ADDR"]))
    {
        $client_ip = $_SERVER["REMOTE_ADDR"];
    }
    else
    {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else
        {
            if (isset($_SERVER["HTTP_CLIENT_IP"]))
            {
                $client_ip = $_SERVER["HTTP_CLIENT_IP"];
            }
        }
    }

    return $client_ip;
}

//escape $_GET, $_POST, $_REQUIRE $_COOKIE ()
if (!function_exists('global_escape'))
{
    function global_escape()
    {
        if (get_magic_quotes_gpc())
        {
            $in = array(&$_GET, &$_POST, &$_REQUEST, &$_COOKIE);
            while (list($k, $v) = each($in))
            {
                foreach ($v as $key => $val)
                {
                    if (!is_array($val))
                    {
                        $in[$k][$key] = escape($val, false);
                        continue;
                    }
                    $in[] =& $in[$k][$key];
                }
            }
            unset($in);
        }
        unregister_globals();
    }
}

function unregister_globals()
{
    // Überprüfung, ob Register Globals läuft
    if (@ini_get("register_globals") == "1" || @ini_get("register_globals") == "on")
    {
        // Erstellen einer Liste der Superglobals
        $superglobals = array("_GET", "_POST", "_REQUEST", "_ENV", "_FILES", "_SESSION", "_COOKIE", "_SERVER");
        foreach ($GLOBALS as $key => $value)
        {
            // Überprüfung, ob die Variablen/Arrays zu den Superglobals gehören, andernfalls löschen
            if (!in_array($key, $superglobals) && $key != "GLOBALS")
            {
                unset($GLOBALS[$key]);
            }
        }

        return true;
    }
    else
    {
        // Läuft Register Globals nicht, gibt es nichts zu tun.
        return true;
    }
}

if (!function_exists('escape'))
{
    function escape($value, $specialchars = true)
    {
        if (get_magic_quotes_gpc())
        {
            $value = stripslashes($value);
        }

        if ($specialchars === true)
        {
            $value = htmlspecialchars($value);
        }
        $value = trim($value);

        return $value;
    }
}

if (!function_exists('sql_escape'))
{
    function sql_escape($value, $specialchars = true)
    {
        $value = escape($value, $specialchars);

        if (function_exists('mysql_real_escape_string'))
        {
            $value = mysql_real_escape_string($value);
        }
        else
        {
            //use old addslashes
            $value = addslashes($value);
        }

        return $value;
    }
}

if (!function_exists('pr'))
{
    function pr()
    {
        $args = func_get_args();
        //$num  = func_num_args();

        if (is_array($args[0]))
        {

            foreach ($args[0] AS $var)
            {
                echo '<pre>';
                print_r($var);
                echo '</pre>';
            }
        }
        else
        {
            echo '<pre>';
            print_r($args[0]);
            echo '</pre>';
        }
        if (!empty($args[1]))
        {
            exit($args[1]);
        }

        return true;
    }
}

if (!function_exists('vd'))
{
    function vd()
    {
        $args = func_get_args();
        //$num  = func_num_args();

        if (is_array($args[0]))
        {

            foreach ($args[0] AS $var)
            {
                echo '<pre>';
                var_dump($var);
                echo '</pre>';
            }
        }
        else
        {
            echo '<pre>';
            var_dump($args[0]);
            echo '</pre>';
        }
        if (!empty($args[1]))
        {
            exit($args[1]);
        }

        return true;
    }
}

if (!function_exists('ifempty'))
{
    //sets $var with $value if empty
    function ifempty(&$var, $value = '')
    {
        if ((empty($var) && $var != 0) || $var == null)
        {
            $var = $value;
        }

        return $var;
    }
}

if (!function_exists('iif'))
{
    //returns $true if $exp = TRUE, else $false
    function iif($exp, $true, $false = '')
    {
        return ($exp) ? $true : $false;
    }
}

if (!function_exists('is_serialized'))
{
    function is_serialized($str)
    {
        return ($str == serialize(false) || @unserialize($str) !== false);
    }
}

if (!function_exists('hrd'))
{
    function hrd($target = '/', $code = 301, $msg = '')
    {
        header("HTTP/1.1 " . $code . " Moved Permanently");
        header("Location: " . $target);
        header("Connection: close");
        exit($msg);
    }
}

if (!function_exists('isSSL'))
{
    function isSSL()
    {
        if (!empty($_SERVER['HTTPS']))
        {
            return true;
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        {
            return true;
        }

        return false;
    }
}

if (!function_exists('_clearvar'))
{
    //clears an array or string
    function _clearvar($var)
    {
        $result = array();
        if (is_array($var))
        {
            foreach ($var AS $k => $v)
            {
                if (is_string($v))
                {
                    $result[$k] = htmlentities(strip_tags($v));
                }
                elseif (is_array($v))
                {
                    $result[$k] = _clearvar($v);
                }
                else
                {
                    $result[$k] = htmlentities(strip_tags($v));
                }
            }
        }

        return $result;
    }
}