<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderHistoryController extends Controller
{
    public function index(Request $request): View
    {
        // Trang nay se hien thi lich su don hang cua nguoi dung dang nhap.
        return view('orders.index', [
            'orders' => collect(),
        ]);
    }
}
