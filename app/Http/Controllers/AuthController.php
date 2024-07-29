<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Application;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function viewLogin()
    {
        return view('Auth/login'); // Pastikan Anda memiliki view login.blade.php
    }

    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $input = $request->only('email', 'password');

        // Temukan pengguna berdasarkan email
        $user = User::where('email', $input['email'])->first();

        // Periksa jika pengguna ditemukan dan password cocok
        if ($user && Hash::check($input['password'], $user->password)) {
            // Buat token
            $token = Str::random(60);

            // Simpan token di tabel applications
            Application::updateOrCreate(
                ['user_id' => $user->id],
                ['token' => hash('sha256', $token), 'expired_at' => now()->addDay()]
            );

            // Perbarui status login pengguna
            $user->update([
                'is_login' => true,
                'last_login' => now(),
            ]);

            // Simpan ke login history
            LoginHistory::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'status' => 'success',
                'device' => $request->header('User-Agent'),
                'created_at' => now(),
            ]);

            // Simpan token di session
            Session::put(['api_token' => $token, 'user_id' => $user->id]);

            // Arahkan ke halaman dashboard
            return redirect()->route('home');
        } else {
            return response()->json(['error' => 'Invalid Password input'], 401);
        }

        // Simpan login history untuk percobaan login yang gagal
        if ($user) {
            LoginHistory::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'status' => 'failed',
                'device' => $request->header('User-Agent'),
                'created_at' => now(),
            ]);
        }

        return response()->json(['error' => 'Failed to login'], 401);
    }

    public function logout(Request $request)
    {

        $token = Session::get('api_token');

        if ($token) {
            Application::where('token', hash('sha256', $token))->delete();
        }
        // Hapus token dari session
        Session::forget(['api_token', 'user_id']);



        // Arahkan ke halaman login
        return redirect()->route('login.form');
    }
}
