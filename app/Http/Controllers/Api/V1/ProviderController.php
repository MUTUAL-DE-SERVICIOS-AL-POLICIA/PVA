<?php

namespace App\Http\Controllers\Api\V1;

use App\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProviderController extends Controller
{
    //


  public function show(Request $request, $id)
  {
    $o = Provider::get();
    return  $o;
  }


  public function getOne($id)
  {
    $provider = Provider::query()->whereKey($id)->get();
    //$provider = Provider::get();
    return $provider;
  }
  public function getListAll()
  {
    $provider = Provider::get();
    return $provider;
  }

  //public function store(EmployeeStoreForm $request)
  public function store(Request $request)
  {
    $provider = Provider::create($request->all());

    return $provider;
  }
  public function destroy($id)
  {
    $provider = Provider::findOrFail($id);
    if ($provider->approved === null) $provider->delete();
    return $provider;
  }


  public function update(Request $request, $id)
  {
    $obj = Provider::findOrFail($id);
    $obj->fill($request->all());
    $obj->save();
    return $obj;
  }

}
