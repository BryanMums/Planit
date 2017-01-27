<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cost;
use App\Project;
use Response;

class CostsController extends Controller
{


    // Permet de stocker des coûts.
    public function store(Request $request)
    {
        $project = Project::findOrFail($request->project_id);
        if($project->modify_finance()){
          $cost = $project->costs()->create($request->only('name', 'description', 'value'));
          return Response::json($cost);
        }
        return Reponse::json();
    }

    // Permet de récupérer les informations d'un coût, retournées en JSON.
    public function show($id)
    {
        $cost = Cost::findOrFail($id);
        $project = Project::findOrFail($cost->project_id);
        if($project->see_finance()) return Response::json($cost);
        return false;
    }


    // Permet de mettre à jour un coût.
    public function update(Request $request, $id)
    {
        $cost = Cost::findOrFail($id);
        $project = Project::findOrFail($cost->project_id);

        if($project->modify_finance()){
          $cost->update($request->only('name', 'description', 'value'));

          return Response::json($cost);
        }
        return false;
    }

    // Permet de supprimer un coût.
    public function destroy($id)
    {
        $cost = Cost::findOrFail($id);
        $project = Project::findOrFail($cost->project_id);
        if($project->modify_finance()){
          $cost = Cost::destroy($id);
          return Response::json($cost);
        }
        return false;
    }
}
