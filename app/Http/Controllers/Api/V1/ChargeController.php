<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Http\Requests\ChargeForm;
use Illuminate\Http\Request;

/** @resource Charge
 *
 * Resource to retrieve, store and update charge data
 */
class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Charge::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChargeForm $request)
    {
        $charge = Charge::create($request->all());
        return $charge;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Charge::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChargeForm $request, $id)
    {
        $charge = Charge::findOrFail($id);
        $charge->fill($request->all());
        $charge->save();
        return $charge;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $charge = Charge::findOrFail($id);
        $charge->delete();
        return $charge;
    }
}
