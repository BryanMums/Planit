<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use App\Project;
use Response;

class ResourcesController extends Controller
{
  // Permet de stocker les ressources.
  public function store(Request $request)
  {
    $project = Project::findOrFail($request->project_id);
    if($project->modify_resources()){
      $resource = $project->resources()->create($request->only('firstname', 'lastname', 'email',
                                                              'role', 'cost_initial', 'cost_per_hour'));
      return Response::json($resource);
    }
    return false;
  }


  // Permet de mettre à jour les ressources.
  public function update(Request $request, $id)
  {
    $resource = Resource::findOrFail($id);
    $project = Project::findOrFail($resource->project_id);
    if($project->modify_resources()){
      $resource->update($request->only('firstname', 'lastname', 'email',
                                      'role', 'cost_initial', 'cost_per_hour'));
      return Response::json($resource);
    }
    return false;
}

  // Permet de récupérer les informations d'une ressource selon son id, retournées en JSON.
  public function show($id)
  {
    $resource = Resource::findOrFail($id);
    $project = Project::findOrFail($resource->project_id);
    if($project->see_resources()){
        return Response::json($resource);
    }
    return false;
  }

  // Permet de supprimer une ressource.
  public function destroy($id)
  {
    $resource = Resource::findOrFail($id);
    $project = Project::findOrFail($resource->project_id);
    if($project->modify_resources()){
      $resource = Resource::destroy($id);

      return Response::json($resource);
    }
    return false;
  }
}
