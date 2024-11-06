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
$router->group(['prefix' => 'admin/casher','middleware'=>['admin']], function () use ($router) {
    $router->get('/reports',['uses'=>'Casher\CasherController@getCasherReport']);
    $router->get('/given/{id}',['uses'=>'Casher\CasherController@Given']);


    $router->get('/{type}/order',['uses'=>'Casher\CasherController@index']);
    $router->get('/flaged/orders/',['uses'=>'Casher\CasherController@FlagedOrders']);
    $router->get('/flaged/orders/all',['uses'=>'Casher\CasherController@FlagedOrdersAll']);
    $router->get('/payed/orders/all',['uses'=>'Casher\CasherController@PayedOrders']);
    $router->get('/flaged/delivery/orders/all',['uses'=>'Casher\CasherController@deliveryFlagedOrdersAll']);
    $router->get('/payed/delivery/orders/all',['uses'=>'Casher\CasherController@deliveryPayedOrders']);
    $router->get('/view/{id}',['uses'=>'Casher\CasherController@CasherWithOrder']);
    $router->get('/flag/{id}',['uses'=>'Casher\CasherController@Flag']);
    $router->get('/unflag/{id}',['uses'=>'Casher\CasherController@UnFlag']);
    $router->post('/store',['uses'=>'Casher\CasherController@store']);
    $router->post('/changeconfig',['uses'=>'Casher\CasherController@cahgeOrderConfig']);
    
    $router->post('/paye/{id}',['uses'=>'Casher\CasherController@Payed']);
    $router->post('/bank/update',['uses'=>'Casher\CasherController@UpdateBank']);
    $router->post('/search',['uses'=>'Casher\CasherController@Search']);
    $router->post('/update',['uses'=>'Casher\CasherController@update']);
    $router->delete('/delete/{id}',['uses'=>'Casher\CasherController@destroy']);
});
$router->group(['prefix' => 'user/waiter','middleware'=>['admin']], function () use ($router) {
    $router->get('/reports',['uses'=>'Casher\CasherController@getWaiterReport']);
    $router->get('/given/{id}/{amount}',['uses'=>'Casher\CasherController@WaiterGiven']);
});
$router->group(['prefix' => 'user/chef'], function () use ($router) {
    $router->get('/order',['uses'=>'Casher\CasherController@Chefeindex']);
    $router->get('/order/admin',['uses'=>'Casher\CasherController@Chefeindexadmin']);
    $router->get('/view/{id}',['uses'=>'Casher\CasherController@CasherWithOrder']);
    $router->get('/done/{id}',['uses'=>'Casher\CasherController@ChefDone']);
    $router->get('/done/all/{id}',['uses'=>'Casher\CasherController@ChefDoneAll']);
    $router->get('/grouped',['uses'=>'Casher\CasherController@GroupedOrder']);
    $router->get('/grouped/done',['uses'=>'Casher\CasherController@GroupedOrderDone']);
    
    

});