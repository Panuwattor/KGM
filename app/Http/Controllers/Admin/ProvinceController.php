<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provinces;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $provinces = Provinces::withCount('districts')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($w) use ($request) {
                    $w->where('name_in_thai', 'like', "%{$request->search}%")
                        ->orWhere('name_in_english', 'like', "%{$request->search}%")
                        ->orWhere('code', 'like', "%{$request->search}%");
                });
            })
            ->orderBy('name_in_thai')
            ->paginate(20)
            ->withQueryString();

        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        return view('admin.provinces.form');
    }

    public function store(Request $request)
    {
        Provinces::create($this->validateData($request));

        return redirect()->route('admin.provinces.index')->with('success', 'เพิ่มจังหวัดแล้ว');
    }

    public function edit(Provinces $province)
    {
        return view('admin.provinces.form', compact('province'));
    }

    public function update(Request $request, Provinces $province)
    {
        $province->update($this->validateData($request));

        return redirect()->route('admin.provinces.index')->with('success', 'อัปเดตจังหวัดแล้ว');
    }

    public function destroy(Provinces $province)
    {
        if ($province->districts()->exists()) {
            return back()->with('error', 'ลบไม่ได้ เพราะมีอำเภออยู่ในจังหวัดนี้');
        }

        $province->delete();

        return redirect()->route('admin.provinces.index')->with('success', 'ลบจังหวัดแล้ว');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'code'            => 'nullable|string|max:10',
            'name_in_thai'    => 'required|string|max:255',
            'name_in_english' => 'nullable|string|max:255',
        ], [], [
            'name_in_thai'    => 'ชื่อจังหวัด (ไทย)',
            'name_in_english' => 'ชื่อจังหวัด (อังกฤษ)',
            'code'            => 'รหัสจังหวัด',
        ]);
    }
}
