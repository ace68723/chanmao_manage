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

    $app->post('rr_close', 'RRCloseController@createRRClose');

    $app->put('rr_close','RRCloseController@updateRRClose');

    // rr_role
    $app->get('rr_role', 'RRRoleController@roleCheck');

});

