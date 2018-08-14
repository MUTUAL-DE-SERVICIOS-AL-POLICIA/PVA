<?php

namespace App\Http\Controllers\Api\V1;

use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentForm;
use Illuminate\Http\Request;

/** @resource Document
 *
 * Resource to retrieve, store and update documents data
 */
class DocumentController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return Document::get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(DocumentForm $request) {
		$document = Document::create($request->all());
		return $document;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return Document::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(DocumentForm $request, $id) {
		$document = Document::findOrFail($id);
		$document->fill($request->all());
		$document->save();
		return $document;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$document = Document::findOrFail($id);
		$document->delete();
		return $document;
	}
}
