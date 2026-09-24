<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Show contact form and all contacts.
     */
    public function index(): View
    {
        $contacts = Contact::latest()->get();

        return view('BackEnd.Contact.index', compact('contacts'));
    }

    /**
     * Store a new contact.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        Contact::create($validated);

        return redirect()
            ->to(url()->previous() . '#form')
            ->with('success', 'Your demo request has been submitted successfully.');
    }

    /**
     * Delete a contact.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return back()->with('success', 'Contact deleted successfully.');
    }
}
