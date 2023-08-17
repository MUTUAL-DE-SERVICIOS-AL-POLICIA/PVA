<?php

namespace App\Http\Controllers\Api\V1;

use App\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VacationQueue;
use Carbon\Carbon;

class VacationQueueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return VacationQueue::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vacation_queue = VacationQueue::create($request->all());
        return $vacation_queue;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return VacationQueue::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vacation_queue = VacationQueue::findOrFail($id);
        $vacation_queue->fill($request->all());
        $vacation_queue->save();
        return $vacation_queue;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vacation_queue = VacationQueue::findOrFail($id);
        $vacation_queue->delete();
        return $vacation_queue;
    }


    public function count_days($employee_id, $date)
    {
        $count = 0;
        $count = Employee::findOrFail($employee_id)
            ->vacation_queues()
            ->where('max_date', '>=', $date)
            ->sum('rest_days');

        return response()->json(['count' => max($count, 0)]);
    }
}
