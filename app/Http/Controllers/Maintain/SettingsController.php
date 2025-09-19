<?php

namespace App\Http\Controllers\Maintain;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintain\StoreBankPaymentAccountRequest;
use App\Http\Requests\Maintain\StoreCashPaymentAccountRequest;
use App\Models\Accounts\ParentAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $parentAccounts = ParentAccount::select(['id', 'title'])
            ->whereAccountGroup(10)
            ->whereStatus(1)
            ->get();
        return view('maintain.settings', compact('parentAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     *
     * Store Cash Payment Accounts for Debit Vouchers
     */

    public function storeCashPaymentAccount(StoreCashPaymentAccountRequest $request)
    {
        $ids = $request->input('cash_payment');
        ParentAccount::whereIn('id', $ids)->update(['is_cash_book' => true]);

        notify()->success('Payment and Receiving Account added successfully', 'Account Attached');
        return redirect()->route('settings');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
