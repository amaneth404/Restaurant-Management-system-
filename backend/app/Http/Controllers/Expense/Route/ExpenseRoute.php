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

$router->group(['prefix' => 'admin/expense','middleware'=>['admin']], function () use ($router) {
    $router->get('/',['uses'=>'Expense\ExpenseController@index']);
    $router->get('/filter/{id}',['uses'=>'Expense\ExpenseController@indexFilter']);
    $router->post('/store',['uses'=>'Expense\ExpenseController@store']);
    $router->post('/update',['uses'=>'Expense\ExpenseController@update']);
    $router->delete('/delete/{id}',['uses'=>'Expense\ExpenseController@destroy']);
    
});
