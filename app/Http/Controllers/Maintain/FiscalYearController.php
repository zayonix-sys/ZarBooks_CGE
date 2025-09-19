<?php

namespace App\Http\Controllers\Maintain;

use App\Http\Controllers\Controller;
use App\Models\SysConfig\FiscalYear;
use App\Http\Requests\FiscalYear\StoreFiscalYearRequest;
use App\Http\Requests\FiscalYear\UpdateFiscalYearRequest;
use Illuminate\Support\Facades\Redirect;

class FiscalYearController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::select('id','fy_title', 'fy_start_date', 'fy_end_date', 'is_active')->get();
        //dd($fiscalYears);

        return view('maintain.fiscalYear', compact('fiscalYears'));
    }

    public function store(StoreFiscalYearRequest $request)
    {
        $fiscalYear = FiscalYear::firstOrCreate(
            $request->validated(),
            ['fy_title' => request('fy_title')]
        );
        notify()->success('Fiscal Year has been added successfully', 'Record Added');
        return redirect('fiscalYear');
    }

    public function update(UpdateFiscalYearRequest $request, FiscalYear $fiscalYear)
    {
        $fiscalYearData = $request->validated();
        $fiscalYear->fill($fiscalYearData);
        $fiscalYear->is_active = $request->input('is_active');

        $fiscalYear->save();

        //$fiscalYear = FiscalYear::updateOrCreate($validator, ['fy_title' => request('fy_title')]);

        // $fiscalYear = FiscalYear::findOrFail($id);

        // $fiscalYear->fy_title = $request->input('fy_title');
        // $fiscalYear->fy_start_date = $request->input('fy_start_date');
        // $fiscalYear->fy_end_date = $request->input('fy_end_date');

        return redirect('fiscalYear');
    }
}
