<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;

class AuthController extends Controller
{
   // Menampilkan halaman login
public function showLoginForm()
{
    // Jika sudah login, paksa ke dashboard
    if (session()->has('is_logged_in')) {
        return redirect()->intended('/dashboard');
    }
    return view('auth.login');
}

public function login(Request $request, ApiService $api)
{
    // Cegah login ulang jika session masih aktif
    if (session()->has('is_logged_in')) {
        return redirect()->intended('/dashboard');
    }

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    try {
        $response = $api->login($credentials);

        session([
            'api_token' => $response['token'],
            'user_data' => $response['role'], // Pastikan sesuai key dari Go
            'is_logged_in' => true
        ]);

        return redirect()->intended('/dashboard');
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Login Error: ' . $e->getMessage()]);
    }
}

public function register(Request $request, ApiService $api)
{
    $data = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed',
    ]);

    try {
        $api->register($data);
        return redirect()->route('frontend.auth.login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Gagal mendaftar di Backend Go: ' . $e->getMessage()]);
    }
}

}
