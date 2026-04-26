<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah session login ADA
        if (!session()->has('is_logged_in') || !session()->has('api_token')) {
            // Jika tidak ada, paksa ke halaman login
            return redirect()->route('frontend.auth.login')
                ->with('flash_error', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
        }

        // 2. Jalankan request selanjutnya
        $response = $next($request);

        // 3. Tambahkan Header agar Browser TIDAK menyimpan cache halaman (Anti-Back Button)
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
