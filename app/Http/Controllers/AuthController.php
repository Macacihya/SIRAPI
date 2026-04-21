<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $input = $request->input('username');
        
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $loginField = 'email';
        } elseif (is_numeric($input) && strlen($input) >= 10) {
            $loginField = 'nip';
        } else {
            $loginField = 'username';
        }
        $role = $request->input('role', 'admin');

        // Validasi: Guru dan Wali Kelas (harus Email/NIP)
        if (($role === 'guru' || $role === 'walikelas') && $loginField === 'username') {
            return back()->withErrors([
                'username' => 'Guru dan Wali Kelas wajib menggunakan Email atau NIP untuk login.',
            ])->onlyInput('username');
        }

        if (Auth::attempt([$loginField => $request->input('username'), 'password' => $request->input('password')], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role !== $role) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Role tidak sesuai dengan akun Anda.',
                ])->onlyInput('username');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'password' => 'Username atau kata sandi tidak sesuai.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
