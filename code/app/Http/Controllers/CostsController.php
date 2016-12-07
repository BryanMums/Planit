<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cost;
use App\Project;
use Response;

class CostsController extends Controller
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
        if($project->modify_finance()){
          $cost = $project->costs()->create($request->only('name', 'description', 'value'));
          return Response::json($cost);
        }
        return Reponse::json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cost = Cost::findOrFail($id);
        $project = Project::findOrFail($cost->project_id);
        if($project->see_finance()) return Response::json($cost);
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
        $cost = Cost::findOrFail($id);
        $project = Project::findOrFail($cost->project_id);

        if($project->modify_finance()){
          $cost->update($request->only('name', 'description', 'value'));

          return Response::json($cost);
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
        $cost = Cost::findOrFail($id);
        $project = Project::findOrFail($cost->project_id);
        if($project->modify_resources()){
          $cost = Cost::destroy($id);
          return Response::json($cost);
        }
        return false;
    }
}
