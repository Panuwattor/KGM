<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\ConsentLog;
use App\Models\DealerApplication;
use App\Models\ShippingProvider;
use App\Models\ShippingRate;
use App\Models\ShippingSetting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'บันทึกการตั้งค่าแล้ว');
    }

    public function shipping()
    {
        $shippings = ShippingSetting::orderBy('sort_order')->get();
        return view('admin.settings.shipping', compact('shippings'));
    }

    public function updateShipping(Request $request)
    {
        ShippingSetting::truncate();
        foreach ($request->shippings as $s) {
            ShippingSetting::create($s);
        }
        return back()->with('success', 'บันทึกการตั้งค่าการจัดส่งแล้ว');
    }

    public function shippingRates()
    {
        $rates = ShippingRate::orderBy('min_qty')->get();
        return view('admin.settings.shipping-rates', compact('rates'));
    }

    public function updateShippingRates(Request $request)
    {
        $request->validate([
            'rates'           => 'required|array|min:1',
            'rates.*.min_qty' => 'required|integer|min:1',
            'rates.*.price'   => 'required|numeric|min:0',
        ]);

        ShippingRate::truncate();
        foreach ($request->rates as $i => $r) {
            ShippingRate::create([
                'min_qty'   => $r['min_qty'],
                'max_qty'   => $r['max_qty'] ?: null,
                'price'     => $r['price'],
                'is_active' => isset($r['is_active']),
            ]);
        }
        return back()->with('success', 'บันทึกอัตราค่าขนส่งแล้ว');
    }

    public function shippingProviders()
    {
        $providers = ShippingProvider::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.settings.shipping-providers', compact('providers'));
    }

    public function updateShippingProviders(Request $request)
    {
        $request->validate([
            'providers'        => 'required|array|min:1',
            'providers.*.name' => 'required|string|max:100',
        ]);

        ShippingProvider::truncate();
        foreach (array_values($request->providers) as $i => $p) {
            ShippingProvider::create([
                'name'      => trim($p['name']),
                'sort_order'=> $i,
                'is_active' => isset($p['is_active']),
            ]);
        }
        return back()->with('success', 'บันทึกบริษัทขนส่งแล้ว');
    }

    public function logs(Request $request)
    {
        $logs = AdminLog::with('admin')->latest()->paginate(50);
        return view('admin.settings.logs', compact('logs'));
    }

    public function consentLogs()
    {
        $logs = ConsentLog::latest('consented_at')->paginate(50);
        return view('admin.settings.consent-logs', compact('logs'));
    }

    public function dealers()
    {
        $dealers = DealerApplication::latest()->paginate(20);
        return view('admin.dealers.index', compact('dealers'));
    }

    public function dealerStatus(Request $request, DealerApplication $dealer)
    {
        $request->validate(['status' => 'required|in:new,reviewing,approved,rejected']);
        $dealer->update(['status' => $request->status, 'admin_note' => $request->admin_note]);
        return back()->with('success', 'อัปเดตสถานะตัวแทนแล้ว');
    }
}
