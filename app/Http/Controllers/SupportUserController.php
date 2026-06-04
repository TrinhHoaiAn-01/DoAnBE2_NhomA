<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportUserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HIỂN THỊ TRANG SUPPORT
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('user.support-user');
    }

    /*
    |--------------------------------------------------------------------------
    | GỬI EMAIL HỖ TRỢ
    |--------------------------------------------------------------------------
    */
    public function send(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */
        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email',

            'type' => 'required|string',

            'message' => 'required|string|min:10',

        ]);

        /*
        |--------------------------------------------------------------------------
        | NỘI DUNG EMAIL
        |--------------------------------------------------------------------------
        */
        $content = "

        HỖ TRỢ NGƯỜI DÙNG

        -------------------------

        Họ tên: {$request->name}

        Email: {$request->email}

        Loại hỗ trợ: {$request->type}

        Nội dung:

        {$request->message}

        ";

        /*
        |--------------------------------------------------------------------------
        | GỬI EMAIL
        |--------------------------------------------------------------------------
        */
        Mail::raw($content, function ($mail) use ($request) {

            $mail->to('yourgmail@gmail.com')

                ->subject('Yêu cầu hỗ trợ từ người dùng');

        });

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */
        return back()->with(

            'success',

            'Gửi yêu cầu hỗ trợ thành công!'

        );
    }
}