<?php

namespace Monster\App;

/*
|--------------------------------------------------------------------------
| API-Monster Framework
|--------------------------------------------------------------------------
|
| API-Monster is a fast, safe, and easy-to-use PHP framework designed for
| building API applications. It provides a wide range of features and
| components to streamline the development process and enhance
| productivity.
|
| Features:
| - Fast: API-Monster is optimized for performance, allowing you to build
|   high-performance API applications.
| - Safe: The framework prioritizes security and provides built-in mechanisms
|   for handling common security concerns.
| - Easy: API-Monster follows a user-friendly approach, making it easy for
|   developers to understand and work with the framework.
|
| Key Components:
| - Routing: API-Monster supports routing similar to Laravel, allowing you to
|   define routes and map them to corresponding controller actions.
| - MySQL Class: The framework includes a MySQL class for easy interaction
|   with MySQL databases.
| - HTTP Class: API-Monster provides an HTTP class for handling HTTP requests
|   and responses, simplifying the communication with external APIs.
| - Cipher Class: The Cipher class offers encoding and decoding functionality,
|   allowing you to securely handle sensitive data.
| - Controllers: API-Monster supports controllers, enabling you to organize
|   your application's logic into modular and reusable components.
| - Object-Oriented Syntax: The framework utilizes object-oriented programming
|   (OOP) syntax, promoting clean and maintainable code.
|
| Getting Started:
| To create a new API-Monster project, you can use Composer by running the
| following command:
|   composer create-project darkphp/apimonster myapp
|
| GitHub Repository:
| For more information and to explore the framework's source code, you can
| visit the API-Monster GitHub repository at:
|   https://github.com/ReactMVC/API-Monster
|
| Developer Information:
| API-Monster is developed by Hossein Pira. If you have any questions,
| suggestions, or feedback, you can reach out to Hossein via email at:
|   - h3dev.pira@gmail.com
|   - hosseinpiradev@gmail.com
| Alternatively, you can contact Hossein on Telegram at @h3dev.
|
*/

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
        $request_uri = rtrim($request_uri, '/');
        if ($this->method === $request_method && preg_match($this->pathRegex[$this->path], $request_uri, $matches)) {
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
            $controllerClass = "Monster\\App\\Controllers\\" . $controller;

            // Cache the value of class_exists and call_user_func_array
            static $classExists = [];
            static $callUserFuncArray = [];

            if (!isset($classExists[$controllerClass])) {
                $classExists[$controllerClass] = class_exists($controllerClass);
            }

            if ($classExists[$controllerClass]) {
                if (!isset($callUserFuncArray[$controllerClass])) {
                    $callUserFuncArray[$controllerClass] = function ($controllerInstance, $method, $params) {
                        if ($params) {
                            $controllerInstance->$method(...$params);
                        } else {
                            $controllerInstance->$method();
                        }
                    };
                }
                $controllerInstance = new $controllerClass;
                $callUserFuncArray[$controllerClass]($controllerInstance, $method, $this->params);
            } else {
                http_response_code(500);
                echo "Internal Server Error: Controller class not found";
            }
        }
    }
}