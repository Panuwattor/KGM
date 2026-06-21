<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Districts;
use App\Models\Subdistricts;
use Illuminate\Http\Request;

class SubdistrictController extends Controller
{
    public function index(Request $request, Districts $district)
    {
        $province = $district->province;

        $subdistricts = $district->subdistricts()
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($w) use ($request) {
                    $w->where('name_in_thai', 'like', "%{$request->search}%")
                        ->orWhere('name_in_english', 'like', "%{$request->search}%")
                        ->orWhere('code', 'like', "%{$request->search}%")
                        ->orWhere('zip_code', 'like', "%{$request->search}%");
                });
            })
            ->orderBy('name_in_thai')
            ->paginate(20)
            ->withQueryString();

        return view('admin.subdistricts.index', compact('province', 'district', 'subdistricts'));
    }

    public function create(Districts $district)
    {
        $province = $district->province;
        return view('admin.subdistricts.form', compact('province', 'district'));
    }

    public function store(Request $request, Districts $district)
    {
        $district->subdistricts()->create($this->validateData($request));

        return redirect()->route('admin.districts.subdistricts.index', $district)->with('success', 'เพิ่มตำบลแล้ว');
    }

    public function edit(Subdistricts $subdistrict)
    {
        $district = $subdistrict->district;
        $province = $district->province;
        return view('admin.subdistricts.form', compact('province', 'district', 'subdistrict'));
    }

    public function update(Request $request, Subdistricts $subdistrict)
    {
        $subdistrict->update($this->validateData($request));

        return redirect()->route('admin.districts.subdistricts.index', $subdistrict->district_id)->with('success', 'อัปเดตตำบลแล้ว');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'code'            => 'nullable|string|max:10',
            'name_in_thai'    => 'required|string|max:255',
            'name_in_english' => 'nullable|string|max:255',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'zip_code'        => 'nullable|string|max:10',
        ], [], [
            'name_in_thai'    => 'ชื่อตำบล (ไทย)',
            'name_in_english' => 'ชื่อตำบล (อังกฤษ)',
            'code'            => 'รหัสตำบล',
            'zip_code'        => 'รหัสไปรษณีย์',
        ]);
    }
}
