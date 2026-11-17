<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use App\Models\Location;

class ContactController extends Controller
{
    public function show()
    {
        return view('site.contact', [
            'locations' => Location::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function store(ContactRequest $request)
    {
        // Honeypot: bot mengisi kolom tersembunyi. Pura-pura sukses, jangan simpan.
        if (filled($request->input('website'))) {
            return redirect()->to(route('contact').'#form')->with('success', 'Terima kasih, pesan Anda sudah kami terima.');
        }

        ContactMessage::create([
            ...$request->safe()->only(['name', 'email', 'phone', 'subject', 'message']),
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->to(route('contact').'#form')
            ->with('success', 'Terima kasih, pesan Anda sudah kami terima. Kami akan menghubungi Anda secepatnya.');
    }
}
