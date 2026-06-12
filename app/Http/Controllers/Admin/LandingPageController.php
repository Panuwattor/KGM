<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    public function index()
    {
        $landingPages = LandingPage::latest()->get();
        return view('admin.landing_pages.index', compact('landingPages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $path = $request->file('image')->store('landing_pages', 'public');

        LandingPage::create([
            'image_path' => $path,
            'is_active'  => false,
        ]);

        return redirect()->route('admin.landing-pages.index')->with('success', 'เพิ่มรูป Landing Page แล้ว');
    }

    public function toggle(LandingPage $landingPage)
    {
        if (!$landingPage->is_active) {
            // Deactivate all others first
            LandingPage::where('id', '!=', $landingPage->id)->update(['is_active' => false]);
        }

        $landingPage->update(['is_active' => !$landingPage->is_active]);

        $status = $landingPage->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        return redirect()->route('admin.landing-pages.index')->with('success', "{$status} Landing Page แล้ว");
    }

    public function destroy(LandingPage $landingPage)
    {
        Storage::disk('public')->delete($landingPage->image_path);
        $landingPage->delete();
        return redirect()->route('admin.landing-pages.index')->with('success', 'ลบรูป Landing Page แล้ว');
    }
}
