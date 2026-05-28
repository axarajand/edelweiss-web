<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edelweiss Detection Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <header class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
            🌼 Edelweiss Detection
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Sistem cerdas deteksi fase pertumbuhan Anaphalis javanica (YOLOv11 + MLP)
        </p>
    </header>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-100">
            <p class="text-xs uppercase tracking-wider text-slate-500">Total Deteksi</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalDetections }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-100">
            <p class="text-xs uppercase tracking-wider text-slate-500">Total Objek Terdeteksi</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalObjects }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl shadow-sm p-5 border border-emerald-100">
            <p class="text-xs uppercase tracking-wider text-emerald-700">Mekar</p>
            <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $byClass['Mekar'] }}</p>
        </div>
        <div class="bg-rose-50 rounded-xl shadow-sm p-5 border border-rose-100">
            <p class="text-xs uppercase tracking-wider text-rose-700">Sangat Mekar</p>
            <p class="mt-2 text-3xl font-bold text-rose-700">{{ $byClass['Sangat Mekar'] }}</p>
        </div>
    </section>

    <div class="flex gap-2 mb-4">
        <button data-tab="upload"
                class="tab-btn px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium">
            📁 Upload Gambar
        </button>
        <button data-tab="camera"
                class="tab-btn px-4 py-2 rounded-lg bg-white text-slate-700 text-sm font-medium border border-slate-200">
            📷 Kamera Real-time
        </button>
    </div>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div id="panel-upload" class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-slate-100">
            <h2 class="font-semibold text-slate-900 mb-3">Upload Gambar</h2>
            <input id="fileInput" type="file" accept="image/*"
                   class="block w-full text-sm text-slate-600
                          file:mr-4 file:py-2 file:px-4 file:rounded-lg
                          file:border-0 file:text-sm file:font-medium
                          file:bg-slate-900 file:text-white hover:file:bg-slate-700"/>
            <div class="mt-4 relative bg-slate-100 rounded-lg overflow-hidden aspect-video">
                <img id="previewImg" class="w-full h-full object-contain hidden">
                <canvas id="uploadCanvas" class="absolute inset-0 w-full h-full"></canvas>
                <p id="uploadHint" class="absolute inset-0 flex items-center justify-center text-slate-400 text-sm">
                    Pilih gambar untuk mulai deteksi
                </p>
            </div>
            <button id="detectBtn" disabled
                    class="mt-4 px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-medium
                           hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed">
                Deteksi Sekarang
            </button>
        </div>

        <div id="panel-camera" class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-slate-100 hidden">
            <h2 class="font-semibold text-slate-900 mb-3">Kamera Real-time</h2>
            <div class="relative bg-black rounded-lg overflow-hidden aspect-video">
                <video id="video" class="w-full h-full object-contain" autoplay playsinline muted></video>
                <canvas id="overlay" class="absolute inset-0 w-full h-full"></canvas>
            </div>
            <div class="mt-4 flex gap-2">
                <button id="startCam"
                        class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700">
                    Mulai Kamera
                </button>
                <button id="stopCam" disabled
                        class="px-5 py-2.5 rounded-lg bg-rose-600 text-white font-medium
                               hover:bg-rose-700 disabled:bg-slate-300 disabled:cursor-not-allowed">
                    Stop
                </button>
                <span id="camStatus" class="ml-auto self-center text-sm text-slate-500"></span>
            </div>
        </div>

        <aside class="bg-white rounded-xl shadow-sm p-6 border border-slate-100">
            <h2 class="font-semibold text-slate-900 mb-3">Hasil Deteksi</h2>
            <div id="resultList" class="space-y-2 text-sm text-slate-600">
                <p class="text-slate-400">Belum ada hasil.</p>
            </div>
        </aside>
    </section>

    <section class="mt-8 bg-white rounded-xl shadow-sm p-6 border border-slate-100">
        <h2 class="font-semibold text-slate-900 mb-3">Riwayat Deteksi Terakhir</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2">Waktu</th>
                        <th class="py-2">Sumber</th>
                        <th class="py-2">Jumlah Objek</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent as $r)
                        <tr class="border-b border-slate-100">
                            <td class="py-2">{{ $r->created_at->format('d M Y H:i') }}</td>
                            <td class="py-2">{{ $r->source }}</td>
                            <td class="py-2">{{ $r->object_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-slate-400">Belum ada riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

</body>
</html>