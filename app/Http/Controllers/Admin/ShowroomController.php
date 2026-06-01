<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use Illuminate\Http\Request;

class ShowroomController extends Controller
{
    public function index()
    {
        $showrooms = Showroom::orderBy('sort_order')->get();
        return view('admin.showrooms.index', compact('showrooms'));
    }

    public function create() { return view('admin.showrooms.form'); }

    public function store(Request $request)
    {
        $data = $this->validate($request, $this->rules());
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('showrooms', 'public');
        }
        Showroom::create($data);
        return redirect()->route('admin.showrooms.index')->with('success', 'เพิ่มสาขาแล้ว');
    }

    public function edit(Showroom $showroom) { return view('admin.showrooms.form', compact('showroom')); }

    public function update(Request $request, Showroom $showroom)
    {
        $data = $this->validate($request, $this->rules());
        if ($request->hasFile('image')) {
            \Storage::disk('public')->delete($showroom->image);
            $data['image'] = $request->file('image')->store('showrooms', 'public');
        }
        $showroom->update($data);
        return redirect()->route('admin.showrooms.index')->with('success', 'อัปเดตสาขาแล้ว');
    }

    public function destroy(Showroom $showroom)
    {
        $showroom->delete();
        return redirect()->route('admin.showrooms.index')->with('success', 'ลบสาขาแล้ว');
    }

    private function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'phone'         => 'nullable|string|max:20',
            'line_id'       => 'nullable|string|max:100',
            'email'         => 'nullable|email',
            'open_hours'    => 'nullable|string',
            'map_embed_url' => 'nullable|string',
            'is_active'     => 'boolean',
            'is_main'       => 'boolean',
            'sort_order'    => 'integer',
            'image'         => 'nullable|image|max:5120',
        ];
    }
}
