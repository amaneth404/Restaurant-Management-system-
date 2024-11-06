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

$router->group(['prefix' => 'users/request'], function () use ($router) {
    $router->get('/admin',['uses'=>'RequestedItme\RequestedItmeController@index']);
    $router->get('/{id}',['uses'=>'RequestedItme\RequestedItmeController@RequestItembyFolder']);
    $router->get('/approve/{id}',['uses'=>'RequestedItme\RequestedItmeController@ApproveRequest']);
    $router->post('/search',['uses'=>'RequestedItme\RequestedItmeController@Search']);
    // $router->post('/update',['uses'=>'RequestedItme\RequestedItmeController@update']);
    $router->delete('/delete/{id}',['uses'=>'RequestedItme\RequestedItmeController@destroy']);
    $router->delete('/delete/folder/{id}',['uses'=>'RequestedItme\RequestedItmeController@destroyfolder']);
});
$router->group(['prefix' => 'admin/approve'], function () use ($router) {
    $router->get('/{id}',['uses'=>'RequestedItme\RequestedItmeController@Approve']);
});