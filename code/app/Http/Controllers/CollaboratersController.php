<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Collaborater;
use App\Project;
use Response;
use Illuminate\Support\Facades\Auth;

class CollaboratersController extends Controller
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
      if($project->modify_collaboraters()){
        $collaborater = $project->collaboraters()->create($request->only('user_id', 'informations_rights', 'collaboraters_rights',
                                                        'resources_rights', 'gantt_rights', 'budget_rights'));
        return Response::json($collaborater);
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
      $collaborater = Collaborater::findOrFail($id);
      $project = Project::findOrFail($collaborater->project_id);
      if($project->modify_collaboraters()){
        $collaborater->update($request->only('informations_rights', 'collaboraters_rights',
                                            'resources_rights', 'gantt_rights', 'budget_rights'));
        return Response::json($collaborater);
      }
      return false;
    }

    public function show($id)
    {
      $collaborater = Collaborater::findOrFail($id);

      $project = Project::findOrFail($collaborater->project_id);
      if($project->see_collaboraters()) return Response::json($collaborater);
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
      $collaborater = Collaborater::findOrFail($id);
      $project = Project::findOrFail($collaborater->project_id);
      if($project->modify_collaboraters() || $collaborater->user_id == Auth::id()){
        $collaborater = Collaborater::destroy($id);

        return Response::json($collaborater);
      }
      return false;
    }
}
