<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invite; // ← udah bener ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->route('dashboard');
        }

        throw ValidationException::withMessages([
            'username' => ['Username atau password salah.'],
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:4',
            'invite_code' => 'required|string|exists:invites,code',
        ]);

        // Cek kode undangan di database
        $invite = Invite::where('code', $request->invite_code)->first();

        if ($invite->is_used) {
            return back()->withErrors(['invite_code' => 'Kode undangan ini sudah dipakai!'])->withInput();
        }

        // Buat akun baru dengan role dari invite
        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $invite->role ?? User::ROLE_STAFF,
        ]);

        // Tandai kode sudah digunakan
        $invite->update(['is_used' => true]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Role Anda: ' . ucfirst($invite->role ?? 'staff'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
