<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DealerApplication;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function index() { return view('frontend.dealer'); }

    public function submit(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name'  => 'required|string|max:255',
            'email'         => 'required|email',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string',
            'province'      => 'required|string',
            'business_type' => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:1000',
            'tax_id'        => 'nullable|string|max:20',
        ]);

        DealerApplication::create($request->only(
            'business_name', 'contact_name', 'email', 'phone',
            'address', 'province', 'business_type', 'description', 'tax_id'
        ));

        return back()->with('success', 'ส่งใบสมัครตัวแทนจำหน่ายเรียบร้อยแล้ว ทีมงานจะติดต่อกลับภายใน 3-5 วันทำการ');
    }
}
