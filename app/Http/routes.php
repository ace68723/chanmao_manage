<?php

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

$app->get('/', function () use ($app) {
    return $app->version();
});



$app->group(['prefix' => 'api/v1','namespace' => 'App\Http\Controllers'], function() use($app)
{   
    
	// Author
    $app->get('author','AuthorController@index');
    
    $app->get('author/{author_id}','AuthorController@getAuthor');
      
    $app->post('author','AuthorController@createAuthor');
      
    $app->put('author/{author_id}','AuthorController@updateAuthor');
      
    $app->delete('author/{author_id}','AuthorController@deleteAuthor');

    // Post
    $app->get('post','PostController@index');
  
    $app->get('post/{id}','PostController@getPost');
      
    $app->post('post','PostController@createPost');
      
    $app->put('post/{id}','PostController@updatePost');
      
    $app->delete('post/{id}','PostController@deletePost');

    // rr_close
    $app->get('rr_close', 'RRCloseController@index');

    $app->get('rr_close/{rid}', 'RRCloseController@getOneClose');

    $app->post('rr_close', 'RRCloseController@createRRClose');

    $app->put('rr_close','RRCloseController@updateRRClose');

    // rr_info
    $app->get('rr_info', 'RRCloseController@getRRInfoClose');

    // rr_role
    $app->get('rr_role', 'RRRoleController@roleCheck');

    // cs_work
    $app->get('cs_work', 'CSWorkController@index');

    $app->get('cs_work_t', 'CSWorkController@getCSThisWeek');

    $app->get('cs_work_t/{uid}', 'CSWorkController@getOneCSThisWeek');
    
    $app->get('cs_work_n/{uid}', 'CSWorkController@getOneCSNextWeek');

    $app->post('cs_work', 'CSWorkController@createCSWork');

    $app->put('cs_work','CSWorkController@updateCSWork');

    $app->patch('cs_work','CSWorkController@deleteCSWork');


    // dr_work
    $app->get('dr_work', 'DRWorkController@index');

    $app->get('dr_work_t', 'DRWorkController@getDRThisWeek');

    $app->get('dr_work_t/{driver_id}', 'DRWorkController@getOneDRThisWeek');

    $app->get('dr_work_n/{driver_id}', 'DRWorkController@getOneDRNextWeek');

    $app->post('dr_work', 'DRWorkController@createDRWork');

    $app->put('dr_work','DRWorkController@updateDRWork');

    $app->patch('dr_work','DRWorkController@deleteDRWork');

    // [CS, DR] Lists
    $app->get('dr_list', 'RoleListController@DRList');

    $app->get('cs_list', 'RoleListController@CSRoles');

    // test
    $app->get('test', 'TestController@test');

    // tools
    $app->get('datesTW', 'ToolController@getThisWeekDates');

    $app->get('datesNW', 'ToolController@getNextWeekDates');

    // rr_location
    $app->get('rr_location/{rid}', 'RRLocationController@getRRLocation');

    $app->post('rr_location', 'RRLocationController@createRRLocation');

    $app->put('rr_location', 'RRLocationController@updateRRLocation');

    // dr_pay
    $app->post('dr_pay_one', 'DriverPayController@getOneDriverTax');

    // bill_sum
    // 前端需要设置一个 要么全部 要么不是全部（一个一个）
    $app->post('get_bills', 'BillSumController@getBills');

    $app->post('save_bills', 'BillSumController@addBills');

    // ads
    $app->get('launch_ad', 'AdvertisementController@getLaunch');

    $app->post('launch_ad', 'AdvertisementController@addLaunch');

    $app->get('top_ad', 'AdvertisementController@getTop');

    $app->post('top_ad', 'AdvertisementController@addTop');

    $app->get('home_ad', 'AdvertisementController@getHome');

    $app->post('home_ad', 'AdvertisementController@addHome');


});

