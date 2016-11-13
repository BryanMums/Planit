<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; //Pour pouvoir utiliser les méthodes de Auth

use App\Project;
use App\Collaborater;
use App\Resource;

use App\Http\Requests;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Project $project)
    {
      return view('projects.index', compact('project'));
    }

    public function create()
    {
      return view('projects.create');
    }

    public function store(Request $request)
    {
      $project = new Project();

      $project->name = $request->name;
      $project->description = $request->description;
      $project->budget = $request->budget;
      $project->date_begin = $request->date_begin;
      $project->date_end = $request->date_end;
      $project->client_name = $request->client_name;
      $project->client_mail = $request->client_mail;
      $project->client_tel = $request->client_tel;
      $project->user_id = Auth::id();

      $project->save();

      return redirect('/home')->with('status', 'Projet créé !');
    }

    public function delete(Project $project)
    {
      return "lol";
    }

    /*************METHODS COLLABORATER********************/
    public function createCollaborater(Project $project){
      return view('collaboraters.create', compact('project'));
    }

    public function storeCollaborater(Project $project, Request $request){

      if(Auth::id() != $request->id_user){
        $col = new CollaboratersController;
        $col->store($request);

        return redirect('/project'.'/'.$project->id)->with('status', 'Nouveau collaborateur ajouté !');
      }
    }
      /*************METHODS RESOURCES********************/
      public function createResource(Project $project){
        return view('resources.create', compact('project'));
      }

      public function storeResource(Project $project, Request $request){


          $resource = new Resource();

          $resource->firstname = $request->firstname;
          $resource->lastname = $request->lastname;
          $resource->email = $request->email;
          $resource->role = $request->role;
          $resource->cost_initial = $request->cost_initial;
          $resource->cost_per_hour = $request->cost_per_hour;
          $resource->project_id = $project->id;

          $resource->save();

          return redirect('/project'.'/'.$project->id)->with('status', 'Nouvelle ressource ajoutée !');

          //dd($request);

    }
}
