<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
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
    $resource = new Resource();

    $resource->firstname = $request->firstname;
    $resource->lastname = $request->lastname;
    $resource->email = $request->email;
    $resource->role = $request->role;
    $resource->cost_initial = $request->cost_initial;
    $resource->cost_per_hour = $request->cost_per_hour;
    $resource->project_id = $request->project_id;

    $resource->save();

    return Response::json($resource);
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
    $resource = Resource::find($id);

    $resource->firstname = $request->firstname;
    $resource->lastname = $request->lastname;
    $resource->email = $request->email;
    $resource->role = $request->role;
    $resource->cost_initial = $request->cost_initial;
    $resource->cost_per_hour = $request->cost_per_hour;

    $resource->save();

    return Response::json($resource);
  }


  public function show($id)
  {
    $resource = Resource::find($id);

    return Response::json($resource);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $resource = Resource::destroy($id);

    return Response::json($resource);
  }
}
