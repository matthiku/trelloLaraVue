<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


// AUTHENTICATION
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');
$router->get('/logout', 'AuthController@logout');


// BOARDS 

$router->group(['middleware' => 'auth'], function () use ($router) {
	// get one item
	$router->get('/boards/{id}', 'BoardController@show');
	// get all items
	$router->get('/boards', 'BoardController@index');
	// create new item
	$router->post('/boards', 'BoardController@store');
	// create new item
	$router->patch('/boards/{id}', 'BoardController@update');
	// create new item
	$router->delete('/boards/{id}', 'BoardController@destroy');
});