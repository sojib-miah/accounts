<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartyController extends Controller
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
        })->where('type', 'Expense')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();

        return view('BackEnd.Party.index', compact('parties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'phone'   => 'nullable|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status'  => 'required|in:Active,Inactive',
        ]);

        // Generate Party ID
        $lastParty = Party::latest('id')->first();

        if ($lastParty) {
            $number = (int) str_replace('PRT_', '', $lastParty->party_id);
            $number++;
        } else {
            $number = 10001;
        }

        $partyId = 'PRT_' . $number;

        Party::create([
            'party_id'   => $partyId,
            'name'       => $request->name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'address'    => $request->address,
            'type'       => 'Expense',
            'status'     => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('party.index')
            ->with('success', 'Party Created Successfully.');
    }

    public function update(Request $request, Party $party)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'phone'   => 'nullable|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status'  => 'required|in:Active,Inactive',
        ]);

        $party->update([
            'name'       => $request->name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'address'    => $request->address,
            'status'     => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('party.index')->with('success', 'Party Updated Successfully.');
    }

    public function destroy(Party $party)
    {
        if ($party->receipts()->exists()) {

            return redirect()
                ->back()
                ->with('error', 'This party has receipts and cannot be deleted.');
        }
        $party->delete();

        return redirect()->route('party.index')->with('success', 'Party Deleted Successfully.');
    }
}
