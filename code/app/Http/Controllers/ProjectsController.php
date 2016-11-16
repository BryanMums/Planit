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
      return "delete";
    }

    public function finance(Project $project)
    {
      return view('projects.finance', compact('project'));
    }

    public function planification(Project $project)
    {
      return view('projects.planification', compact('project'));
    }

    public function statistics(Project $project)
    {
      return view('projects.statistics', compact('project'));
    }

    /*************METHODS COLLABORATER********************/
    public function createCollaborater(Project $project){
      return view('collaboraters.create', compact('project'));
    }

    public function storeCollaborater(Project $project, Request $request){
        $col = new CollaboratersController;
        $col->store($request);

        return redirect('/project'.'/'.$project->id)->with('status', 'Nouveau collaborateur ajouté !');
    }
      /*************METHODS RESOURCES********************/
    public function createResource(Project $project){
      return view('resources.create', compact('project'));
    }

    public function storeResource(Project $project, Request $request){
        $res = new ResourcesController;
        $res->store($request);
        return redirect('/project'.'/'.$project->id)->with('status', 'Nouvelle ressource ajoutée !');
    }

    /*********METHODS COSTS**************/
    public function createCost(Project $project){
      return view('costs.create', compact('project'));
    }

    public function storeCost(Project $project, Request $request){
      $cos = new CostsController;
      $cos->store($request);
      return redirect('/project'.'/'.$project->id.'/finance')->with('status', 'Nouveau coût ajouté !');
    }
}
