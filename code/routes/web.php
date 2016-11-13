<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use App\Collaborater;
use App\Resource;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('autocomplete', function()
{
    return view('autocomplete');
});

Route::get('getusers', function()
{
    $term = strtolower(Request::input('term'));

    $users = App\User::where('email','LIKE', '%'.$term.'%')->orWhere('name', 'LIKE', '%'.$term.'%')->get();

    foreach ($users as $user) {
      $return_array[] = array('value' => $user->email, 'id' => $user->id);
    }
    return Response::json($return_array);
});

/**********************PROJECTS-WEB***********************/
Route::get('/project/create', 'ProjectsController@create');
Route::get('/project/{project}', 'ProjectsController@index');

Route::post('/project/create', 'ProjectsController@store');
Route::delete('/project/{project}/delete', 'ProjectsController@delete');

/**********************COLLABORATERS***********************/
Route::get('/project/{project}/collaborater/create', 'ProjectsController@createCollaborater');


Route::get('/collaborater/{id?}', 'CollaboratersController@get');
Route::post('/project/{project}/collaborater/create', 'ProjectsController@storeCollaborater');
Route::put('/collaborater/{id?}', 'CollaboratersController@update');
Route::delete('/collaborater/{id?}', 'CollaboratersController@destroy');


/**********************RESOURCES***********************/
Route::get('/project/{project}/resource/create', 'ProjectsController@createResource');
Route::post('/project/{project}/resource/create', 'ProjectsController@storeResource');
Route::get('/resource/{id?}', 'ResourcesController@get');
Route::put('/resource/{id?}', 'ResourcesController@update');
Route::delete('/resource/{id?}', 'ResourcesController@destroy');


Route::get('/home', 'HomeController@index');
