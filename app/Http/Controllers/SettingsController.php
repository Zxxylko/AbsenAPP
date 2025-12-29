<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,'.Auth::id(),
        ]);

        $user = Auth::user();
        $user->username = $request->username;
        $user->save();

        return redirect()->route('settings.index')->with('success', 'Username berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('settings.index')->with('success', 'Password berhasil diperbarui!');
    }
}
