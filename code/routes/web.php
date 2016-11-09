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
Route::get('/project/create', 'ProjectsController@create'); //Création d'un nouveau projet
Route::get('/project/{project}', 'ProjectsController@index'); //Liste des projets de l'utilisateur

Route::post('/project/create', 'ProjectsController@store'); //Store
Route::delete('/project/{project}/delete', 'ProjectsController@delete');

Route::get('/project/{project}/collaborater/create', 'ProjectsController@createCollaborater');
Route::post('/project/{project}/collaborater/create', 'ProjectsController@storeCollaborater');
Route::delete('/collaborater/{collaborater_id?}',function($collaborater_id){
    $collaborater = Collaborater::destroy($collaborater_id);

    return Response::json($collaborater);
});
Route::get('/home', 'HomeController@index');
