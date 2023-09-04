<?php
use Monster\App\Route;

/*
|--------------------------------------------------------------------------
| API-Monster Route
|--------------------------------------------------------------------------
|
| The Route class is responsible for defining routes and their associated
| handlers in the application. It provides methods for specifying various
| types of HTTP routes such as GET, POST, etc. and mapping them to
| corresponding controller actions or closures.
|
*/

Route::get('/', 'HomeController@index');
Route::post('/', 'HomeController@index');
Route::get('/api', 'AppController@index');

/*
Route::group('/admin', function () {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@getALL');
    Route::post('/user/get/{id}', 'UserController@store');
});
*/