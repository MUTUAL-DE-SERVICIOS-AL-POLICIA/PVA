<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CasCertification;

class CasCertificationController extends Controller
{
  /**
	 * Display a listing of the Cas certification.
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
      return CasCertification::get();
    }

    /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
    public function store(Request $request)
    {
      $request['active'] = true;
      $cas = CasCertification::create($request->all());
      return $cas;
    }

    /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
    public function show($id)
    {
      return CasCertification::findOrFail($id);
    }

    /**
   * Update a resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
    public function update(Request $request, $id)
    {
      $cas = CasCertification::findOrFail($id);
      $cas->fill($request->all());
      $cas->save();
      return $cas;
    }

    public function destroy($id)
    {
      $cas = CasCertification::findOrFail($id);
      $cas->delete();
      return $cas;
    }

    public function getCasEmployee($id)
    {
      return CasCertification::where('employee_id',$id)->orderBy('created_at','desc')->get();
    }
}
