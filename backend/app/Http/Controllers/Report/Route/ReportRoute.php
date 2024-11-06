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
// app.post('/report/dashbored/:id?', ReportController.GetDashboredReport)
// app.post('/report/dashbored/all/:id?', ReportController.GetReportDashboredAll)
// app.post('/report/transfer/return/all/report/:id', ReportController.getReturnTransferReportAllReport)
// app.post('/report/bank/all/report/:id', ReportController.GetBacnkReportAllReport)
// app.get('/report/by/filter/:id', ReportController.GetReportByFilter)
$router->group(['prefix' => 'admin/report','middleware'=>'admin'], function () use ($router) {
    $router->post('/dashbored/{id}',['uses'=>'Report\ReportController@GetDashboredReport']);
    $router->post('/dashbored/casher/{id}',['uses'=>'Report\ReportController@GetDashboredReportCasher']);
    $router->post('/dashbored/casher/bank/{id}',['uses'=>'Report\ReportController@GetDashboredReportCasherBank']);
    $router->get('/dashbored/casher/graph/',['uses'=>'Report\ReportController@getGraphdata']);
    $router->post('/dashbored/all/{id}',['uses'=>'Report\ReportController@GetReportDashboredAll']);
    $router->post('/by/date',['uses'=>'Report\ReportController@GetReportByDate']);
    $router->post('/export/expense/{id}',['uses'=>'Report\ReportController@ExportExpensese']);
    $router->get('/bank/{id}/get',['uses'=>'Report\ReportController@GetBacnkReport']);
    $router->get('/bank/all',['uses'=>'Report\ReportController@GetBacnkReportAll']);
    $router->post('/bank/all/report/{id}',['uses'=>'Report\ReportController@GetBacnkReportAllReport']);
    $router->get('/transfer/return/{id}/get',['uses'=>'Report\ReportController@getReturnTransferReport']);
    $router->get('/transfer/return/all',['uses'=>'Report\ReportController@getReturnTransferReportAll']);
    $router->post('/transfer/return/all/report/{id}',['uses'=>'Report\ReportController@getReturnTransferReportAllReport']);

    $router->get('/by/filter/{id}',['uses'=>'Report\ReportController@GetReportByFilter']);
    $router->post('/casher/by/date',['uses'=>'Report\ReportController@GetReportByFilterCasher']);

    // $router->get('/casher/filter/{id}',['uses'=>'Report\ReportController@GetReportByFilterCasher']);
    $router->get('/reported',['uses'=>'Report\ReportController@Reported']);
    $router->get('/folder',['uses'=>'Report\ReportController@ReportFolder']);
    $router->get('/folder/get/all',['uses'=>'Report\ReportController@ReportFolderAll']);

    // $router->post('/store',['uses'=>'Report\ReportController@store']);
    // $router->put('/update/{id}',['uses'=>'Report\ReportController@update']);
    // $router->delete('/delete/{id}',['uses'=>'Report\ReportController@destroy']);
});
$router->group(['prefix' => 'users/report'], function () use ($router) {
    $router->post('/dashbored/{id}',['uses'=>'Report\ReportController@GetDashboredReport']);
    $router->post('/dashbored/casher/{id}',['uses'=>'Report\ReportController@GetDashboredReportCasher']);
    $router->get('/dashbored/casher/graph/',['uses'=>'Report\ReportController@getGraphdata']);



});