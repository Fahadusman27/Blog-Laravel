<?php

use Illuminate\Support\Facades\Route;
use App\Services\ApiService;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Middleware\CheckApiSession;
use App\Http\Middleware\RedirectIfLoggedIn;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('pages.blog-index', [
        'featuredPost' => null,
        'posts'        => collect([]),
        'categories'   => collect([]),
    ]);
})->name('home');

Route::get('/blog', function () {
    return view('pages.blog-index', [
        'featuredPost' => null,
        'posts'        => collect([]),
        'categories'   => collect([]),
    ]);
})->name('frontend.blog.index');

Route::get('/about', fn() => view('pages.about'))->name('frontend.pages.about');
Route::get('/terms', fn() => view('pages.terms'))->name('frontend.pages.terms');

/*
|--------------------------------------------------------------------------
| Guest Routes (Hanya untuk yang BELUM login)
|--------------------------------------------------------------------------
*/

Route::middleware([RedirectIfLoggedIn::class])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('frontend.auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('frontend.auth.login.post');

    Route::get('/register', fn() => view('auth.register'))->name('frontend.auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('frontend.auth.register.post');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Hanya untuk yang SUDAH login)
|--------------------------------------------------------------------------
| Middleware CheckApiSession memastikan:
| 1. User punya token aktif.
| 2. Browser tidak menyimpan cache (Anti-Back Button).
*/

Route::middleware([CheckApiSession::class])->group(function () {
    
    // Dashboard: Menampilkan Role & Konten Trending
    Route::get('/dashboard', function (ApiService $api) {
        try {
            $trendingPosts = $api->getPosts(); 
            $trending = array_slice($trendingPosts ?? [], 0, 3);
        } catch (\Exception $e) {
            $trending = [];
        }

        return view('pages.dashboard', [
            'role'     => session('user_data'),
            'trending' => $trending,
        ]);
    })->name('dashboard');

    // Manajemen Artikel
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles/store', [ArticleController::class, 'store'])->name('articles.store');

    // Akun & Profile (Integrasi Go Fiber)
    Route::get('/user/account', [App\Http\Controllers\ProfileController::class, 'edit'])->name('frontend.user.account');
    Route::put('/user/account', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Alias untuk kecocokan rute lama jika diperlukan
    Route::get('/user/profile', fn() => redirect()->route('frontend.user.account'))->name('profile.edit');
    
    // Logout
    Route::post('/logout', function () {
        session()->forget(['api_token', 'user_data', 'is_logged_in']);
        session()->flush();
        return redirect()->route('frontend.auth.login');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Content & Data Routes
|--------------------------------------------------------------------------
*/

// Halaman Kategori (Mengambil data dari Go)
Route::get('/categories', function (ApiService $api) {
    try {
        $categories = $api->getCategories();
    } catch (\Exception $e) {
        $categories = [];
    }
    return view('pages.categories', compact('categories'));
})->name('frontend.blog.category');

// Detail Blog & Komentar
Route::get('/blog/{slug}', [ArticleController::class, 'show'])->name('frontend.blog.show');
Route::post('/blog/{id}/comments', fn() => response()->json(['ok' => true]))->name('frontend.blog.comments.store');