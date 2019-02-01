<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SupplyRequest;

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
    //VALIDATION
    $errors = [];
    $role = \App\Role::where('name','almacenes')->first();
    if(!isset($role->id)) {
      $roleid = 0;
      array_push($errors,'No se econtró el rol ALMACENES.');
    } else {
      $roleid = $role->id;
    }

		$user = \App\User::whereHas('roles', function($query) use ($roleid) {
			$query->where('role_id',$roleid);
    })->first();
    if(!isset($user->id)) {
      array_push($errors,'No se encontró encargado de almacenes.');
    }

    $identity_card  = \App\Employee::find($request->employee['id'])->identity_card ?? 'NO CI';
    $user = \App\SupplyUser::where('ci',$identity_card)->first();
    if(!isset($user->id)) {
      array_push($errors,'El usuario no se encuentra habilitado para hacer pedidos.');
    }
    if(sizeof($errors) > 0)
    {
      return response()->json([
        'message' => 'bad database formed',
        'errors' => [
            'type' => $errors,
        ]
      ], 409);
    }
    //END VALIDATION
    $supply_request = new SupplyRequest($request->employee['id']);
    $supply_request->save();
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

  /**
   * Print a supplyrequest from.
   *
   * @param  \App\SupplyRequest  $supplyRequest
   * @return \View
   */
  public function print($id)
  {
    $supply_request = SupplyRequest::find($id);

    $data = [
      'supply_request' => $supply_request
    ];
    $filename = 'solicitudalmacen'.$supply_request->nro_solicitud.'.pdf';
    return \PDF::loadView('supply.print', $data)
      ->setOption('page-width', '216')
      ->setOption('page-height', '279')
      ->setOption('margin-top', '5')
      ->setOption('margin-right', '5')
      ->setOption('margin-bottom', '5')
      ->setOption('margin-left', '10')
      ->setOption('encoding', 'utf-8')
      ->stream($filename);
  }
}
