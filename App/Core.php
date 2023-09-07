<?php

namespace Monster\App;

class Core
{
    // The HTTP method associated with this route item
    private $method;

    // The path associated with this route item
    private $path;

    // The controller method to execute when this route item is matched
    private $controller;

    // The parameters extracted from the request URI when this route item is matched
    private $params;

    // Theregular expression used to match the request URI against the path of this route item
    private $pathRegex;

    public function __construct($method, $path, $controller)
    {
        $this->method = $method;
        $this->path = $path;
        $this->controller = $controller;

        // Cache the regular expression created by preg_replace
        if (!isset($this->pathRegex[$path])) {
            $path_regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $path);
            $path_regex = str_replace('/', '\/', $path_regex);
            $path_regex .= '(\\?.*)?$'; // Include an optional query parameter at the end of the path
            $this->pathRegex[$path] = '/^' . $path_regex . '$/';
        }
    }

    // Getters and setters for the private properties
    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    // Match the route item against the request method and URI
    public function match($request_method, $request_uri)
    {
        $parsed_url = parse_url($request_uri);
        $request_path = $parsed_url['path'];

        $request_path = rtrim($request_path, '/');
        if ($this->method === $request_method && preg_match($this->pathRegex[$this->path], $request_path, $matches)) {
            $this->params = array_slice($matches, 1);
            return true;
        }

        return false;
    }

    // Execute the controller method associated with this route item
    public function execute()
    {
        if (is_callable($this->controller)) {
            // If the controller is a closure, execute it directly
            call_user_func_array($this->controller, $this->params);
        } else {
            // If the controller is a string, parse it into a controller and method to execute
            list($controller, $method) = explode('@', $this->controller);
            $controllerClass = "Monster\\App\\Controllers\\" . $controller; // Make sure to escape the backslash

            // Cache the value of class_exists and call_user_func_array
            static $classExists = [];
            static $callUserFuncArray = [];

            if (!isset($classExists[$controllerClass])) {
                $classExists[$controllerClass] = class_exists($controllerClass);
            }

            if ($classExists[$controllerClass]) {
                if (!isset($callUserFuncArray[$controllerClass])) {
                    $callUserFuncArray[$controllerClass] = function ($controllerInstance, $method, $params) {
                        if (method_exists($controllerInstance, $method)) {  // Check if the method exists in the controller class
                            if ($params) {
                                $controllerInstance->$method(...$params);
                            } else {
                                $controllerInstance->$method();
                            }
                        } else {
                            http_response_code(500);
                            echo "Function not found in controller";
                        }
                    };
                }
                $controllerInstance = new $controllerClass;
                $callUserFuncArray[$controllerClass]($controllerInstance, $method, $this->params);
            } else {
                http_response_code(500);
                echo "Controller class not found";
            }
        }
    }
}
