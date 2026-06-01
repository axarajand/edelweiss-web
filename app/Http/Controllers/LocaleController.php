<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch app locale via session.
     * Endpoint: POST /locale/{lang}
     *
     * Available languages: id, en
     */
    public function switch(Request $request, string $lang): RedirectResponse
    {
        $supportedLocales = config('app.supported_locales', ['id', 'en']);

        if (in_array($lang, $supportedLocales)) {
            $request->session()->put('locale', $lang);
        }

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back();
    }
}
