<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::withCount('applications')->latest()->paginate(15);
        return view('admin.careers.index', compact('careers'));
    }

    public function create() { return view('admin.careers.form'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'department'   => 'nullable|string|max:100',
            'type'         => 'required|in:full_time,part_time,contract',
            'description'  => 'required|string',
            'requirements' => 'nullable|string',
            'benefits'     => 'nullable|string',
            'location'     => 'nullable|string|max:255',
            'vacancies'    => 'integer|min:1',
            'is_active'    => 'boolean',
            'closes_at'    => 'nullable|date',
        ]);
        Career::create($data);
        return redirect()->route('admin.careers.index')->with('success', 'เพิ่มตำแหน่งงานแล้ว');
    }

    public function edit(Career $career) { return view('admin.careers.form', compact('career')); }

    public function update(Request $request, Career $career)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'department'   => 'nullable|string|max:100',
            'type'         => 'required|in:full_time,part_time,contract',
            'description'  => 'required|string',
            'requirements' => 'nullable|string',
            'benefits'     => 'nullable|string',
            'location'     => 'nullable|string|max:255',
            'vacancies'    => 'integer|min:1',
            'is_active'    => 'boolean',
            'closes_at'    => 'nullable|date',
        ]);
        $career->update($data);
        return redirect()->route('admin.careers.index')->with('success', 'อัปเดตตำแหน่งงานแล้ว');
    }

    public function destroy(Career $career)
    {
        $career->delete();
        return redirect()->route('admin.careers.index')->with('success', 'ลบตำแหน่งงานแล้ว');
    }

    public function applications(Career $career)
    {
        $applications = $career->applications()->latest()->paginate(20);
        return view('admin.careers.applications', compact('career', 'applications'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $request->validate(['status' => 'required|in:new,reviewing,interviewed,hired,rejected']);
        $application->update(['status' => $request->status, 'admin_note' => $request->admin_note]);
        return back()->with('success', 'อัปเดตสถานะผู้สมัครแล้ว');
    }
}
