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
$router->group(['prefix' => 'admin/items' ,'middleware'=>'admin'], function () use ($router) {
    $router->get('/',['uses'=>'Item\ItemController@index']);
    
    

    $router->post('/log/get/all/report/{id}',['uses'=>'Item\ItemController@indexItemLogAllReport']);
    $router->post('/log/get/all/report/data/{id}',['uses'=>'Item\ItemController@indexItemInShopStoreAllReport']);
    $router->post('/get/all/shop/report/{id}',['uses'=>'Item\ItemController@indexItemInShopAllReport']);
    $router->post('/get/all/store/report/{id}',['uses'=>'Item\ItemController@indexItemInStoreAllReport']);
    $router->get('/log/get/all',['uses'=>'Item\ItemController@indexItemLogAll']);
    $router->get('/log',['uses'=>'Item\ItemController@indexItemLog']);
    // $router->get('/find/{id}',['uses'=>'Item\ItemController@find']);
    $router->get('/filter/admin/{ismain}',['uses'=>'Item\ItemController@indexAdmin']);
    $router->get('/filter/{id}',['uses'=>'Item\ItemController@FilterItems']);
    $router->get('/filter/log/{id}',['uses'=>'Item\ItemController@FilterItemsLog']);
    $router->get('/filter/transfered/{id}',['uses'=>'Item\ItemController@FilterTransfered']);
    $router->get('/filter/dashbored/{id}/{ismain}',['uses'=>'Item\ItemController@FilterItemsDashbored']);
    $router->put('/disable/{id}',['uses'=>'Item\ItemController@DActivet']);
    $router->put('/transfer/{id}',['uses'=>'Item\ItemController@TransferToSub']);
    $router->put('/enable/{id}',['uses'=>'Item\ItemController@Activet']);
    $router->post('/in/branch/{id}',['uses'=>'Item\ItemController@ItemInBranch']);
    $router->post('/in/item/{branch}',['uses'=>'Item\ItemController@getTotalItemsByitem']);
    $router->put('/update/{id}',['uses'=>'Item\ItemController@update']);
    $router->delete('/delete/{id}',['uses'=>'Item\ItemController@destroy']);
    $router->get('/sell/{id}/{amount}',['uses'=>'Item\ItemController@SellItems']);
    $router->get('/link/{id}/{menu_id}',['uses'=>'Item\ItemController@Link']);
    $router->get('/unlink/{id}',['uses'=>'Item\ItemController@UnLink']);
    $router->post('/transfer/{id}',['uses'=>'Item\ItemController@transfer']);
    $router->get('/transfer/history/data',['uses'=>'Item\ItemController@transferHistory']);
    $router->get('/transfer/history/all',['uses'=>'Item\ItemController@transferAllHistory']);
});
$router->group(['prefix' => 'users/items','middleware'=>['admin','store']], function () use ($router) {
    $router->get('/transfer/to/shope/{id}/{amount}',['uses'=>'Item\ItemController@TransferFromStoreToMain']);
    $router->post('/store',['uses'=>'Item\ItemController@store']);
    $router->post('/request/update/name/{id}',['uses'=>'Item\ItemController@UpdateRequestName']);
    $router->get('/request/sell/{id}',['uses'=>'Item\ItemController@SellItemsRequest']);
    $router->get('/get',['uses'=>'Item\ItemController@indexUser']);
    $router->put('/return/{id}',['uses'=>'Item\ItemController@ReturnToMain']);
    $router->put('/enable/{id}',['uses'=>'Item\ItemController@Activet']);
    $router->put('/disable/{id}',['uses'=>'Item\ItemController@DActivet']);
    $router->get('/filter/{id}',['uses'=>'Item\ItemController@FilterItems']);


});