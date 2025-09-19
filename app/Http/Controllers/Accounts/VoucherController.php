<?php

namespace App\Http\Controllers\Accounts;

use App\Enum\VoucherStatus;
use App\Enum\VoucherType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\StoreCrVoucherRequest;
use App\Http\Requests\Accounts\storeDrVoucherRequest;
use App\Http\Requests\Accounts\StoreJVoucherRequest;
use App\Models\Accounts\ControllingAccount;
use App\Models\Accounts\ParentAccount;
use App\Models\Accounts\Transaction;
use App\Models\Accounts\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class VoucherController extends Controller
{

    //Display All Transactions
    public function index(): object
    {
//        $trns = Transaction::select(['id', 'trn_date', 'trn_type', 'payee', 'messer', 'description', 'trn_amount', 'status'])
//                ->get();

            $trns = Transaction::orderBy('trn_date', 'desc')->orderBy('id', 'desc')
                ->select(['id', 'trn_date', 'trn_type', 'payee', 'messer', 'description', 'trn_amount', 'status'])
                ->get();

        return view('accounts.dashboard', compact('trns'));
    }

    //Create Voucher for Transactions
    public function createVoucher(string $vch)
    {
        $parentAccounts = ParentAccount::select(['id', 'title'])
            ->whereIsCashBook(true)->get();

        //$cashBookAccounts = ParentAccount::select('id')->whereIsCashBook(true)->get();
        $controllingAccounts = ControllingAccount::select(['id', 'title'])
            ->whereStatus(1)
            ->get();

        if($vch == 'debit')
        {
            return view('accounts.debitVoucher',
                array('parentAccounts' => $parentAccounts,
                    'controllingAccounts' => $controllingAccounts)
            );
        }
        elseif ($vch == 'credit')
        {
            return view('accounts.creditVoucher',
                array('parentAccounts' => $parentAccounts,
                    'controllingAccounts' => $controllingAccounts)
            );
        }
        elseif ($vch == 'journal')
        {
            return view('accounts.journalVoucher',
                compact('controllingAccounts'));
        }
        else
        {
            return abort(404);
        }
    }

    //Display Controlling Accounts
    public function getAccounts(int $parentAccount)
    {
        $accounts = ControllingAccount::select('id', 'title')->where('parent_account_id', $parentAccount)->get();
        return response()->json($accounts);
    }

    //Display Single Transaction
    public function showTrnDetails(int $trnId)
    {
        $trnsDetails = DB::table('transactions as trn')
                    ->join('transaction_details as trn_dt', 'trn_dt.transaction_id', '=', 'trn.id')
                    ->join('controlling_accounts as acc', 'acc.id', '=', 'trn_dt.controlling_account_id')
                    ->select(['trn.id', 'trn.trn_date', 'trn.trn_type', 'trn.payee', 'trn.messer', 'trn.description', 'trn.status',
                        'trn_dt.particular', 'trn_dt.dr_amount', 'trn_dt.cr_amount',
                        'acc.code', 'acc.title as account'])
                    ->where('trn.id', $trnId)
                    ->orderBy('trn_dt.dr_amount','DESC')
                    ->get();
        return $trnsDetails;
    }

    //Store Debit Voucher
    public function storeDrVoucher(storeDrVoucherRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $trn = Transaction::create([
                    $request->validated(),
                    'trn_date' => date('Y-m-d', strtotime($request->input('trn_date'))),
                    'trn_type' => VoucherType::DebitVoucher,
                    'payee' => $request->input('payee'),
                    'messer' => $request->input('messer'),
                    'description' => $request->input('description'),
                    'trn_amount' => $request->input('trn_amount'),
                    'status' => VoucherStatus::Pending,
                    'fiscal_year_id' => session('FiscalYear'),
                    'user_id' => auth()->id(),
                ]);

                //Debit Accounts
                $trn_id = $trn->id;
                $controlling_accounts = $request->input('controlling_account_id');
                $particulars = $request->input('particular');
                $dr_amounts = $request->input('dr_amount');
                $fiscal_year_id = 1;

                for ($trns = 0; $trns < count($controlling_accounts); $trns++) {
                    if ($controlling_accounts[$trns] != '') {
                        TransactionDetail::create([
                            $request->validated(),
                            'transaction_id' => $trn->id,
                            'particular' => $particulars[$trns],
                            'controlling_account_id' => $controlling_accounts[$trns],
                            'dr_amount' => $dr_amounts[$trns],
                            'fiscal_year_id' => $fiscal_year_id
                        ]);
                    }
                }

                //Credit Accounts
                $credit_account = $request->input('credit_account');
                $cr_amount = $request->input('trn_amount');

                TransactionDetail::create([
                    $request->validated(),
                    'transaction_id' => $trn->id,
                    'particular' => $request->input('description'),
                    'controlling_account_id' => $credit_account,
                    'cr_amount' => $cr_amount,
                    'fiscal_year_id' => $fiscal_year_id
                ]);
            });

            notify()->success('Transaction added successfully', 'Voucher Created');
            return redirect('/accounts/voucher/debit');
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function storeCrVoucher(StoreCrVoucherRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $trn = Transaction::create([
                    $request->validated(),
                    'trn_date' => date('Y-m-d', strtotime($request->input('trn_date'))),
                    'trn_type' => VoucherType::CreditVoucher,
                    'payee' => $request->input('payee'),
                    'messer' => $request->input('messer'),
                    'description' => $request->input('description'),
                    'trn_amount' => $request->input('trn_amount'),
                    'status' => VoucherStatus::Pending,
                    'fiscal_year_id' => 1,
                    'user_id' => 1,
                ]);

                //Credit Accounts
                $trn_id = $trn->id;
                $controlling_accounts = $request->input('controlling_account_id');
                $particulars = $request->input('particular');
                $cr_amounts = $request->input('cr_amount');
                $fiscal_year_id = 1;

                for ($trns = 0; $trns < count($controlling_accounts); $trns++) {
                    if ($controlling_accounts[$trns] != '') {
                        TransactionDetail::create([
                            $request->validated(),
                            'transaction_id' => $trn->id,
                            'particular' => $particulars[$trns],
                            'controlling_account_id' => $controlling_accounts[$trns],
                            'cr_amount' => $cr_amounts[$trns],
                            'fiscal_year_id' => $fiscal_year_id
                        ]);
                    }
                }

                //Debit Accounts
                $debit_account = $request->input('debit_account');
                $dr_amount = $request->input('trn_amount');

                TransactionDetail::create([
                    $request->validated(),
                    'transaction_id' => $trn->id,
                    'particular' => $request->input('description'),
                    'controlling_account_id' => $debit_account,
                    'dr_amount' => $dr_amount,
                    'fiscal_year_id' => $fiscal_year_id
                ]);
            });

            notify()->success('Transaction added successfully', 'Voucher Created');
            return redirect('/accounts/voucher/credit');
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function storeJVoucher(StoreJVoucherRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $trn = Transaction::create([
                    $request->validated(),
                    'trn_date' => date('Y-m-d', strtotime($request->input('trn_date'))),
                    'trn_type' => VoucherType::JournalVoucher,
                    'payee' => 'Accountant',
                    'messer' => 'CGE',
                    'description' => $request->input('description'),
                    'trn_amount' => $request->input('dr_total'),
                    'status' => VoucherStatus::Pending,
                    'fiscal_year_id' => 1,
                    'user_id' => 1,
                ]);

                //Debit and Credit Accounts
                $trn_id = $trn->id;
                $controlling_accounts = $request->input('controlling_account_id');
                $particulars = $request->input('particular');
                $dr_amounts = $request->input('dr_amount');
                $cr_amounts = $request->input('cr_amount');
                $fiscal_year_id = 1;

                for ($trns = 0; $trns < count($controlling_accounts); $trns++) {
                    if ($controlling_accounts[$trns] != '') {
                        TransactionDetail::create([
                            $request->validated(),
                            'transaction_id' => $trn->id,
                            'particular' => $particulars[$trns],
                            'controlling_account_id' => $controlling_accounts[$trns],
                            'dr_amount' => is_null($dr_amounts[$trns]) ? 0.00 : $dr_amounts[$trns],
                            'cr_amount' => is_null($cr_amounts[$trns]) ? 0.00 : $cr_amounts[$trns],
                            'fiscal_year_id' => $fiscal_year_id
                        ]);
                    }
                }
            });

            notify()->success('Transaction added successfully', 'Voucher Created');
            return redirect('/accounts/voucher/journal');
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    //Update Single Transaction Status
    public function updateTrnStatus(int $trnId, Request $request)
    {
        $trn = Transaction::findOrFail($trnId);
        $request->status == 1
            ? $trn->update(['status' => VoucherStatus::Approved])
            : $trn->update(['status' => VoucherStatus::Rejected]);

        notify()->success('Status updated successfully', 'Transaction Update');
        return redirect('/accounts/dashboard');
    }

    //Update Bulk Transaction Status
    public function updateBulkTrnStatus(Request $request)
    {
        $request->validate([
            'chk' => 'required|array',
            'chk.*' => 'required',
            'bulk_status' => 'required'
        ]);

        //dd($request->input('chk'));
        $request->bulk_status == 1
            ? Transaction::whereIn('id', $request->input('chk'))->update(['status' => VoucherStatus::Approved])
            : Transaction::whereIn('id', $request->input('chk'))->update(['status' => VoucherStatus::Rejected]);

        notify()->success('Bulk Status updated successfully', 'Bulk Transaction Update');
        return redirect('/accounts/dashboard');
    }

//    public function createDrVoucher(): object
//    {
//        $paymentAccounts = ParentAccount::select(['id', 'title'])
//            ->whereIsCashBook(true)->get();
//
//        //$cashBookAccounts = ParentAccount::select('id')->whereIsCashBook(true)->get();
//        $debitAccounts = ControllingAccount::select(['id', 'title'])
//            ->whereStatus(1)
//            ->get();
//
//        return view('accounts.debitVoucher',
//            array('paymentAccounts' => $paymentAccounts,
//                'debitAccounts' => $debitAccounts)
//        );
//    }

    /**
     * @throws Exception
     */






    public function createJVoucher()
    {
        return view('accounts.journalVoucher');
    }
}
