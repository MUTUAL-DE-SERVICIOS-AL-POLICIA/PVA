<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcedureForm;
use App\Procedure;
use Illuminate\Http\Request;

/** @resource Procedure
 *
 * Resource to retrieve, store and update procedures data
 */
class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Procedure::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProcedureForm $request)
    {
        $procedure = Procedure::create($request->all());
        return $procedure;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Procedure::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProcedureForm $request, $id)
    {
        $procedure = Procedure::findOrFail($id);
        $procedure->fill($request->all());
        $procedure->save();
        return $procedure;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $procedure = Procedure::findOrFail($id);
        $procedure->delete();
        return $procedure;
    }
}
