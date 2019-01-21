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
    $filename = 'supply.pdf';

    $data = [
      'supplies' => $request->all()
    ];

    return \PDF::loadView('supply.print', $data)
      ->setOption('page-width', '216')
      ->setOption('page-height', '279')
      ->setOption('margin-top', '0')
      ->setOption('margin-right', '5')
      ->setOption('margin-bottom', '0')
      ->setOption('margin-left', '10')
      ->setOption('encoding', 'utf-8')
      ->stream($filename);
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
