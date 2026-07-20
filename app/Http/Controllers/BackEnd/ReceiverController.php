<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        })->where('type', 'Income')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();

        return view('BackEnd.Receiver.index', compact('parties'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'name'    => 'required|max:255',
            'phone'   => 'nullable|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'designation' => 'nullable|string',
            'status'  => 'required|in:Active,Inactive',
        ]);

        // Generate Party ID
        $lastParty = Party::orderByDesc('party_id')->first();

        $partyId = $lastParty ? ((int) $lastParty->party_id + 1) : 10001;

        Party::create([
            'party_id'   => $partyId,
            'name'       => $request->name,
            'designation'       => $request->designation,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'address'    => $request->address,
            'type'       => 'Income',
            'status'     => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('receiver.index')->with('success', 'Receiver Created Successfully.');
    }

    public function update(Request $request, Party $party)
    {
        $request->validateWithBag('edit', [
            'name'    => 'required|max:255',
            'phone'   => 'nullable|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'designation' => 'nullable|string',
            'status'  => 'required|in:Active,Inactive',
        ]);

        $party->update([
            'name'       => $request->name,
            'designation'       => $request->designation,
            'phone'      => $request->phone,
            'email'      => $request->email,
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
