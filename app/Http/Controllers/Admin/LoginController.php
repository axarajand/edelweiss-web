<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        // Sudah login? langsung dashboard
        if (Auth::check() && Auth::user()->isApproved()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek user dulu untuk pesan error spesifik (pending/rejected)
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !\Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        if ($user->isPending()) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda masih menunggu persetujuan admin.',
            ]);
        }

        if ($user->isRejected()) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah ditolak. Hubungi admin.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Berhasil logout.');
    }
}
