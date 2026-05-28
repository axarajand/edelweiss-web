<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewRegistrationToAdmin;
use App\Notifications\RegistrationPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function show()
    {
        if (Auth::check() && Auth::user()->isApproved()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => User::STATUS_PENDING,
        ]);

        // Kirim email — wrap dalam try-catch supaya register tidak gagal
        // kalau mail server belum dikonfigurasi (misal di local dev).
        try {
            // 1. Email ke pendaftar
            $newUser->notify(new RegistrationPending());

            // 2. Email ke semua admin yang sudah approved
            $admins = User::where('status', User::STATUS_APPROVED)->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewRegistrationToAdmin($newUser));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim email registrasi: ' . $e->getMessage());
        }

        return redirect()->route('admin.login')->with(
            'success',
            'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan admin. Email pemberitahuan telah dikirim.'
        );
    }
}
