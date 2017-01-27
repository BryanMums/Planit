<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Collaborater;
use App\Project;
use Response;
use Illuminate\Support\Facades\Auth;

class CollaboratersController extends Controller
{


    // Permet de stocker les collaborateurs.
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


    // Permet de mettre à jour le collaborateur.
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

    // Permet de récupérer les informations d'un collaborateur selon son id, retournées en JSON.
    public function show($id)
    {
      $collaborater = Collaborater::findOrFail($id);

      $project = Project::findOrFail($collaborater->project_id);
      if($project->see_collaboraters()) return Response::json($collaborater);
      return false;
    }

    // Permet de supprimer un collaborateur selon son id.
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
