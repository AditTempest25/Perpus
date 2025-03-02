<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// OAuth Routes (disarankan menggunakan middleware web)
Route::get('/auth/{provider}', [OAuthController::class, 'redirectToProvider'])
    ->name('oauth.redirect');
Route::get('/auth/{provider}/callback', [OAuthController::class, 'handleProviderCallback'])
    ->name('oauth.callback');

Route::prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});
// Dashboard Admin
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return Inertia::render('admin/dashboard');
    })->name('admin.dashboard');
});

// Dashboard Petugas
Route::middleware('role:petugas')->group(function () {
    Route::get('/petugas/dashboard', function () {
        return Inertia::render('petugas/dashboard');
    })->name('petugas.dashboard');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
