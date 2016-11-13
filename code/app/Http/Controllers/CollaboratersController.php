<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Collaborater;
use Response;

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
      $collaborater = new Collaborater();

      $collaborater->user_id = $request->id_user;
      $collaborater->project_id = $request->project_id;
      $collaborater->informations_rights = $request->inforadio;
      $collaborater->gantt_rights = $request->ganttradio;
      $collaborater->budget_rights = $request->budgetradio;

      $collaborater->save();

      return Response::json($collaborater);
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
      $collaborater = Collaborater::find($id);

      $collaborater->informations_rights = $request->informations_rights;
      $collaborater->gantt_rights = $request->gantt_rights;
      $collaborater->budget_rights = $request->budget_rights;

      $collaborater->save();

      return Response::json($collaborater);
    }

    public function get($id)
    {
      $collaborater = Collaborater::find($id);

      return Response::json($collaborater);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $collaborater = Collaborater::destroy($id);

      return Response::json($collaborater);
    }
}
