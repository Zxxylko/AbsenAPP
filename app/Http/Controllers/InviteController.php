<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use Carbon\Carbon;

class InviteController extends Controller
{
    /**
     * Cleanup kode berdasarkan aturan umur.
     */
    private function cleanup()
    {
        // Hapus kode yang SUDAH TERPAKAI lebih dari 3 hari
        Invite::where('is_used', true)
            ->where('created_at', '<', Carbon::now()->subDays(3))
            ->delete();

        // Hapus kode yang BELUM TERPAKAI lebih dari 7 hari
        Invite::where('is_used', false)
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->delete();
    }

    public function index()
    {
        $this->cleanup();

        $invites = Invite::latest()->get();
        return view('invites.index', compact('invites'));
    }

    public function invite()
    {
        $this->cleanup();
        return view('invites.index');
    }

    public function store(Request $request)
    {
        $this->cleanup();

        // Input manual 1 kode
        if ($request->has('code')) {
            $request->validate([
                'code' => 'required|string|unique:invites,code',
                'role' => 'sometimes|string|in:admin,staff,viewer',
            ]);

            Invite::create([
                'code' => $request->code,
                'role' => $request->role ?? 'staff',
                'is_used' => false,
            ]);

            return redirect()->route('invites.index')
                ->with('success', 'Kode baru berhasil dibuat dengan role: ' . ucfirst($request->role ?? 'staff'));
        }

        // Auto generate banyak kode
        if ($request->has('codes')) {
            $request->validate([
                'codes' => 'required|array|min:1',
                'role' => 'sometimes|string|in:admin,staff,viewer',
            ]);

            $role = $request->role ?? 'staff';

            foreach ($request->codes as $code) {
                Invite::create([
                    'code' => $code,
                    'role' => $role,
                    'is_used' => false,
                ]);
            }

            return redirect()->route('invites.index')
                ->with('success', count($request->codes) . ' kode undangan berhasil dibuat dengan role: ' . ucfirst($role));
        }

        return redirect()->route('invites.index')->with('error', 'Tidak ada kode yang dibuat.');
    }

    public function destroy($id)
    {
        Invite::findOrFail($id)->delete();

        return redirect()->route('invites.index')
            ->with('success', 'Kode berhasil dihapus!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('success', 'Tidak ada kode yang dipilih.');
        }

        Invite::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Beberapa kode undangan berhasil dihapus ✅');
    }
}
