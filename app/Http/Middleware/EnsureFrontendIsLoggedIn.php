<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFrontendIsLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah session login ADA
        if (!session()->has('is_logged_in') || !session()->has('api_token')) {
            // Jika tidak ada, paksa ke halaman login dengan pesan error
            return redirect()->route('frontend.auth.login')
                ->withErrors(['message' => 'Silakan login terlebih dahulu untuk mengakses dashboard.']);
        }

        // 2. Tambahkan Header agar Browser TIDAK menyimpan cache halaman dashboard
        // Ini kunci agar tombol "Back" tidak menampilkan data lama
        $response = $next($request);
        
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}