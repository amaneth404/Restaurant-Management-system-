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
// app.post('/changeusername', UserController.changeUsername)
// app.post('/changepassword', UserController.changePassword)
// app.post('/changephonenumber', UserController.ChangePhoneNumber)
// app.post('/update', UserController.updateUserProfile)

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('/get/all',['uses'=>'User\UserController@index']);
    $router->get('/profile',['uses'=>'User\UserController@UserProfile']);
    $router->get('/get/profile/{id}',['uses'=>'User\UserController@GetUserProfile']);
    $router->post('/changeusername',['uses'=>'User\UserController@changeUsername']);
    $router->post('/changePassword',['uses'=>'User\UserController@changeUserPassword']);
    $router->post('/ChangePhoneNumber',['uses'=>'User\UserController@changephonenumber']);
    $router->post('/update',['uses'=>'User\UserController@updateUserProfile']);

    $router->get('/get/credit',['uses'=>'User\UserController@GetCredit']);
    $router->post('/credit/pay',['uses'=>'User\UserController@PayCredit']);

});
$router->group(['prefix' => 'admin/users','middleware'=>'admin'], function () use ($router) {
    $router->get('/',['uses'=>'User\UserController@index']);
    $router->post('/remark/{id}',['uses'=>'User\UserController@Remark']);
    $router->get('/salary',['uses'=>'User\UserController@GetSalary']);
    $router->get('/pay/{id}',['uses'=>'User\UserController@Pay']);
    $router->get('/waiter',['uses'=>'User\UserController@UserProfileWaitre']);
    $router->get('/chef',['uses'=>'User\UserController@UserProfilechef']);
    $router->get('/show/{id}',['uses'=>'User\UserController@showUser']);
    $router->get('/disable/{id}',['uses'=>'User\UserController@DActivet']);
    $router->get('/enable/{id}',['uses'=>'User\UserController@Activet']);
    $router->delete('/delete/{id}',['uses'=>'User\UserController@Delete']);
    $router->post('/search',['uses'=>'User\UserController@SearchUsers']);
    $router->post('/register',['uses'=>'User\UserController@Store']);
    $router->put('/update/{id}',['uses'=>'User\UserController@Update']);
    $router->put('/change/password/{id}',['uses'=>'User\UserController@ChangePassword']);

});
$router->group(['prefix' => 'admin/branches','middleware'=>'admin'], function () use ($router) {
    $router->get('/',['uses'=>'User\UserController@GetBranches']);
    $router->get('/managers',['uses'=>'User\UserController@GetBranchesWithAdmin']);
});
