<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Districts;
use App\Models\Provinces;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request, Provinces $province)
    {
        $districts = $province->districts()
            ->withCount('subdistricts')
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

        return view('admin.districts.index', compact('province', 'districts'));
    }

    public function create(Provinces $province)
    {
        return view('admin.districts.form', compact('province'));
    }

    public function store(Request $request, Provinces $province)
    {
        $province->districts()->create($this->validateData($request));

        return redirect()->route('admin.provinces.districts.index', $province)->with('success', 'เพิ่มอำเภอแล้ว');
    }

    public function edit(Districts $district)
    {
        $province = $district->province;
        return view('admin.districts.form', compact('province', 'district'));
    }

    public function update(Request $request, Districts $district)
    {
        $district->update($this->validateData($request));

        return redirect()->route('admin.provinces.districts.index', $district->province_id)->with('success', 'อัปเดตอำเภอแล้ว');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'code'            => 'nullable|string|max:10',
            'name_in_thai'    => 'required|string|max:255',
            'name_in_english' => 'nullable|string|max:255',
        ], [], [
            'name_in_thai'    => 'ชื่ออำเภอ (ไทย)',
            'name_in_english' => 'ชื่ออำเภอ (อังกฤษ)',
            'code'            => 'รหัสอำเภอ',
        ]);
    }
}
