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

/*
  Protected Routes
*/
$router->group(['middleware' => 'auth'], function () use ($router) {

  // BOARDS
  $router->get('/boards', 'BoardController@index');   // all boards
  $router->post('/boards', 'BoardController@store');      // create new
  $router->get('/boards/{board}', 'BoardController@show');   // get single
  $router->put('/boards/{board}', 'BoardController@update');  // update
  $router->patch('/boards/{board}', 'BoardController@update'); // update
  $router->delete('/boards/{board}', 'BoardController@destroy'); // delete

  // LISTS
  $router->get(   '/boards/{board}/list',        'ListController@index');     // get all
  $router->post(  '/boards/{board}/list',        'ListController@store');// create new
  $router->get(   '/boards/{board}/list/{list}', 'ListController@show');
  $router->put(   '/boards/{board}/list/{list}', 'ListController@update');
  $router->patch( '/boards/{board}/list/{list}', 'ListController@update');
  $router->delete('/boards/{board}/list/{list}', 'ListController@destroy');

  // CARDS
  $router->get(   '/boards/{board}/list/{list}/card',        'CardController@index');     // get all
  $router->post(  '/boards/{board}/list/{list}/card',        'CardController@store');// create new
  $router->get(   '/boards/{board}/list/{list}/card/{card}', 'CardController@show');
  $router->put(   '/boards/{board}/list/{list}/card/{card}', 'CardController@update');
  $router->patch( '/boards/{board}/list/{list}/card/{card}', 'CardController@update');
  $router->delete('/boards/{board}/list/{list}/card/{card}', 'CardController@destroy');

});
