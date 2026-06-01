<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Set app locale dari session.
     * Locale default: 'id' (Bahasa Indonesia).
     * Locale tersedia: 'id', 'en'
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['id', 'en']);

        $locale = $request->session()->get('locale', config('app.locale', 'id'));

        // Fallback ke default kalau locale tidak didukung
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale', 'id');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
