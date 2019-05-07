<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Material;

class MaterialController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return Material::get();
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Material  $material
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $material = Material::findOrFail($id);
    foreach ($material->subarticles as $subarticle) {
      $subarticle->stock();
    }
    return $material;
  }
}
