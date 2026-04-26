<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user profile.
     */
    public function edit(ApiService $api)
{
    try {
        // Ambil data profil (Token ditangani otomatis oleh ApiService)
        $profile = $api->getProfile();
        
        return view('pages.profile-edit', compact('profile'));
    } catch (\Exception $e) {
        // Jika gagal, tampilkan pesan error asli untuk memudahkan debug
        return back()->withErrors(['message' => 'Gagal mengambil data profil: ' . $e->getMessage()]);
    }
}

    /**
     * Update the user profile in the Go backend and sync Laravel session.
     */
    public function update(Request $request, ApiService $api)
    {
        $request->validate([
            'Name'        => 'required|string|max:30',
            'Username'    => 'required|string|max:255',
            'PictureFile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $currentData = session('user_data');
            // Ambil foto lama dengan aman
            $picturePath = is_array($currentData) ? ($currentData['picture'] ?? '') : '';

            if ($request->hasFile('PictureFile')) {
                $file = $request->file('PictureFile');
                $path = $file->store('profiles', 'public');
                $picturePath = asset('storage/' . $path);
            }

            // 1. Update ke Go Fiber
            $api->put('/auth/profile', [
                'Name'     => $request->Name,
                'Username' => $request->Username,
                'Picture'  => $picturePath,
            ]);

            // 2. SYNC: Paksa update session Laravel agar UI langsung berubah
            $currentData = session('user_data');
            
            // Jika data lama string atau array, kita bangun ulang array-nya
            $updatedData = [
                'name'     => $request->Name,
                'username' => $request->Username,
                'picture'  => $picturePath,
                'role'     => is_array($currentData) ? ($currentData['role'] ?? 'user') : $currentData,
                'email'    => is_array($currentData) ? ($currentData['email'] ?? '') : '',
            ];
            
            session(['user_data' => $updatedData]);

            // 3. Redirect ke Dashboard sesuai permintaan
            return redirect()->route('dashboard')->with('flash_success', 'Identity updated and synced successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['message' => 'Failed to update: ' . $e->getMessage()]);
        }
    }
}
