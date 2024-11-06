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
// require_once('App\Http\Controllers\User\UserRoute');
$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/store/api/v1/login',['uses'=>'User\AuthController@login']);
$router->get('/testing',['uses'=>'Bank\BankController@webRequest']);
$router->post('/store/api/v1/register',['uses'=>'User\AuthController@RegisterUser']);
$router->group(['prefix' => 'store/api/v1/','middleware'=>'auth'], function () use ($router) {
    require __DIR__.'/../app/Http/Controllers/User/Route/UserRoute.php';
    require __DIR__.'/../app/Http/Controllers/RequestedItme/Route/RequestedItmeRoute.php';
    require __DIR__.'/../app/Http/Controllers/Category/Route/CategoryRoute.php';
    require __DIR__.'/../app/Http/Controllers/Expense/Route/ExpenseRoute.php';
    require __DIR__.'/../app/Http/Controllers/Casher/Route/CasherRoute.php';
    require __DIR__.'/../app/Http/Controllers/MenuCategory/Route/CategoryRoute.php';
    require __DIR__.'/../app/Http/Controllers/Menu/Route/MenuRoute.php';
    require __DIR__.'/../app/Http/Controllers/ItemGroup/Route/ItemGroupRoute.php';
    require __DIR__.'/../app/Http/Controllers/Item/Route/ItemRoute.php';
    require __DIR__.'/../app/Http/Controllers/Bank/Route/BankRoute.php';
    require __DIR__.'/../app/Http/Controllers/TotalCash/Route/TotalCashRoute.php';
    require __DIR__.'/../app/Http/Controllers/Report/Route/ReportRoute.php';
});
