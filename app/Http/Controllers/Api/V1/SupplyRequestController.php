<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
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
    return SupplyRequest::with('employee')->orderBy('created_at', 'DESC')->get();
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
    $role = \App\Role::where('name', 'almacenes')->first();
    if (!isset($role->id)) {
      $roleid = 0;
      array_push($errors, 'No se econtró el rol ALMACENES.');
    } else {
      $roleid = $role->id;
    }

    $user = \App\User::whereHas('roles', function ($query) use ($roleid) {
      $query->where('role_id', $roleid);
    })->first();
    if (!isset($user->id)) {
      array_push($errors, 'No se encontró encargado de almacenes.');
    }

    $identity_card = \App\Employee::find($request->employee['id'])->identity_card ?? 'NO CI';
    $user = \App\SupplyUser::where('ci', $identity_card)->first();
    if (!isset($user->id)) {
      array_push($errors, 'El usuario no se encuentra habilitado para hacer pedidos.');
    }
    if (sizeof($errors) > 0) {
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
    foreach ($request->supplyRequest as $article) {
      $supply_request->subarticles()->attach([
        $article['id'] => ['amount' => $article['amount'], 'amount_delivered' => 0, 'total_delivered' => 0]
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
    foreach ($request->subarticles as $subarticle) {
      $subarticle->stock();
    }
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
    $supply_request = SupplyRequest::find($id);
    foreach ($request->subarticles as $subarticle) {
      $supply_request->subarticles()->updateExistingPivot($subarticle['id'], $subarticle['pivot']);
    }
    $supply_request->fill($request->all());
    $supply_request->delivery_date = Carbon::now()->toDateTimeString();
    $supply_request->save();
    $supply_request->subarticles;
    return $supply_request;
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
  public function print(Request $request, $id)
  {
    $supply_request = SupplyRequest::find($id);

    $data = [
      'supply_request' => $supply_request,
      'type' => $request->query()['type']
    ];
    $filename = $request->query()['type'] == 'delivery' ? 'entrega_almacen_' : 'solicitud_almacen_' . $supply_request->nro_solicitud . '.pdf';
    return \PDF::loadView('supply.print', $data)
      ->setOption('page-width', '216')
      ->setOption('page-height', '356')
      ->setOption('margin-top', '4')
      ->setOption('margin-right', '5')
      ->setOption('margin-bottom', '0')
      ->setOption('margin-left', '10')
      ->setOption('encoding', 'utf-8')
      ->stream($filename);
  }
}
