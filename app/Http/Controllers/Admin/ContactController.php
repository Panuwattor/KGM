<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.contacts.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return view('admin.contacts.show', compact('message'));
    }

    public function reply(Request $request, ContactMessage $message)
    {
        $request->validate(['reply' => 'required|string|max:2000']);
        $message->update([
            'reply' => $request->reply,
            'replied_at' => now(),
            'replied_by' => auth()->id(),
            'is_read' => true,
        ]);
        return back()->with('success', 'ตอบกลับข้อความแล้ว');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'ลบข้อความแล้ว');
    }
}
