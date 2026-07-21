<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PackageHelper;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::with('paymentType')->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                    ->orWhere('account_holder_name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('opening_balance', 'like', "%{$search}%")
                    ->orWhere('current_balance', 'like', "%{$search}%")
                    ->orWhere('default_status', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        })->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();
        return view('BackEnd.Accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'account_name'        => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number'      => 'required|string|max:255|unique:accounts,account_number',
            'opening_balance'     => 'required|numeric|min:0',
            'default_status'      => 'required|in:Default,Not Default',
            'status'              => 'required|in:Active,Inactive',
        ]);
        if (!Auth::user()->hasRole('Super-Admin')) {
            $companyPackage = PackageHelper::package();
            if (!$companyPackage) {
                return back()->with('error', 'No active package assigned.');
            }
            $limit = $companyPackage->package->account_limit;
            $current = Account::where('created_by', Auth::id())->count();
            if ($limit != -1 && $current >= $limit) {
                return back()->with('error', 'Your Account Create limit has been exceeded.');
            }
        }
        DB::beginTransaction();
        try {
            if (!Account::where('default_status', 'Default')->exists()) {
                $request->default_status = 'Default';
            }
            if ($request->default_status == 'Default') {
                Account::where('default_status', 'Default')
                    ->update([
                        'default_status' => 'Not Default'
                    ]);
            }
            Account::create([
                'company_id' => auth()->user()->company_id,
                'account_name'        => $request->account_name,
                'address'        => $request->address,
                'account_holder_name' => $request->account_holder_name,
                'account_number'      => $request->account_number,
                'opening_balance'     => $request->opening_balance,
                'current_balance'     => $request->opening_balance,
                'default_status'      => $request->default_status,
                'status'              => $request->status,
                'created_by'          => auth()->id(),
            ]);
            DB::commit();
            return redirect()->route('accounts.index')->with('success', 'Account Created Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Account $account)
    {
        $account->load([
            'paymentType'
        ]);

        $transactions = $account->transactions()
            ->with([
                'receipt',
                'user'
            ])->orderBy('transaction_date')->orderBy('id')->paginate(20);
        $totalCredit = $account->transactions()->sum('credit');
        $totalDebit = $account->transactions()->sum('debit');
        $currentBalance = $account->current_balance;
        $openingBalance = $account->opening_balance;
        return view(
            'BackEnd.Accounts.show',
            compact(
                'account',
                'transactions',
                'totalCredit',
                'totalDebit',
                'openingBalance',
                'currentBalance'
            )
        );
    }

    public function update(Request $request, Account $account)
    {
        $request->validateWithBag('edit', [
            'account_name'        => 'required|max:255',
            'account_holder_name' => 'required|max:255',
            'account_number'      => 'required|max:255|unique:accounts,account_number,' . $account->id,
            'opening_balance'     => 'required|numeric|min:0',
            'default_status'      => 'required|in:Default,Not Default',
            'status'              => 'required|in:Active,Inactive',
        ]);
        DB::beginTransaction();
        try {
            if (
                $account->default_status == 'Default' &&
                $request->default_status == 'Not Default'
            ) {
                $defaultCount = Account::where('default_status', 'Default')->count();
                if ($defaultCount == 1) {
                    return back()->withInput()->with('error', 'At least one account must remain as Default.');
                }
            }
            if ($request->default_status == 'Default') {
                Account::where('id', '!=', $account->id)
                    ->update([
                        'default_status' => 'Not Default'
                    ]);
            }
            $difference = $request->opening_balance - $account->opening_balance;
            $currentBalance = $account->current_balance + $difference;
            $account->update([
                'payment_type_id'     => $request->payment_type_id,
                'account_name'        => $request->account_name,
                'address'        => $request->address,
                'account_holder_name' => $request->account_holder_name,
                'account_number'      => $request->account_number,
                'opening_balance'     => $request->opening_balance,
                'current_balance'     => $currentBalance,
                'default_status'      => $request->default_status,
                'status'              => $request->status,
                'updated_by'          => auth()->id(),
            ]);
            DB::commit();
            return redirect()->route('accounts.index')->with('success', 'Account Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Account $account)
    {
        if ($account->transactions()->exists()) {
            return redirect()->route('accounts.index')->with('error', 'This account has transaction history. It cannot be deleted.');
        }
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account Deleted Successfully.');
    }
}
