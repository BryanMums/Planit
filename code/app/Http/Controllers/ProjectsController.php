<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; //Pour pouvoir utiliser les méthodes de Auth

use App\Project;
use App\Collaborater;
use App\Resource;
use App\GanttTask;

use App\Http\Requests;
use Response;

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

      // Pourcentage du temps restant.
      //$time = new DateTime($project->date_begin);
      $time_begin = strtotime($project->date_begin);
      $time_end = strtotime($project->date_end);
      $time_today = time();

      $nb_days_total = floor(($time_end - $time_begin) / (60 * 60 * 24));
      $nb_days_from_begin = floor(($time_today - $time_begin) / (60 * 60 * 24));

      $days = [$nb_days_total, $nb_days_from_begin];

      // Valeurs pour la répartition resources-heures plan/real
      $resources_hours = [];
      $costs = [];

      // On va récupérer la somme totale des coûts hors-ressources.
      $sum_costs = 0;
      foreach($project->costs as $cost){
        $sum_costs += $cost->value;
      }
      $c = ["Achats", 0, $sum_costs];
      array_push($costs, $c);

      foreach($project->resources as $resource){
        $hours_plan = 0;
        $hours_real = 0;
        $complete_name = $resource->firstname . " " . $resource->lastname;
          foreach($resource->ganttTasks as $ganttTask){
            $hours_plan += $ganttTask->hours_plan;
            $hours_real += $ganttTask->hours_real;
          }
          $t = [$complete_name, $hours_plan, $hours_real];
          array_push($resources_hours, $t);

          $total_cost_plan = $resource->cost_initial + ($hours_plan * $resource->cost_per_hour);
          $total_cost_real = $resource->cost_initial + ($hours_real * $resource->cost_per_hour);
          $c = [$complete_name, $total_cost_plan, $total_cost_real];
          array_push($costs, $c);
      }
      return view('projects.statistics', compact('project', 'resources_hours', 'costs', 'days'));
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

    public function getResources($id){
          $resources = Resource::all()->where('project_id', $id);
          return Response::json($resources);
    }

    public function getTasks($id){
        $tasks = GanttTask::all()->where('project_id', $id);
        return Response::json($tasks);
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

    /*******METHODS TASKS*************/
    public function createGantttask(Project $project, Request $request){
      if($project->modify_gantt()){
        $tas = new GanttTasksController;
        return $tas->store($request);
        //Penser après à mettre à jour partout
      }
    }

    public function updateGantttask(Project $project, GanttTask $task, Request $request){
      if($project->modify_gantt()){
        $tas = new GanttTasksController;
        return $tas->update($request, $task->id);
      }
    }

}
