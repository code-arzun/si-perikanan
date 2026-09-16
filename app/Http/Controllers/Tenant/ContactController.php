<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Requests\Tenant\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        
        $query = Contact::where('tenant_id', $tenantId);

        if ($request->filled('type') && in_array($request->type, ['supplier', 'buyer'])) {
            $query->whereIn('type', [$request->type, 'both']);
        }

        $contacts = $query->latest()->paginate(15);
        
        // $contacts = Contact::latest()->get();

        return view('tenant.contacts.index', compact('contacts'));
    }

    public function store(ContactRequest $request)
    {
        $validated = $request->validated();
        $validated['tenant_id'] = auth()->user()->tenant_id;

        Contact::create($validated);

        return back()->with('success', 'Kontak berhasil ditambahkan!');
    }

    public function update(ContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());

        return back()->with('success', 'Data kontak berhasil diperbarui!');
    }

    public function destroy(Contact $contact)
    {
        if ($contact->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $contact->delete();
        return back()->with('success', 'Kontak berhasil dihapus!');
    }
}