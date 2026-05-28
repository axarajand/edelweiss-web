<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Pastikan user sudah login DAN status approved.
     * Pending/rejected user akan di-logout otomatis.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (!$user->isApproved()) {
            $message = match ($user->status) {
                'pending' => 'Akun Anda masih menunggu persetujuan admin.',
                'rejected' => 'Akun Anda telah ditolak. Hubungi admin.',
                default => 'Akun Anda tidak dapat mengakses panel.',
            };

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('error', $message);
        }

        return $next($request);
    }
}
