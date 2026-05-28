<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DetectionController extends Controller
{
    public function detect(Request $request): JsonResponse
    {
        // Naikkan max execution time PHP ke 90 detik (default 30s)
        // supaya proses deteksi gambar resolusi tinggi atau banyak objek tidak timeout.
        set_time_limit(90);

        $request->validate([
            'image' => 'required|file|image|max:10240',
            'source' => 'sometimes|in:upload,camera',
            'save' => 'sometimes|boolean',
        ]);

        $file = $request->file('image');
        $apiUrl = config('services.ml_api.url') . '/predict';

        try {
            $response = Http::timeout(60)
                ->connectTimeout(5)
                ->attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )
                ->post($apiUrl);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Service tidak bisa dihubungi (offline / port salah / firewall)
            return response()->json([
                'success' => false,
                'error_type' => 'service_offline',
                'message' => 'Service deteksi sedang tidak tersedia. Silakan coba beberapa saat lagi.',
            ], 503);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Request timeout atau response error
            $isTimeout = str_contains(strtolower($e->getMessage()), 'timeout') ||
                         str_contains(strtolower($e->getMessage()), 'timed out');
            return response()->json([
                'success' => false,
                'error_type' => $isTimeout ? 'timeout' : 'request_failed',
                'message' => $isTimeout
                    ? 'Proses deteksi memakan waktu lebih lama dari biasanya. Coba unggah ulang gambar.'
                    : 'Terjadi kendala saat memproses gambar. Silakan coba lagi.',
            ], 502);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error_type' => 'unknown',
                'message' => 'Terjadi kesalahan saat mendeteksi. Silakan coba lagi.',
            ], 500);
        }

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'ML service error',
                'detail' => $response->body(),
            ], $response->status());
        }

        $data = $response->json();
        $source = $request->input('source', 'upload');

        // === Logic save ===
        // - source=upload         → SELALU save (semua upload disimpan)
        // - source=camera + save  → save (user explicit click "Potret")
        // - source=camera no save → SKIP save (frame auto-detect realtime)
        $shouldSave = ($source === 'upload')
            || ($source === 'camera' && $request->boolean('save'));

        $savedDetection = null;

        if ($shouldSave) {
            $isGuest = !Auth::check();
            $imagePath = $this->storeImage($file);

            $savedDetection = Detection::create([
                'user_id' => Auth::id(),
                'is_guest' => $isGuest,
                'guest_ip' => $isGuest ? $request->ip() : null,
                'source' => $source,
                'object_count' => $data['count'] ?? 0,
                'result' => $data,
                'image_path' => $imagePath,
            ]);
        }

        // Tambahkan flag saved + id ke response untuk frontend (untuk toast)
        $data['saved'] = $savedDetection !== null;
        $data['detection_id'] = $savedDetection?->id;

        return response()->json($data);
    }

    /**
     * Simpan file gambar ke storage/app/public/detections/{tahun}/{bulan}/.
     */
    private function storeImage($file): string
    {
        $year = date('Y');
        $month = date('m');
        $folder = "detections/{$year}/{$month}";

        $extension = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $filename = time() . '_' . Str::random(8) . '.' . $extension;

        return $file->storeAs($folder, $filename, 'public');
    }
}
