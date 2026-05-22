<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Hiển thị danh sách liên hệ từ khách hàng
    public function index(Request $request)
    {
        $query = Contact::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $contacts = $query->latest()->paginate(15);
        
        $pendingCount = Contact::where('status', 'pending')->count();
        $resolvedCount = Contact::where('status', 'resolved')->count();
        
        return view('admin.contacts.index', compact('contacts', 'pendingCount', 'resolvedCount'));
    }

    // Xem chi tiết nội dung liên hệ
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contacts.show', compact('contact'));
    }

    // Gửi phản hồi liên hệ và hoàn tất
    public function reply(Request $request, $id)
    {
        $request->validate(['reply_message' => 'required|string']);
        $contact = Contact::findOrFail($id);
        
        $contact->update([
            'reply_message' => $request->reply_message,
            'status' => 'resolved'
        ]);

        // Tính năng gửi mail (Có thể demo bằng Log hoặc Mailtrap)
        // Mail::to($contact->email)->send(new \App\Mail\ContactReplyMail($contact));

        return redirect()->back()->with('success', 'Đã gửi phản hồi cho khách hàng thành công!');
    }
}
