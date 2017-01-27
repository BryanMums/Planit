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
use App\Project;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('autocomplete', function()
{
    return view('autocomplete');
});

// Autocompletion pour l'ajout du collaborateur
Route::get('getusers/{project}', function(Project $project)
{
    $term = strtolower(Request::input('term'));


    $users = App\User::where('email','LIKE', '%'.$term.'%')->orWhere('name', 'LIKE', '%'.$term.'%')->get();
    $return_array = [];
    foreach ($users as $user) {
      if($user->id != Auth::id()){
        $there = false;
        foreach($project->collaboraters as $collaborater){
          if($collaborater->user_id == $user->id)
            $there = true;
        }
        if(!$there){
            $return_array[] = array('value' => $user->email, 'id' => $user->id);
        }
      }
    }
    return Response::json($return_array);
});

/**********************PROJECTS-WEB***********************/
Route::get('/project/create', 'ProjectsController@create');
Route::get('/project/{project}', 'ProjectsController@index');

Route::get('project/{project}/finance', 'ProjectsController@finance');
Route::get('project/{project}/planification', 'ProjectsController@planification');
Route::get('project/{project}/statistics', 'ProjectsController@statistics');

Route::resource('project', 'ProjectsController', ['only' => [
  'destroy', 'update'
  ]]);
Route::get('project/{project}/json', 'ProjectsController@ProjectJson');

Route::post('/project/create', 'ProjectsController@store');
Route::delete('/project/{project}/delete', 'ProjectsController@delete');

/**********************COLLABORATERS***********************/
Route::get('/project/{project}/collaborater/create', 'ProjectsController@createCollaborater');


Route::post('/project/{project}/collaborater/create', 'ProjectsController@storeCollaborater');

Route::resource('collaborater', 'CollaboratersController', ['only' => [
  'show', 'store', 'update', 'destroy'
  ]]);
Route::get('/project/{project}/quit', 'ProjectsController@quitProject');

/**********************RESOURCES***********************/
Route::get('/project/{project}/resource/create', 'ProjectsController@createResource');
Route::post('/project/{project}/resource/create', 'ProjectsController@storeResource');
Route::get('/project/{id}/resources', 'ProjectsController@getResources');

Route::resource('resource', 'ResourcesController', ['only' => [
  'show', 'store', 'update', 'destroy'
  ]]);


/*******************COSTS**************************/
Route::get('/project/{project}/cost/create', 'ProjectsController@createCost');
Route::post('/project/{project}/cost/create', 'ProjectsController@storeCost');
Route::resource('cost', 'CostsController', ['only' => [
  'show', 'store', 'update', 'destroy'
  ]]);

/*****************TASKS**************************/
Route::get('/project/{id}/tasks', 'ProjectsController@getTasks');
Route::put('/project/{project}/gantttask/{task}', 'ProjectsController@updateGantttask');
Route::post('/project/{project}/gantttask', 'ProjectsController@storeGantttask');
Route::resource('gantttask', 'GanttTasksController', ['only' => [
  'show', 'store', 'update', 'destroy'
  ]]);


Route::get('/home', 'HomeController@index');
