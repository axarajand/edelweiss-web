<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Researcher;
use App\Models\Partner;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    // ================================================================
    // MAIN PAGE (3 tabs)
    // ================================================================

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'research');

        $researchers = Researcher::orderBy('sort_order')->orderBy('name')->get();
        $partners    = Partner::orderBy('sort_order')->orderBy('name')->get();
        $galleries   = Gallery::orderByDesc('created_at')->get();

        return view('pages.content', compact('tab', 'researchers', 'partners', 'galleries'));
    }

    // ================================================================
    // RESEARCHER CRUD (Tim Peneliti)
    // ================================================================

    public function storeResearcher(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'role'        => 'nullable|string|max:255',
            'affiliation' => 'nullable|string|max:255',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'scholar_url' => 'nullable|url|max:500',
            'sort_order'  => 'integer',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $this->storeSquarePhoto($request->file('photo'), 'researchers');
        }
        unset($data['photo']);
        $data['created_by'] = Auth::id();
        $data['is_active'] = $request->boolean('is_active', true);

        Researcher::create($data);

        return back()->with('success', 'Peneliti berhasil ditambahkan.');
    }

    public function updateResearcher(Request $request, Researcher $researcher)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'role'        => 'nullable|string|max:255',
            'affiliation' => 'nullable|string|max:255',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'scholar_url' => 'nullable|url|max:500',
            'sort_order'  => 'integer',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($researcher->photo_path) {
                Storage::disk('public')->delete($researcher->photo_path);
            }
            $data['photo_path'] = $this->storeSquarePhoto($request->file('photo'), 'researchers');
        }
        unset($data['photo']);
        $data['is_active'] = $request->boolean('is_active', true);
        $researcher->update($data);

        return back()->with('success', 'Peneliti berhasil diperbarui.');
    }

    public function destroyResearcher(Researcher $researcher)
    {
        if ($researcher->photo_path) {
            Storage::disk('public')->delete($researcher->photo_path);
        }
        $researcher->delete();
        return back()->with('success', 'Peneliti berhasil dihapus.');
    }

    /**
     * Simpan foto persegi (square crop) untuk foto peneliti. Distandarkan 512x512.
     */
    private function storeSquarePhoto($file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.jpg';
        $path = $folder . '/' . $filename;

        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        if ($image) {
            $w = imagesx($image);
            $h = imagesy($image);
            $side = min($w, $h);
            $srcX = (int)(($w - $side) / 2);
            $srcY = (int)(($h - $side) / 2);
            $size = 512;
            $square = imagecreatetruecolor($size, $size);
            imagefill($square, 0, 0, imagecolorallocate($square, 255, 255, 255));
            imagecopyresampled($square, $image, 0, 0, $srcX, $srcY, $size, $size, $side, $side);
            ob_start();
            imagejpeg($square, null, 85);
            $data = ob_get_clean();
            Storage::disk('public')->put($path, $data);
            imagedestroy($image);
            imagedestroy($square);
        } else {
            $path = $file->store($folder, 'public');
        }

        return $path;
    }

    // ================================================================
    // PARTNER CRUD
    // ================================================================

    public function storePartner(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'website'     => 'nullable|url|max:500',
            'category'    => 'required|in:institution,ngo,government,university',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->storePartnerLogo($request->file('logo'));
        }

        unset($data['logo']);
        $data['created_by'] = Auth::id();
        $data['is_active'] = $request->boolean('is_active', true);

        Partner::create($data);

        return back()->with('success', 'Partner berhasil ditambahkan.');
    }

    public function updatePartner(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'website'     => 'nullable|url|max:500',
            'category'    => 'required|in:institution,ngo,government,university',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo_path) {
                Storage::disk('public')->delete($partner->logo_path);
            }
            $data['logo_path'] = $this->storePartnerLogo($request->file('logo'));
        }

        unset($data['logo']);
        $data['is_active'] = $request->boolean('is_active', true);
        $partner->update($data);

        return back()->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroyPartner(Partner $partner)
    {
        if ($partner->logo_path) {
            Storage::disk('public')->delete($partner->logo_path);
        }
        $partner->delete();
        return back()->with('success', 'Partner berhasil dihapus.');
    }

    private function storePartnerLogo($file): string
    {
        $filename = time() . '_' . uniqid() . '.jpg';
        $path = 'partners/' . $filename;

        // Compress & standardize logo using GD
        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        if ($image) {
            $w = imagesx($image);
            $h = imagesy($image);
            $size = 400;
            $ratio = min($size / $w, $size / $h);
            $nw = (int)($w * $ratio);
            $nh = (int)($h * $ratio);
            $resized = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $nw, $nh, $w, $h);
            ob_start();
            imagejpeg($resized, null, 85);
            $data = ob_get_clean();
            Storage::disk('public')->put($path, $data);
            imagedestroy($image);
            imagedestroy($resized);
        } else {
            $path = $file->store('partners', 'public');
        }

        return $path;
    }

    // ================================================================
    // GALLERY CRUD
    // ================================================================

    public function storeGallery(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'location'     => 'nullable|string|max:255',
            'taken_at'     => 'nullable|date',
            'category'     => 'required|in:field,lab,event,other',
            'is_published' => 'boolean',
            'sort_order'   => 'integer',
        ]);

        $data['image_path'] = $this->storeGalleryImage($request->file('image'));
        unset($data['image']);
        $data['created_by'] = Auth::id();
        $data['is_published'] = $request->boolean('is_published', true);

        Gallery::create($data);

        return back()->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function updateGallery(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'location'     => 'nullable|string|max:255',
            'taken_at'     => 'nullable|date',
            'category'     => 'required|in:field,lab,event,other',
            'is_published' => 'boolean',
            'sort_order'   => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $data['image_path'] = $this->storeGalleryImage($request->file('image'));
        }

        unset($data['image']);
        $data['is_published'] = $request->boolean('is_published', true);
        $gallery->update($data);

        return back()->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroyGallery(Gallery $gallery)
    {
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();
        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }

    /**
     * Standardize gallery image: resize to max 1280px wide, convert to JPEG, compress.
     * Accepts 1KB - 10MB input.
     */
    private function storeGalleryImage($file): string
    {
        $filename = time() . '_' . uniqid() . '.jpg';
        $path = 'galleries/' . date('Y/m') . '/' . $filename;

        $raw = file_get_contents($file->getRealPath());
        $image = imagecreatefromstring($raw);

        if ($image) {
            $w = imagesx($image);
            $h = imagesy($image);
            $maxW = 1280;
            $maxH = 960;

            if ($w > $maxW || $h > $maxH) {
                $ratio = min($maxW / $w, $maxH / $h);
                $nw = (int)($w * $ratio);
                $nh = (int)($h * $ratio);
                $resized = imagecreatetruecolor($nw, $nh);
                // Preserve white background for PNG
                imagefill($resized, 0, 0, imagecolorallocate($resized, 255, 255, 255));
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $nw, $nh, $w, $h);
                imagedestroy($image);
                $image = $resized;
            }

            ob_start();
            imagejpeg($image, null, 82);
            $data = ob_get_clean();
            Storage::disk('public')->put($path, $data);
            imagedestroy($image);
        } else {
            // Fallback: store as-is
            $path = $file->store('galleries/' . date('Y/m'), 'public');
        }

        return $path;
    }
}
