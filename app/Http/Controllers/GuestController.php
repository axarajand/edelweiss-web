<?php

namespace App\Http\Controllers;

use App\Models\Researcher;
use App\Models\Partner;
use App\Models\Gallery;

class GuestController extends Controller
{
    public function landing()
    {
        $researchers = Researcher::active()->orderBy('sort_order')->orderBy('name')->get();
        $partners    = Partner::active()->orderBy('sort_order')->orderBy('name')->limit(6)->get();
        $galleries   = Gallery::published()->orderByDesc('created_at')->limit(6)->get();

        return view('guest.landing', compact('researchers', 'partners', 'galleries'));
    }

    public function detection()
    {
        return view('guest.detection');
    }

    public function research()
    {
        $researchers = Researcher::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('guest.research', compact('researchers'));
    }

    public function partners()
    {
        $partners = Partner::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('guest.partners', compact('partners'));
    }

    public function gallery()
    {
        $galleries = Gallery::published()->orderByDesc('created_at')->get();
        return view('guest.gallery', compact('galleries'));
    }
}
