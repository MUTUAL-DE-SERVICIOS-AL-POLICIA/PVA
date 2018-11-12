<?php

namespace App\Http\Controllers\Api\V1;

use App\Certificate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** @resource Certificate
 *
 * Resource to retrieve, store and update Certificates data
 */
class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Certificate::with('document_type')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $correlative = 1;
        $year = date('Y');
        $certificate = Certificate::where('document_type_id', $request->document_type_id)->orderBy('correlative', 'desc')->first();
        if ($certificate) {
          $correlative = $certificate->correlative + 1;
          if ($certificate->year != $year) {
            $correlative = 1;
          }
        }
        $certificate = new Certificate;
        $certificate->document_type_id = $request->document_type_id;
        $certificate->correlative = $correlative;
        $certificate->year = $year;
        $certificate->data = json_encode($request->data);
        $certificate->save();

        return $certificate;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Certificate::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->fill($request->all());
        $certificate->save();
        return $certificate;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();
        return $certificate;
    }
}
