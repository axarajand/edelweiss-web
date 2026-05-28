<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class HealthCheckController extends Controller
{
    /**
     * Cek status ML Service (FastAPI).
     * Coba beberapa endpoint umum: /health, /, dengan timeout pendek.
     * 
     * Response:
     * {
     *   "online": true|false,
     *   "url": "http://127.0.0.1:8001",
     *   "response_time_ms": 45,
     *   "message": "FastAPI online" / "Connection refused" / ...
     * }
     */
    public function mlService(): JsonResponse
    {
        $baseUrl = config('services.ml_api.url', 'http://127.0.0.1:8001');

        // Coba berbagai endpoint umum (urut prioritas)
        $endpoints = ['/health', '/', '/docs'];

        $startTime = microtime(true);

        foreach ($endpoints as $endpoint) {
            try {
                $response = Http::timeout(2)
                    ->connectTimeout(1)
                    ->get($baseUrl . $endpoint);

                if ($response->successful() || $response->status() < 500) {
                    $elapsed = round((microtime(true) - $startTime) * 1000);

                    return response()->json([
                        'online' => true,
                        'url' => $baseUrl,
                        'response_time_ms' => $elapsed,
                        'endpoint_used' => $endpoint,
                        'message' => 'ML Service aktif',
                    ]);
                }
            } catch (\Throwable $e) {
                // Coba endpoint selanjutnya
                continue;
            }
        }

        // Semua endpoint gagal
        return response()->json([
            'online' => false,
            'url' => $baseUrl,
            'response_time_ms' => null,
            'message' => 'ML Service tidak dapat dihubungi',
        ]);
    }
}
