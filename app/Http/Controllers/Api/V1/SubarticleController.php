<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Subarticle;

class SubarticleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $subarticles = Subarticle::get();
    foreach ($subarticles as $subarticle) {
      $subarticle->stock();
    }
    return $subarticles;
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Subarticle  $subarticle
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $subarticle = Subarticle::findOrFail($id);
    return $subarticle->stock();
  }
}
