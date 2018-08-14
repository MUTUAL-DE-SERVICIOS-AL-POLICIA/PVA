<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobScheduleForm;
use App\JobSchedule;
use Illuminate\Http\Request;

/** @resource JobSchedule
 *
 * Resource to retrieve, store and update job schedules data
 */
class JobScheduleController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return JobSchedule::get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(JobScheduleForm $request) {
		$job_shedule = JobSchedule::create($request->all());
		return $job_shedule;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return JobSchedule::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(JobScheduleForm $request, $id) {
		$job_shedule = JobSchedule::findOrFail($id);
		$job_shedule->fill($request->all());
		$job_shedule->save();
		return $job_shedule;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$job_shedule = JobSchedule::findOrFail($id);
		$job_shedule->delete();
		return $job_shedule;
	}
}
