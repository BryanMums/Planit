<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GanttTask;
use App\Project;
use App\Resource;
use App\GanttTasksDependencie;

use Illuminate\Support\Facades\Auth; //Pour pouvoir utiliser les méthodes de Auth
use Response;

class GanttTasksController extends Controller
{
    // Permet de stocker les tâches.
    public function store(Request $request)
    {
      $project = Project::findOrFail($request->project_id);
      if($project->modify_gantt()){
        $input = array_filter($request->only('parent_id', 'order_id', 'title',
                                              'description', 'date_begin_plan', 'duration_plan',
                                              'hours_plan', 'date_begin_real', 'duration_real',
                                              'hours_real', 'color', 'percent_done'), 'strlen');
        // Création de la tâche
        $ganttTask = $project->gantttasks()->create($input);

        // On va lier les ressources à la tâche
        foreach ((array)$request->resources as $resource_id){
            $resource = Resource::findOrFail($resource_id);
            $ganttTask->resources()->attach($resource);
        }

        // On va créer des dépendances.
        foreach ((array)$request->dependencies as $dependence){
          $values["predecessor_id"] = $dependence;
          $ganttTask->dependencies()->create($values);
        }

        return Response::json($ganttTask);
      }
      return false;
    }

    // Permet de mettre à jour une tâche.
    public function update(Request $request, $id)
    {
      $ganttTask = GanttTask::findOrFail($id);
      $project = Project::findOrFail($ganttTask->project_id);
      if($project->modify_gantt()){
        // Mise à jour des informations de base.
        $ganttTask->update(array_filter($request->only('parent_id', 'order_id', 'title',
                                         'description', 'date_begin_plan', 'duration_plan',
                                         'hours_plan', 'date_begin_real', 'duration_real',
                                         'hours_real', 'color', 'percent_done'), 'strlen'));

        // Mise à jour des ressources.
        $ganttTask->resources()->detach();
        foreach((array)$request->resources as $resource_id){
          $resource = Resource::findOrFail($resource_id);
          $ganttTask->resources()->attach($resource);
        }

        // Mise à jour des dépendances.
        GanttTasksDependencie::where('gantt_task_id', '=', $ganttTask->id)->delete();
        foreach((array)$request->dependencies as $dependence){
          $values["predecessor_id"] = $dependence;
          $ganttTask->dependencies()->create($values);
        }

        return Response::json($ganttTask);
      }
      return false;
    }

    // Permet de récupérer les informations d'une tâche, retournées en JSON.
    public function show($id)
    {
      $ganttTask = GanttTask::with('resources', 'dependencies')->findOrFail($id);
      $project = Project::findOrFail($ganttTask->project_id);
      if($project->see_gantt()){
        return Response::json($ganttTask);
      }
      return false;
    }

    // Permet de supprimer une tâche.
    public function destroy($id)
    {
      $ganttTask = GanttTask::findOrFail($id);
      $project = Project::findOrFail($ganttTask->project_id);
      if($project->modify_gantt()){
        $ganttTask = GanttTask::destroy($id);

        return Reponse::json($ganttTask);
      }
      return false;
    }
}
