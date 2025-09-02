<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AssistantContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AssistantContractController extends Controller
{
    public function index()
    {
        return AssistantContract::with('job_schedules', 'employee', 'employee.city_identity_card', 'retirement_reason', 'position_group')
            ->orderBy('end_date', 'ASC')
            ->get();
    }

    public function store(Request $request)
{
    try {
        DB::beginTransaction();
        $validated = $request->validate([
            'schedules.id' => 'nullable|integer|min:0',
        ]);

        $scheduleId = (int) data_get($validated, 'schedules.id', 0);
        $data = $request->except('schedules', 'id');
        $contract = AssistantContract::create($data);
        $toAttach = ($scheduleId === 0) ? [1, 2] : [$scheduleId];
        if (!empty($toAttach)) {
            $contract->job_schedules()->syncWithoutDetaching(array_unique($toAttach));
        }
        DB::commit();
        return response()->json($contract->load('job_schedules'), 201);
    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'error' => 'Error al crear el contrato: '.$e->getMessage(),
        ], 500);
    }
}


    public function show($id)
    {
        return AssistantContract::with('job_schedules', 'employee', 'employee.city_identity_card', 'retirement_reason', 'position_group')
            ->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $contract = AssistantContract::findOrFail($id);
        $contract->fill($request->all());
        $contract->save();
        if ($request->schedules) {
            $contract->job_schedules()->detach();
            foreach ($request->schedules as $schedule) {
                $contract->job_schedules()->attach($schedule);
            }return "lasjkdh";
        }
        return $contract;
    }
}
