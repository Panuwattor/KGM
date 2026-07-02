<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsTicker;
use Illuminate\Http\Request;

class NewsTickerController extends Controller
{
    public function index()
    {
        $tickers = NewsTicker::ordered()->paginate(20);
        return view('admin.news-tickers.index', compact('tickers'));
    }

    public function create()
    {
        return view('admin.news-tickers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plain_text' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        NewsTicker::create($validated);

        return redirect()->route('admin.news-tickers.index')
            ->with('success', 'เพิ่มข่าวสำคัญสำเร็จ');
    }

    public function edit(NewsTicker $newsTicker)
    {
        return view('admin.news-tickers.edit', compact('newsTicker'));
    }

    public function update(Request $request, NewsTicker $newsTicker)
    {
        $validated = $request->validate([
            'plain_text' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $newsTicker->update($validated);

        return redirect()->route('admin.news-tickers.index')
            ->with('success', 'อัพเดตข่าวสำคัญสำเร็จ');
    }

    public function destroy(NewsTicker $newsTicker)
    {
        $newsTicker->delete();

        return redirect()->route('admin.news-tickers.index')
            ->with('success', 'ลบข่าวสำคัญสำเร็จ');
    }
}
