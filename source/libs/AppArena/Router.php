<?php
namespace Apparena;

Class Router
{
    const ACTION_SUFFIX = 'Action';
    protected $routes;
    protected $request;
    protected $errorHandler;

    public function __construct()
    {
        $env           = \Slim\Environment::getInstance();
        $this->request = new \Slim\Http\Request($env);
        $this->routes  = array();
    }

    public function addRoutes($routes)
    {
        foreach ($routes as $route => $path)
        {

            if (is_string($route))
            {
                if (is_array($path))
                {
                    foreach ($path as $method => $action)
                    {
                        $this->addRoute($route, $action . '@' . $method);
                    }
                }
                else
                {
                    $this->addRoute($route, $path);
                }
            }
            else
            {
                $this->addRoute($path[0], $path[1]);
            }
        }
    }

    protected function addRoute($route, $pathStr)
    {
        $method = 'any';

        if (strpos($pathStr, '@') !== false)
        {
            list($pathStr, $method) = explode('@', $pathStr);
        }

        $func = $this->processCallback($pathStr);

        $r = new \Slim\Route($route, $func);
        $r->setHttpMethods(strtoupper($method));

        array_push($this->routes, $r);
    }

    protected function processCallback($path)
    {
        $class = 'Main';

        if (strpos($path, ':') !== false)
        {
            list($class, $path) = explode(':', $path);
        }
        else
        {
            $class = $path;
            $path  = 'index';
        }

        $function = ($path !== '') ? $path : 'index';
        $function .= self::ACTION_SUFFIX;
        $that = $this;

        $func = function () use ($class, $function, $that)
        {
            $class = __NAMESPACE__ . '\Controllers\\' . $class;
            if (!is_callable(array($class, $function)))
            {
                $that->call404Page();
            }

            $class = new $class();
            $args  = func_get_args();

            call_user_func_array(array($class, 'before'), $args);
            $return = call_user_func_array(array($class, $function), $args);
            call_user_func_array(array($class, 'after'), $args);

            return $return;
        };

        return $func;
    }

    public function run()
    {
        $display404 = true;
        $uri        = $this->request->getResourceUri();
        $method     = $this->request->getMethod();

        foreach ($this->routes as $route)
        {
            if ($route->matches($uri))
            {
                if ($route->supportsHttpMethod($method) || $route->supportsHttpMethod('ANY'))
                {
                    call_user_func_array($route->getCallable(), array_values($route->getParams()));
                    $display404 = false;
                    break;
                }
            }
        }

        if ($display404)
        {
            if (is_callable($this->errorHandler))
            {
                call_user_func($this->errorHandler);
            }
            else
            {
                $this->call404Page();
            }
        }
    }

    public function set404Handler($path)
    {
        $this->errorHandler = $this->processCallback($path);
    }

    public function call404Page()
    {
        $class = new Controllers\Main();
        call_user_func_array(array($class, 'notFoundAction'), array());
        exit();
    }
}