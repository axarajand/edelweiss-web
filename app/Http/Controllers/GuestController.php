<?php

namespace App\Http\Controllers;

class GuestController extends Controller
{
    /**
     * Landing page utama (halaman publik di /).
     */
    public function landing()
    {
        return view('guest.landing');
    }

    /**
     * Halaman deteksi untuk pengguna tanpa login.
     */
    public function detection()
    {
        return view('guest.detection');
    }
}
