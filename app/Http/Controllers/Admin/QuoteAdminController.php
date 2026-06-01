<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class QuoteAdminController extends Controller
{
    public function index()
    {
        $quotes = QuoteRequest::with('user')->latest()->paginate(20);
        return view('admin.quotes.index', compact('quotes'));
    }

    public function show(QuoteRequest $quote)
    {
        return view('admin.quotes.show', compact('quote'));
    }

    public function respond(Request $request, QuoteRequest $quote)
    {
        $request->validate([
            'quoted_price' => 'required|numeric|min:0',
            'admin_note'   => 'nullable|string',
            'quote_pdf'    => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [
            'quoted_price' => $request->quoted_price,
            'admin_note'   => $request->admin_note,
            'status'       => 'quoted',
            'quoted_at'    => now(),
        ];

        if ($request->hasFile('quote_pdf')) {
            $data['quote_pdf'] = $request->file('quote_pdf')->store('quotes', 'public');
        }

        $quote->update($data);
        return back()->with('success', 'ส่งใบเสนอราคาแล้ว');
    }

    public function updateStatus(Request $request, QuoteRequest $quote)
    {
        $request->validate(['status' => 'required|in:pending,quoted,accepted,rejected,closed']);
        $quote->update(['status' => $request->status]);
        return back()->with('success', 'อัปเดตสถานะแล้ว');
    }
}
