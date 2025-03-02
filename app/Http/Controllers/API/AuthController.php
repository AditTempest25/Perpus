<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            // Mengembalikan ke form dengan error jika validasi gagal
            return back()->withErrors($validator)->withInput();
        } 

        // Membuat user baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'role' => 'user', // default user
        ]);

        // Login user secara otomatis
        Auth::login($user);

        // Redirect ke halaman dashboard
        return Redirect::route('dashboard');
    }

    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Coba login dengan kredensial yang diberikan
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
        }

        // Redirect ke halaman dashboard setelah login berhasil
        return Redirect::route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
