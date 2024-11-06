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

$router->group(['prefix' => 'admin/menu/category','middleware'=>'admin'], function () use ($router) {
    $router->get('/',['uses'=>'MenuCategory\CategoryController@index']);
    $router->post('/store',['uses'=>'MenuCategory\CategoryController@store']);
    $router->post('/search',['uses'=>'MenuCategory\CategoryController@Search']);
    $router->post('/update',['uses'=>'MenuCategory\CategoryController@update']);
    $router->delete('/delete/{id}',['uses'=>'MenuCategory\CategoryController@destroy']);
});
$router->group(['prefix' => 'casher/menu/category','middleware'=>['admin','casher']], function () use ($router) {
    $router->get('/',['uses'=>'MenuCategory\CategoryController@index']);
});