<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function permissions()
    {
        $roles = \App\Models\RolePermission::all();
        return view('admin.permissions', compact('roles'));
    }

    public function logs()
    {
        return "Trang Nhật ký hệ thống đang được xây dựng (Người 5)";
    }
}
