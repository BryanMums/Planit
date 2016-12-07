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
		//$this->middleware('rights', ['index' => ['is_admin', 'is_collaborator']]);
	}

    public function index(Project $project)
    {
        if($project->is_admin() || $project->is_collaborater())
        {
            return view('projects.index', compact('project'));
        }else{
            return view('projects.error');
        }
    }

    public function create()
    {
      return view('projects.create');
    }

    public function store(Request $request)
    {
      Auth::user()->projects()->create($request->only('name', 'description', 'budget',
                                                  'date_begin', 'date_end', 'client_name',
                                                  'client_mail', 'client_tel'));
      return redirect('/home')->with('status', 'Projet créé !');
    }

    public function delete(Project $project)
    {
      if($project->is_admin()){
        //delete
      }
      return view('projects.error');
    }

    public function finance(Project $project)
    {
      if($project->see_finance()){
        return view('projects.finance', compact('project'));
      }
        return view('projects.error', compact('project'));
    }

    public function planification(Project $project)
    {
      if($project->see_gantt()){
          return view('projects.planification', compact('project'));
      }
      return view('projects.error', compact('project'));
    }

    public function statistics(Project $project)
    {
      return view('projects.statistics', compact('project'));
    }


    /*************METHODS COLLABORATER********************/
    public function createCollaborater(Project $project){
      if($project->modify_collaboraters()){
        return view('collaboraters.create', compact('project'));
      }
      return view('projects.error');
    }

    public function storeCollaborater(Project $project, Request $request){
        if($project->modify_collaboraters()){
          $col = new CollaboratersController;
          $col->store($request);

          return redirect('/project'.'/'.$project->id)->with('status', 'Nouveau collaborateur ajouté !');
        }
        return view('projects.error');
    }
      /*************METHODS RESOURCES********************/
    public function createResource(Project $project){
      if($project->modify_resources()){
          return view('resources.create', compact('project'));
      }
      return view('projects.error');
    }

    public function storeResource(Project $project, Request $request){
      if($project->modify_resources()){
        $res = new ResourcesController;
        $res->store($request);
		// route('project.show', $project->id)
        return redirect('/project'.'/'.$project->id)->with('status', 'Nouvelle ressource ajoutée !');
      }
      return view('projects.error');
    }

    /*********METHODS COSTS**************/
    public function createCost(Project $project){
      if($project->modify_finance()){
          return view('costs.create', compact('project'));
      }
      return view('projects.error');
    }

    public function storeCost(Project $project, Request $request){
      if($project->modify_finance()){
        $cos = new CostsController;
        $cos->store($request);
        return redirect('/project'.'/'.$project->id.'/finance')->with('status', 'Nouveau coût ajouté !');
      }
      return view('projects.error');
    }

}
