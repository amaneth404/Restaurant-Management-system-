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
$router->group(['prefix' => 'admin/item','middleware'=>['admin','store']], function () use ($router) {
    $router->put('/disable/{id}',['uses'=>'ItemGroup\ItemGroupController@DActivet']);
    $router->put('/enable/{id}',['uses'=>'ItemGroup\ItemGroupController@Activet']);
    $router->get('/web',['uses'=>'ItemGroup\ItemGroupController@index']);
    $router->get('/',['uses'=>'ItemGroup\ItemGroupController@indexAll']);
    $router->get('/for/filter',['uses'=>'ItemGroup\ItemGroupController@indexAll']);
    $router->post('/search',['uses'=>'ItemGroup\ItemGroupController@Search']);
    $router->post('/store',['uses'=>'ItemGroup\ItemGroupController@store']);
    $router->put('/update/{id}',['uses'=>'ItemGroup\ItemGroupController@update']);
    $router->delete('/delete/{id}',['uses'=>'ItemGroup\ItemGroupController@destroy']);
});

$router->group(['prefix' => 'users/item'], function () use ($router) {
  
    $router->get('/get',['uses'=>'ItemGroup\ItemGroupController@indexAll']);
    $router->get('/for/filter',['uses'=>'ItemGroup\ItemGroupController@indexAll']);

   
});