<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; //Pour pouvoir utiliser les méthodes de Auth

use App\Project;
use App\Collaborater;

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

    public function createCollaborater(Project $project){
      return view('collaboraters.create', compact('project'));
    }

    public function delete(Project $project)
    {
      return "lol";
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

    public function storeCollaborater(Project $project, Request $request){

      if(Auth::id() != $request->id_user){
        $collaborater = new Collaborater();

        $collaborater->user_id = $request->id_user;
        $collaborater->project_id = $project->id;
        $collaborater->informations_rights = $request->inforadio;
        $collaborater->gantt_rights = $request->ganttradio;
        $collaborater->budget_rights = $request->budgetradio;

        $collaborater->save();

        return redirect('/project'.'/'.$project->id)->with('status', 'Nouveau collaborateur ajouté !');
      }
    
    }
}
