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
        $contract = AssistantContract::create($request->all());
        return $contract;
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
        return $contract;
    }

    public function delete($id)
    {
        $contract = AssistantContract::findOrFail($id);
        $contract->delete();
        return $contract;
    }
}
