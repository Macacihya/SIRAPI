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
        $role = $request->input('role', 'admin');
        
        $loginCredentials = ['password' => $request->input('password')];

        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $loginCredentials['email'] = $input;
        } else {
            if ($role === 'admin') {
                $loginCredentials['username'] = $input;
            } else {
                // Cari NIP guru di tabel gurus
                $guru = \App\Models\Guru::with('user')->where('nip', $input)->first();
                if ($guru && $guru->user) {
                    $loginCredentials['username'] = $guru->user->username;
                } else {
                    // Fallback ke username jika NIP tidak ditemukan
                    $loginCredentials['username'] = $input;
                }
            }
        }

        if (Auth::attempt($loginCredentials, $request->boolean('remember'))) {
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
