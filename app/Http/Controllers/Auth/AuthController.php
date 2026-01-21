<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * =========================
     * FORM REGISTER
     * =========================
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * =========================
     * PROSES REGISTER
     * (TOKO + USER ADMIN)
     * =========================
     */
    public function register(Request $request)
    {
        $request->validate([
            // toko
            'store_name' => 'required|string|max:100',
            'city' => 'nullable|string|max:50',

            // user
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        DB::transaction(function () use ($request) {

            // =========================
            // CREATE STORE
            // =========================
            $store = Store::create([
                'store_code' => 'CUST-' . strtoupper(Str::random(6)),
                'store_name' => $request->store_name,
                'type' => 'customer',
                'city' => $request->city,
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'store_id' => $store->id,
                'role' => 'customer_admin',
            ]);

            // =========================
            // AUTO LOGIN
            // =========================
            Auth::login($user);
        });

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil. Selamat datang!');
    }

    /**
     * =========================
     * FORM LOGIN
     * =========================
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * =========================
     * PROSES LOGIN
     * =========================
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    /**
     * =========================
     * LOGOUT
     * =========================
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
