<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = request()->user();

        if ($user?->role === 'admin' || $user?->role === 'admin_tu') {
            return redirect()->route('admin.dashboard');
        }

        if ($user?->role === 'walikelas') {
            return view('walikelas.dashboard-walikelas', [
                'user' => $user,
            ]);
        }

        return view('dashboard', [
            'user' => $user,
        ]);
    }
}
