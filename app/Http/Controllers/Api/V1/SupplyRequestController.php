<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SupplyRequest;
use Auth;
class SupplyRequestController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  /**
   * Store a newly created resource in request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {			
	  $supply_request = SupplyRequest::create(['employee_id'=>$request->employee['id']]);				
	  $articles = [];
	  foreach($request->supplyRequest as $article) {			
		  $supply_request->subarticles()->attach([
				  $article['id']=> ['amount' => $article['request'] ]
			  ]);				
	  }
	  return $supply_request;    
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\SupplyRequest  $supplyRequest
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $request = SupplyRequest::find($id);
    $request->subarticles;
    return $request;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\SupplyRequest  $supplyRequest
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\SupplyRequest  $supplyRequest
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
