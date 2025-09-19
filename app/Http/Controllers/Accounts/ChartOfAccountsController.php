<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\StoreControllingAccountRequest;
use App\Http\Requests\Accounts\StoreParentAccRequest;
use App\Models\Accounts\ControllingAccount;
use App\Models\Accounts\ParentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsController extends Controller
{
    public function index()
    {
        //$assetsAccounts = ParentAccount::select('id', 'title')->where([['account_group',10], ['status',true]])->get();
        $assetsAccounts = ParentAccount::select(['id', 'title'])
            ->whereAccountGroup(10)
            ->whereStatus(1)
            ->get();

        //Eloquent Method
        $controllingAccounts = ControllingAccount::query()
            ->with(['parent_accounts' => function ($query) {
                $query->select('id', 'account_group', 'title');
            }])
            ->get();

        //$controllingAccounts = ControllingAccount::with('parent_accounts')->get();


        //Query Builder Method
//        $controllingAccounts = DB::table('parent_accounts as pa')
//            ->select('pa.account_group','pa.title as Ptitle',
//                'ca.code', 'ca.title', 'ca.status')
//            ->join('controlling_accounts as ca','pa.id','=','ca.parent_account_id')
//            ->get();

        return view('accounts.chartOfAccounts',
            array('assetsAccounts' => $assetsAccounts,
                'controllingAccounts' => $controllingAccounts,
                'parentAccounts' => ParentAccount::get())
        );
    }

    public function addParentAccount(StoreParentAccRequest $request)
    {
        $parentAcc = ParentAccount::firstOrCreate(
            $request->validated(),
            ['title' => request('title')]
        );

        //Getting Last Row Record
        $accId = $parentAcc->id;
        $accGroup = $parentAcc->account_group;

        //Update Account Code Column
        $parentAcc->code = $accGroup.'-'.str_pad($accId, 3, '0', STR_PAD_LEFT) ;
        $parentAcc->save();

        notify()->success('Parent Account added successfully', 'Account Created');
        return redirect('/accounts/chartOfAccounts');
    }

    public function getParentAccounts(int $accountGroup)
    {
        //dd($accountGroup);
        $accounts = ParentAccount::select('id', 'title')->where('account_group', $accountGroup)->get();

        return response()->json($accounts);
    }

    public function getControllingAccount(string $accCode)
    {
        $accounts = ControllingAccount::select('id', 'parent_account_id', 'title')->where('code', $accCode)->get();

        return response()->json($accounts);
    }

    public function updateAccount(int $id, Request $request)
    {
        $account = ControllingAccount::findOrFail($id);
        $request->validate([
           'title' => 'required',
           'parent_account' => 'required'
        ]);

        $account->update([
            $account->title = $request->input('title'),
            $account->parent_account_id = $request->input('parent_account'),
            $account->status = $request->input('is_active')
        ]);

        notify()->success('Account update successfully', 'Account Updated');
        return redirect('/accounts/chartOfAccounts');
    }

    public function store(StoreControllingAccountRequest $request)
    {
        $controllingAcc = ControllingAccount::firstOrCreate(
            $request->validated(),
            ['title' => request('title')]
        );

        //Getting Parent Account Code
        $parentAccId = ['parent_account_id' => request('parent_account_id')];
        $parentAccCode = ParentAccount::select('code')->where('id', $parentAccId)->get();

        //Getting Last Row Record
        $accId = $controllingAcc->id;

        //Update Account Code Column
        $controllingAcc->code = $parentAccCode[0]->code.'-'.str_pad($accId, 4, '0', STR_PAD_LEFT) ;
        $controllingAcc->save();

        notify()->success('Controlling Account added successfully', 'Account Created');
        return redirect('/accounts/chartOfAccounts');
    }

    public function show()
    {

    }
}
