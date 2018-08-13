<?php

namespace App\Http\Controllers;

use App\ManagementEntity;

/** @resource ManagementEntity
 *
 * Resource to retrieve and show ManagementEntity data
 */

class ManagementEntityController extends Controller {
	/**
	 * Display a listing of the management entities.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return ManagementEntity::get();
	}

	/**
	 * Display the specified management entity.
	 *
	 * @param  \App\ManagementEntity  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return ManagementEntity::findOrFail($id);
	}
}
