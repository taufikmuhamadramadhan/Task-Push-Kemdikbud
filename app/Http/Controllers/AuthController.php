<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Application;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function viewLogin()
    {
        return view('Auth/login');
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

            Log::info('User ID: ' . $user->id);
            Log::info('Generated Token: ' . $token);

            // Simpan token di tabel applications
            Application::updateOrCreate(
                ['user_id' => $user->id],
                ['id' => Str::uuid(), 'token' => hash('sha256', $token), 'expired_at' => now()->addDay()]
            );

            // Perbarui status login pengguna
            $user->update([
                'is_login' => true,
                'last_login' => now(),
            ]);

            // Simpan ke login history
            LoginHistory::create([
                'user_id' => $user->id,
                'status' => 'success',
                'device' => $request->header('User-Agent'),
                'created_at' => now(),
            ]);

            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => 'Invalid Password input'], 401);
        }

        // Simpan login history untuk percobaan login yang gagal
        if ($user) {
            LoginHistory::create([
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
        $token = $request->bearerToken();
        if ($token) {
            Application::where('token', hash('sha256', $token))->delete();
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
}
