<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cost;
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
        $cost = new Cost();

        $cost->name = $request->name;
        $cost->description = $request->description;
        $cost->value = $request->value;
        $cost->project_id = $request->project_id;

        $cost->save();

        return Response::json($cost);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cost = Cost::find($id);

        return Response::json($cost);
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
        $cost = Cost::find($id);
        $cost->name = $request->name;
        $cost->description = $request->description;
        $cost->value = $request->value;

        $cost->save();

        return Response::json($cost);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cost = Cost::destroy($id);

        return Response::json($cost);
    }
}
