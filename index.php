<?php
use Monster\App\Models\Env;
use Monster\App\Route;

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

/*
|--------------------------------------------------------------------------
| Application Bootstrap
|--------------------------------------------------------------------------
|
| This is the main entry point of the application. It handles the bootstrap
| process, including autoloading dependencies, loading routes, and running
| the application.
|
*/

// Autoload dependencies using Composer
require_once 'vendor/autoload.php';

// PHP error handling
$config = new Env('.env');
$debug = $config->get("APP_DEBUG");
if ($debug == "true") {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

// Load application routes
require 'routes/web.php';

// Run the application
Route::run();