<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use App\Project;
use Response;

class ResourcesController extends Controller
{
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
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


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
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


  public function show($id)
  {
    $resource = Resource::findOrFail($id);
    $project = Project::findOrFail($resource->project_id);
    if($project->see_resources()){
        return Response::json($resource);
    }
    return false;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
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
