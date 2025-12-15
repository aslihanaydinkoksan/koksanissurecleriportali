<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Rehber Listesi
     */
    public function index()
    {
        $contacts = Contact::orderBy('name')->paginate(10);
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Yeni Kişi Kaydetme (Modal ile hızlı ekleme yapacağız)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'profession' => 'required|string', // Elektrikçi, Tesisatçı vs.
            'email' => 'nullable|email',
            'notes' => 'nullable|string',
        ]);

        Contact::create($validated);

        return back()->with('success', 'Kişi rehbere eklendi.');
    }

    /**
     * Silme
     */
    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return redirect()->route('contacts.index')
            ->with('success', 'Rehber kişisi başarıyla silindi.');
    }
}