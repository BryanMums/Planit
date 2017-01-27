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
	}

    // Méthode appelée pour afficher la page d'accueil d'un projet.
    public function index(Project $project)
    {
        if($project->is_admin() || $project->is_collaborater())
        {
            return view('projects.index', compact('project'));
        }else{
            return view('projects.error', compact('project'));
        }
    }

    // Permet de récupérer les informations d'un projet, en JSON.
    public function ProjectJson(Project $project)
    {
      if($project->see_informations()) return Response::json($project);
      return false;
    }

    // Permet d'afficher la page de création d'un projet.
    public function create()
    {
      return view('projects.create');
    }

    // Permet de mettre à jour un projet.
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if($project->modify_informations()){
          $project->update($request->only('name', 'description', 'budget', 'date_begin', 'date_end',
                                          ' client_name', 'client_tel', 'client_mail'));

          return Response::json($project);
        }
        return false;
    }

    // Permet de stocker un projet.
    public function store(Request $request)
    {
      Auth::user()->projects()->create($request->only('name', 'description', 'budget',
                                                  'date_begin', 'date_end', 'client_name',
                                                  'client_mail', 'client_tel'));
      return redirect('/home')->with('status', 'Projet créé !');
    }

    // Permet de supprimer un projet.
    public function destroy($id){
      $project = Project::findOrFail($id);
      if($project->is_admin()){
        $project->resources()->delete();
        $project->costs()->delete();
        $project->gantttasks()->delete();
        $project->collaboraters()->delete();
        $project->destroy($project->id);
        return Response::json($project);
      }
      return false;
    }

    // Méthode appelée lorsqu'un collaborateur quitte un projet.
    public function quitProject(Project $project){
      if($project->is_collaborater()){
        $collaborater = Collaborater::where('user_id', '=', Auth::id())->where('project_id', '=', $project->id)->delete();
        if($collaborater){
          return redirect('/home')->with('status', 'Vous avez bien quitté le projet !');
        }
        return view('projects.index', compact($project));
      }
      return view('projects.error', compact('project'));
    }

    // Méthode appelée pour afficher la page des coûts.
    public function finance(Project $project)
    {
      if($project->see_finance()){
        return view('projects.finance', compact('project'));
      }
        return view('projects.error', compact('project'));
    }

    // Méthode appelée pour afficher la page de la planification.
    public function planification(Project $project)
    {
      if($project->see_gantt()){
          return view('projects.planification', compact('project'));
      }
      return view('projects.error', compact('project'));
    }

    // Méthode appelée pour afficher la page des statistiques.
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
    // Permet d'afficher la page de création d'un collaborateur.
    public function createCollaborater(Project $project){
      if($project->modify_collaboraters()){
        return view('collaboraters.create', compact('project'));
      }
      return view('projects.error');
    }

    // Méthode appelée pour stocker un collaborateur puis rediriger sur la bonne page.
    public function storeCollaborater(Project $project, Request $request){
        if($project->modify_collaboraters()){
          $col = new CollaboratersController;
          $col->store($request);

          return redirect('/project'.'/'.$project->id)->with('status', 'Nouveau collaborateur ajouté !');
        }
        return view('projects.error');
    }

    /*************METHODS RESOURCES********************/
    // Permet d'afficher la page de création de ressources.s
    public function createResource(Project $project){
      if($project->modify_resources()){
          return view('resources.create', compact('project'));
      }
      return view('projects.error');
    }

    // Méthode appelée pour stocker une ressource puis rediriger sur la bonne page.
    public function storeResource(Project $project, Request $request){
      if($project->modify_resources()){
        $res = new ResourcesController;
        $res->store($request);
        return redirect('/project'.'/'.$project->id)->with('status', 'Nouvelle ressource ajoutée !');
      }
      return view('projects.error');
    }

    // Permet de retourner sous le format JSON, la liste des ressources du projet.
    public function getResources($id){
      $project = Project::findOrFail($id);
      if($project->see_resources()){
        $resources = Resource::all()->where('project_id', $id);
        return Response::json($resources);
      }
      return false;
    }

    /*********METHODS COSTS**************/
    // Permet de retourner la page de création d'un coût.
    public function createCost(Project $project){
      if($project->modify_finance()){
          return view('costs.create', compact('project'));
      }
      return view('projects.error');
    }

    // Méthode appelée pour stocker un coût puis rediriger sur la bonne page.
    public function storeCost(Project $project, Request $request){
      if($project->modify_finance()){
        $cos = new CostsController;
        $cos->store($request);
        return redirect('/project'.'/'.$project->id.'/finance')->with('status', 'Nouveau coût ajouté !');
      }
      return view('projects.error');
    }

    /*******METHODS TASKS*************/
    // Méthode appelée pour stocker une tâche.
    public function storeGantttask(Project $project, Request $request){
      if($project->modify_gantt()){
        $tas = new GanttTasksController;
        return $tas->store($request);
      }
    }

    // Méthode appelée pour mettre à jour une tâche.
    public function updateGantttask(Project $project, GanttTask $task, Request $request){
      if($project->modify_gantt()){
        $tas = new GanttTasksController;
        return $tas->update($request, $task->id);
      }
    }

    // Permet de retourner sous le format JSON, la liste des tâches du projet.
    public function getTasks($id){
      $project = Project::findOrFail($id);
      if($project->see_gantt()){
        $tasks = GanttTask::with('resources', 'dependencies')->where('project_id', $id)->get();
        return Response::json($tasks);
      }
    }

}
