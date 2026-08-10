<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PackageHelper;

class ReceiverController extends Controller
{
    public function index(Request $request)
    {
        $parties = Party::with(['creator', 'updater'])->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('party_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        })->where('type', 'Customer')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();

        return view('BackEnd.Receiver.index', compact('parties'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'name'        => 'required|max:255',
            'phone'       => 'nullable|max:30',
            'email'       => 'nullable|email|max:255',
            'address'     => 'nullable|string',
            'company_name' => 'required|string|max:255',
            'designation' => 'nullable|string',
            'status'      => 'required|in:Active,Inactive',
        ]);

        if (!Auth::user()->hasRole('Super-Admin')) {
            $current = Party::where('created_by', Auth::id())
                ->where('type', 'Customer')
                ->count();

            if ($message = PackageHelper::checkLimit('party_limit', $current)) {
                return back()->with('error', $message);
            }
        }

        Party::create([
            'company_id'  => auth()->user()->company_id,
            'party_id'    => random_int(100000, 999999),
            'name'        => $request->name,
            'designation' => $request->designation,
            'phone'       => $request->phone,
            'email'       => $request->email,
            'company_name' => $request->company_name,
            'address'     => $request->address,
            'type'        => 'Customer',
            'status'      => $request->status,
            'created_by'  => auth()->id(),
        ]);

        return redirect()
            ->route('receiver.index')
            ->with('success', 'Receiver Created Successfully.');
    }


    public function update(Request $request, Party $party)
    {
        $request->validateWithBag('edit', [
            'name'    => 'required|max:255',
            'phone'   => 'nullable|max:30',
            'email'   => 'nullable|email|max:255',
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'designation' => 'nullable|string',
            'status'  => 'required|in:Active,Inactive',
        ]);

        $party->update([
            'name'       => $request->name,
            'designation'       => $request->designation,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'company_name'      => $request->company_name,
            'address'    => $request->address,
            'status'     => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('receiver.index')->with('success', 'Receiver Updated Successfully.');
    }

    public function destroy(Party $party)
    {
        if ($party->receipts()->exists()) {

            return redirect()->back()->with(
                'error',
                'This Party has receipts. It cannot be deleted.'
            );
        }
        $party->delete();
        return redirect()->route('receiver.index')->with('success', 'Receiver Deleted Successfully.');
    }
}
