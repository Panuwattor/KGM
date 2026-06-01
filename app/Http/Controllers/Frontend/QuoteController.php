<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index() { return view('frontend.quote'); }

    public function submit(Request $request)
    {
        $request->validate([
            'company_name'    => 'required|string|max:255',
            'contact_name'    => 'required|string|max:255',
            'email'           => 'required|email',
            'phone'           => 'required|string|max:20',
            'product_details' => 'required|string|max:2000',
            'quantity'        => 'required|integer|min:1',
            'notes'           => 'nullable|string|max:2000',
            'attachment'      => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $attachPath = $request->file('attachment')?->store('quote-attachments', 'local');

        QuoteRequest::create([
            'user_id'         => auth()->id(),
            'company_name'    => $request->company_name,
            'contact_name'    => $request->contact_name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'product_details' => $request->product_details,
            'quantity'        => $request->quantity,
            'notes'           => $request->notes,
            'attachment'      => $attachPath,
        ]);

        return redirect()->route('quote.success');
    }

    public function success() { return view('frontend.quote-success'); }
}
