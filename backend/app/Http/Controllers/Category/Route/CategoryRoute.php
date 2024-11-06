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

$router->group(['prefix' => 'admin/category','middleware'=>['admin']], function () use ($router) {
    $router->get('/',['uses'=>'Category\CategoryController@index']);
    $router->get('/with/item',['uses'=>'Category\CategoryController@CategoryWithitem']);
    $router->post('/store',['uses'=>'Category\CategoryController@store']);
    $router->post('/search',['uses'=>'Category\CategoryController@Search']);
    $router->post('/update',['uses'=>'Category\CategoryController@update']);
    $router->delete('/delete/{id}',['uses'=>'Category\CategoryController@destroy']);


    $router->get('/expense',['uses'=>'Category\CategoryController@indexexpense']);
    $router->post('/expense/store',['uses'=>'Category\CategoryController@storeexpense']);
    $router->post('/expense/search',['uses'=>'Category\CategoryController@Searchexpense']);
    $router->post('/expense/update',['uses'=>'Category\CategoryController@updateexpense']);
    $router->delete('/expense/delete/{id}',['uses'=>'Category\CategoryController@destroyexpense']);
});






