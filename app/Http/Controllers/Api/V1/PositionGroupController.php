<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PositionGroupForm;
use App\PositionGroup;
use Illuminate\Http\Request;

/** @resource PositionGroup
 *
 * Resource to retrieve, store and update position groups data
 */
class PositionGroupController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return PositionGroup::get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(PositionGroupForm $request) {
		$position_group = PositionGroup::create($request->all());
		return $position_group;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return PositionGroup::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(PositionGroupForm $request, $id) {
		$position_group = PositionGroup::findOrFail($id);
		$position_group->fill($request->all());
		$position_group->save();
		return $position_group;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$position_group = PositionGroup::findOrFail($id);
		$position_group->delete();
		return $position_group;
	}
}
