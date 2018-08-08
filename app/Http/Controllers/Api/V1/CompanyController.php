<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\CompanyEditForm;
use App\Http\Requests\CompanyStoreForm;
use Illuminate\Http\Request;

/** @resource Company
 *
 * Resource to retrieve, store and edit Company data
 */

class CompanyController extends Controller {
	public $guarded = ['id'];
	protected $timestamps = true;
	protected $fillable = ['name', 'shortened', 'tax_number'];

	/**
	 * Display Company's data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return response()->json(Company::first());
	}

	/**
	 * Store a newly Company if no one found in the database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CompanyStoreForm $request) {
		if (Company::count() == 0) {
			return response()->json(Company::create($request));
		} else {
			return response()->json([
				'message' => 'Ya existe una Compañia, para cambiar los datos debe editarla',
				'errors' => [
					'type' => ['Solo puede existir una Compañia'],
				],
			], 400);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return response()->json(Company::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(CompanyEditForm $request, $id) {
		$company = Company::find($id);
		if ($company) {
			$company->fill($request->all());
			$company->save();
			return response()->json($company);
		} else {
			return response()->json([
				'message' => 'No existe la Compañia buscada',
				'errors' => [
					'type' => ['ID inexistente'],
				],
			], 400);
		}
	}
}
