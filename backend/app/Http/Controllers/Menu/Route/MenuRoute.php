<?php
/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'admin/menu','middleware'=>'admin'], function () use ($router) {
    $router->get('/{id}',['uses'=>'Menu\MenuController@index']);
    $router->get('/from/store',['uses'=>'Menu\MenuController@getForLinking']);
    $router->post('/store',['uses'=>'Menu\MenuController@store']);
    $router->post('/search/{id}',['uses'=>'Menu\MenuController@Search']);
    $router->post('/update',['uses'=>'Menu\MenuController@update']);
    $router->delete('/delete/{id}',['uses'=>'Menu\MenuController@destroy']);
});
$router->group(['prefix' => 'casher/menu/category','middleware'=>['admin','casher']], function () use ($router) {
    $router->get('/{id}',['uses'=>'Menu\MenuController@index']);
});