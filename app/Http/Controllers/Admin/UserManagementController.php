<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = User::query()->with('approver')->latest();

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $users = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => User::count(),
            'pending' => User::where('status', 'pending')->count(),
            'approved' => User::where('status', 'approved')->count(),
            'rejected' => User::where('status', 'rejected')->count(),
        ];

        return view('admin.users.index', compact('users', 'status', 'counts'));
    }

    public function approve(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['action' => 'Tidak bisa approve akun sendiri.']);
        }

        $wasNotApproved = !$user->isApproved();

        $user->update([
            'status' => User::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Kirim email approval hanya kalau sebelumnya belum approved
        // (untuk hindari spam saat approve ulang)
        if ($wasNotApproved) {
            try {
                $user->notify(new AccountApproved());
            } catch (\Throwable $e) {
                Log::warning("Gagal kirim email approval ke {$user->email}: " . $e->getMessage());
            }
        }

        return back()->with('success', "User {$user->name} berhasil disetujui dan email pemberitahuan dikirim.");
    }

    public function reject(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['action' => 'Tidak bisa reject akun sendiri.']);
        }

        $user->update([
            'status' => User::STATUS_REJECTED,
            'approved_at' => null,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', "User {$user->name} ditolak.");
    }

    public function destroy(User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return back()->withErrors(['action' => 'Hanya Super Admin yang dapat menghapus user.']);
        }

        if ($user->id === Auth::id()) {
            return back()->withErrors(['action' => 'Tidak bisa hapus akun sendiri.']);
        }

        $user->delete();
        return back()->with('success', 'User dihapus.');
    }
}
