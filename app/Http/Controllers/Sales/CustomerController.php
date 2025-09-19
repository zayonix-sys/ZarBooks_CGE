<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\CustomerStoreRequest;
use App\Http\Requests\Sales\CustomerUpdateRequest;
use App\Models\Accounts\ControllingAccount;
use App\Models\Accounts\ParentAccount;
use App\Models\Sales\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return #View
     */
    public function index(): View
    {
        $customers = Customer::get();
        $parentAccounts = ParentAccount::where('account_group', 10)->get();

        return view('customer.customers-list', compact('customers', 'parentAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerStoreRequest $request)
    {
        $request->validated();
        try {
            DB::transaction(function () use ($request) {

                //Store Customer Controlling Account
                $controllingAcc = ControllingAccount::create([
                    'title' => $request->input('name'),
                    'parent_account_id' => $request->input('parent_account_id')
                ]);

                //Getting Parent Account Code
                $parentAccId = ['parent_account_id' => request('parent_account_id')];
                $parentAccCode = ParentAccount::select('code')->where('id', $parentAccId)->get();

                //Getting Last Row Record
                $accId = $controllingAcc->id;

                //Update Account Code Column
                $controllingAcc->code = $parentAccCode[0]->code.'-'.str_pad($accId, 4, '0', STR_PAD_LEFT) ;
                $controllingAcc->save();

                //Store Customer Info
                $customer = Customer::Create([$request->validated(),
                        'name' => $request->input('name'),
                        'address' => $request->input('address'),
                        'contact' => $request->input('contact'),
                        'email' => $request->input('email'),
                        'cnic' => $request->input('cnic'),
                        'ntn' => $request->input('ntn'),
                        'strn' => $request->input('strn'),
                        'controlling_account_id' => $accId]
                );
            });

            notify()->success('Customer added successfully', 'Customer Created');
            return redirect('/customer');
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sales\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sales\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $customer = Customer::findOrFail($customer->id);

        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sales\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        $cust = $request->validated();
        $customer->fill($cust);
        $customer->save();

        notify()->success('Customer updated successfully', 'Customer Updated');
        return redirect('/customer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sales\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
