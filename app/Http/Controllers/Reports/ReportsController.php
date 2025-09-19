<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ControllingAccount;
use App\Models\Accounts\ParentAccount;
use DB;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpParser\Node\Expr\Cast\Object_;

class ReportsController extends Controller
{
    public function index(): View
    {
        $accounts = ControllingAccount::select('id', 'title')->get();

        return view('reports.ledger-report', compact('accounts'));
    }

    public function ledgerReport(Request $request): Object
    {
        $validated = $request->validate([
            'account' => 'required',
//            'from_date' => 'required|date',
//            'to_date' => 'required|date|after_or_equal:from_date'
        ]);
//        $fromDate = date('Y-m-d', strtotime($request->input('from_date')));
//        dd($request->input('from_date'), 'Converted '.$fromDate);

        $closingBal = DB::table('transaction_details as trn_dt')
            ->join('transactions as trn', 'trn_dt.transaction_id', '=', 'trn.id')
            ->selectRaw('sum(trn_dt.dr_amount) - sum(trn_dt.cr_amount) as openingBal')
            ->where('trn_dt.controlling_account_id', $request->input('account'))
            ->where('trn.trn_date', '<', $request->input('from_date'))
            ->get()->pluck('openingBal');

        $openingBal = [
            'from_date' => date('d-M-Y', strtotime($request->input('from_date'))),
            'to_date' => date('d-M-Y', strtotime($request->input('to_date'))),
            'ob' => is_null($closingBal[0]) ? 0.00 : $closingBal[0]
        ];

        $trns = DB::table('transaction_details as trn_dt')
            ->join('controlling_accounts as acc', 'trn_dt.controlling_account_id', '=', 'acc.id')
            ->join('transactions as trn', 'trn_dt.transaction_id', '=', 'trn.id')
            ->select('trn.id', 'trn.trn_date', 'trn.status', 'acc.title', 'acc.code',
                'trn_dt.particular', 'trn_dt.dr_amount', 'trn_dt.cr_amount')
            ->where('trn_dt.controlling_account_id', $request->input('account'))
            ->whereBetween('trn.trn_date', [$request->input('from_date'), $request->input('to_date')])
            ->orderBy('trn.trn_date', 'asc')
            ->get();

        $accounts = ControllingAccount::select('id', 'title')->get();

        //Return Response if request from trial Balance
        if($request->has('request_from') == 'trialBalance')
        {
            return response()->json(['openingBal' => $openingBal, 'trns' => $trns,]);
        }

        return view('reports.ledger-report',
            ['openingBal' => $openingBal,
                'trns' => $trns,
                'accounts' => $accounts
            ]
        );


    }

//    public function printVoucher(): View
//    {
//        return view('reports.print-voucher');
//    }

    public function getVoucherTrns(Request $request): View
    {
        $request->validate([
            'trn_id' => 'required|numeric'
        ]);

        $trnsDetails = DB::table('transactions as trn')
            ->join('transaction_details as trn_dt', 'trn_dt.transaction_id', '=', 'trn.id')
            ->join('controlling_accounts as acc', 'acc.id', '=', 'trn_dt.controlling_account_id')
            ->select(['trn.id', 'trn.trn_date', 'trn.trn_type', 'trn.payee', 'trn.messer', 'trn.description', 'trn.status',
                'trn_dt.particular', 'trn_dt.dr_amount', 'trn_dt.cr_amount',
                'acc.code', 'acc.title as account'])
            ->where('trn.id', $request->input('trn_id'))
            ->orderBy('trn_dt.dr_amount', 'DESC')
            ->get();
        if($trnsDetails->isEmpty())
        {
            abort('404');
        }
        else
        {
            return view('reports.print-voucher', compact('trnsDetails'));
        }
    }

    public function trialBalanceReport(): View
    {
        //Query for Balance Sheet
//        $accounts = AccountGroup::all();
//        foreach ($accounts as $index => $group)
//        {
//            $group->parentAccount  = ParentAccount::where('account_group', $accounts[$index]->id)
//                ->select(['id', 'title'])
//                ->get();
//
//            foreach ($group->parentAccount as $parentAccounts)
//            {
//                $parentAccounts->account = \DB::table('transaction_details as trns')
//                    ->join('controlling_accounts as cntrl', 'trns.controlling_account_id', '=', 'cntrl.id')
//                    ->where('cntrl.parent_account_id', '=', $parentAccounts->id)
//                    ->select(\DB::raw('cntrl.id, MAX(title) as title, SUM(dr_amount) as dr_amount, SUM(cr_amount) as cr_amount'))
//                    ->groupBy('cntrl.id')
//                    ->get();
//            }
//        }
//        echo "<pre>";
//        print_r($accounts->toArray());

        $accounts = DB::table('transaction_details as trns')
            ->select(DB::raw('cntrl.id, cntrl.code, cntrl.title,
                SUM(dr_amount) as dr_amount,
                SUM(cr_amount) as cr_amount,
                SUM(dr_amount) - SUM(cr_amount) as balance'))
            ->join('controlling_accounts as cntrl', 'trns.controlling_account_id', '=', 'cntrl.id')
            ->join('parent_accounts as prnt', 'prnt.id', '=', 'cntrl.parent_account_id')
            ->groupBy('cntrl.id')
            ->orderBy('prnt.account_group')
            ->orderBy('cntrl.code')
            ->get();

//        echo "<pre>";
//        print_r($accounts);

        return view('reports.trial-balance-report', compact('accounts'));
    }

    public function viewIncomeStatementReport(): View
    {
        return view('reports.income-statement');
    }

    public function incomeStatementReport(Request $request): View
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ]);

        //Query for Income Statement
        $accounts = ParentAccount::whereIn('account_group', [40, 50])
            ->select(['id', 'account_group', 'title'])
            ->get();

        foreach ($accounts as $parentAccounts) {
            $parentAccounts->account = DB::table('transaction_details as trn_dt')
                ->join('transactions as trns', 'trns.id', '=', 'trn_dt.transaction_id')
                ->join('controlling_accounts as cntrl', 'trn_dt.controlling_account_id', '=', 'cntrl.id')
                ->where('cntrl.parent_account_id', '=', $parentAccounts->id)
                ->whereBetween('trns.trn_date', [$request->input('from_date'), $request->input('to_date')])
                ->select(DB::raw('cntrl.id, title,
                            SUM(trn_dt.dr_amount) - SUM(trn_dt.cr_amount) as balance'))
                ->groupBy('cntrl.id')
                ->orderBy('title')
                ->get();
        }

        $sales = $accounts->where('account_group', 40)->sum(function ($accounts) {
            return $accounts->account->sum('balance');
        });

        $expenses = $accounts->where('account_group', 50)->sum(function ($accounts) {
            return $accounts->account->sum('balance');
        });

        $totalAndPeriod = [
            'from_date' => date('d-M-Y', strtotime($request->input('from_date'))),
            'to_date' => date('d-M-Y', strtotime($request->input('to_date'))),
            'sales' => $sales,
            'expenses' => $expenses,
        ];

        return view('reports.income-statement', compact('accounts', 'totalAndPeriod'));
    }

    public function viewBalanceSheetReport(): View
    {
        return view('reports.balance-sheet');
    }

    public function balanceSheetReport(Request $request)
    {
        $request->validate([
            //'from_date' => 'required|date',
            'to_date' => 'required|date'
        ]);

        //Query for Balance Sheet
        $accounts = ParentAccount::whereIn('account_group', [10, 20, 30, 40, 50])
            ->select(['id', 'account_group', 'title'])
            ->get();

        foreach ($accounts as $parentAccounts) {
            $parentAccounts->account = DB::table('transaction_details as trn_dt')
                ->join('transactions as trns', 'trns.id', '=', 'trn_dt.transaction_id')
                ->join('controlling_accounts as cntrl', 'trn_dt.controlling_account_id', '=', 'cntrl.id')
                ->where('cntrl.parent_account_id', '=', $parentAccounts->id)
                ->where('trns.trn_date', '<=', $request->input('to_date'))
                ->select(DB::raw('cntrl.id, title,
                            SUM(trn_dt.dr_amount) - SUM(trn_dt.cr_amount) as balance'))
                ->groupBy('cntrl.id')
                ->get();
        }

        $assets = $accounts->where('account_group', 10)->sum(function ($accounts) {
            return $accounts->account->sum('balance');
        });

        $liabilities = $accounts->where('account_group', 20)->sum(function ($accounts) {
            return $accounts->account->sum('balance');
        });

        $equities = $accounts->where('account_group', 30)->sum(function ($accounts) {
            return $accounts->account->sum('balance');
        });

        $income = $accounts->where('account_group', 40)->sum(function ($accounts) {
            return $accounts->account->sum('balance');
        });

        $expense = $accounts->where('account_group', 50)->sum(function ($accounts) {
            return $accounts->account->sum('balance');
        });

        $totalAndPeriod = [
//            'from_date' => date('d-M-Y', strtotime($request->input('from_date'))),
            'to_date' => date('d-M-Y', strtotime($request->input('to_date'))),
            'totalAssets' => $assets,
            'liabilities' => $liabilities,
            'ownerEquity' => $equities,
            'profitLoss' => abs($income + $expense),
            'totalOwnerEquity' => $equities + ($income + $expense),
            'totalEquities' => $liabilities + $equities + ($income + $expense),
        ];

        return view('reports.balance-sheet', compact('accounts', 'totalAndPeriod'));
    }
}
