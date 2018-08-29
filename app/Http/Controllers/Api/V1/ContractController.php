<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use App\EmployerNumber;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** @resource Contract
 *
 * Resource to retrieve, store and update contracts data
 */
class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Contract::with('job_schedules', 'employee', 'insurance_company', 'employee.city_identity_card', 'position', 'position.charge', 'position.position_group', 'contract_type', 'contract_mode', 'retirement_reason')
            ->orderBy('start_date', 'DESC')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contract = Contract::create($request->all());
        $contract->job_schedules()->attach($request->schedule['id']);
        if ($request->schedule['id'] == 1) {
            $contract->job_schedules()->attach(2);
        }
        return $contract;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Contract::findOrFail($id);
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
        $contract = Contract::findOrFail($id);
        $contract->fill($request->all());
        $contract->save();
        if ($request->schedule['id']) {
            $contract->job_schedules()->detach();
            $contract->job_schedules()->attach($request->schedule['id']);
            if ($request->schedule['id'] == 1) {
                $contract->job_schedules()->attach(2);
            }
        }
        return $contract;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();
        return $contract;
    }

    /**
     * PDF the specified resource from storage.
     *
     * @param  int  $id
     * @return pdf
     */
    function print($id, $type) {
        $headerHtml  = view()->make('partials.head')->render();
        $pageWidth   = '216';
        $pageHeight  = '330';
        $pageMargins = [30, 25, 40, 30];
        $pageName    = 'contrato.pdf';
        $data        = [
            'contract'        => Contract::findOrFail($id),
            'mae'             => Contract::where([['position_id', '1'], ['active', 'true']])->first(),
            'employer_number' => EmployerNumber::where('insurance_company_id', '1')->first(),
        ];
        if ($type != 'printEventual') {
            $headerHtml  = '';
            $pageWidth   = '202';
            $pageHeight  = '130';
            $pageMargins = [10, 11, 12, 11];
            $pageName    = 'seguro.pdf';
        }
        return \PDF::loadView('contract.' . $type, $data)
            ->setOption('header-html', $headerHtml)
            ->setOption('page-width', $pageWidth)
            ->setOption('page-height', $pageHeight)
            ->setOption('margin-top', $pageMargins[0])
            ->setOption('margin-right', $pageMargins[1])
            ->setOption('margin-bottom', $pageMargins[2])
            ->setOption('margin-left', $pageMargins[3])
            ->setOption('encoding', 'utf-8')
            ->stream($pageName);
    }
}
