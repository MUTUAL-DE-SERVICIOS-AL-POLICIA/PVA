<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
   * Print a supply request.
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    return response(null, 200);
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
