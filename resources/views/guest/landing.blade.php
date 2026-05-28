<x-layouts.guest title="Edelweiss Detection - Deteksi Kesehatan Bunga Edelweiss Jawa">

    {{-- ============================================================
         HERO SECTION
         ============================================================ --}}
    <section class="relative min-h-[600px] lg:min-h-[700px] flex items-center overflow-hidden">

        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1920&q=80"
                 alt="Pegunungan Indonesia"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/85 to-white/40 dark:from-slate-950/95 dark:via-slate-950/85 dark:to-slate-950/40"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
            <div class="max-w-2xl">

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight mb-6">
                    Deteksi Kesehatan
                    <span class="block bg-gradient-to-r from-emerald-600 to-green-700 dark:from-emerald-400 dark:to-green-500 bg-clip-text text-transparent">
                        Bunga Edelweiss Jawa
                    </span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed mb-8 max-w-xl">
                    Pantau kesehatan bunga Edelweiss dari foto. Sistem mengenali setiap bunga di gambar
                    lalu menentukan kondisi kesehatannya &mdash; cocok untuk pendaki, peneliti, dan
                    pemerhati tanaman endemik <em class="not-italic font-medium">Anaphalis javanica</em>.
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('guest.detection') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">
                        <x-icon name="scan" class="w-5 h-5" />
                        Cek Kesehatan Sekarang
                    </a>
                    <a href="#kondisi"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-medium border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Pelajari Kondisinya
                    </a>
                </div>

                <div class="mt-10 flex flex-wrap gap-6 items-center text-sm text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                        <span>Tanpa daftar</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                        <span>Hasil cepat</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                        <span>Gratis</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         KONDISI/LABEL SECTION
         ============================================================ --}}
    <section id="kondisi" class="py-16 lg:py-24 bg-slate-50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mx-auto text-center mb-12">
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                    Indikator Kesehatan
                </p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Kondisi Kesehatan yang Dapat Dideteksi
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    Sistem mengenali tiga kondisi kesehatan utama dari bunga Edelweiss Jawa.
                </p>
            </div>

            @php
                $kondisi = [
                    [
                        'nama' => 'Mekar',
                        'desc' => 'Bunga sehat dengan mahkota terbuka penuh. Menandakan tanaman tumbuh dengan baik.',
                    ],
                    [
                        'nama' => 'Sangat_Mekar',
                        'desc' => 'Bunga di puncak mekarnya. Tanda kondisi tanaman optimal dan lingkungan mendukung.',
                    ],
                    [
                        'nama' => 'Penyemaian',
                        'desc' => 'Fase awal pertumbuhan. Penting untuk regenerasi populasi Edelweiss di habitat aslinya.',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($kondisi as $i => $k)
                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 hover:shadow-md transition">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm font-bold text-slate-600 dark:text-slate-300">
                                {{ $i + 1 }}
                            </span>
                            <x-fase-badge :fase="$k['nama']" />
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ str_replace('_', ' ', $k['nama']) }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $k['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            <p class="mt-8 text-center text-xs text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                Sistem akan terus dikembangkan untuk mengenali lebih banyak indikator kesehatan Edelweiss ke depannya.
            </p>
        </div>
    </section>

    {{-- ============================================================
         CARA KERJA SECTION
         ============================================================ --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mx-auto text-center mb-12">
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                    Cara Pakai
                </p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Tiga Langkah Mudah
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    Tidak perlu pengetahuan teknis &mdash; siapa saja bisa cek kesehatan Edelweiss-nya.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

                <div class="relative">
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 mb-5">
                        <x-icon name="upload" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        1. Foto atau Upload
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Pilih foto Edelweiss dari galeri, atau gunakan kamera langsung untuk memotret di tempat.
                    </p>
                    <div class="hidden md:block absolute top-6 -right-4 lg:-right-8">
                        <svg class="w-8 h-2 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 32 8">
                            <path d="M0 4h28m-4-4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="relative">
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 mb-5">
                        <x-icon name="scan" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        2. Sistem Menganalisis
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Setiap bunga di gambar diidentifikasi, lalu ditentukan kondisi kesehatannya secara otomatis.
                    </p>
                    <div class="hidden md:block absolute top-6 -right-4 lg:-right-8">
                        <svg class="w-8 h-2 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 32 8">
                            <path d="M0 4h28m-4-4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 mb-5">
                        <x-icon name="check-circle" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        3. Lihat Hasil
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Hasil muncul dalam hitungan detik &mdash; lengkap dengan kotak penanda dan label kondisi setiap bunga.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         CTA SECTION
         ============================================================ --}}
    <section class="py-16 lg:py-24 bg-gradient-to-br from-emerald-600 to-green-700 dark:from-emerald-700 dark:to-green-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                Siap mengecek kesehatan Edelweiss Anda?
            </h2>
            <p class="text-base sm:text-lg text-emerald-50 mb-8 max-w-2xl mx-auto">
                Tidak perlu daftar atau bayar &mdash; coba langsung.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('guest.detection') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-lg bg-white text-emerald-700 font-semibold hover:bg-emerald-50 transition shadow-lg">
                    <x-icon name="scan" class="w-5 h-5" />
                    Mulai Deteksi
                </a>
                <a href="{{ route('admin.register') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-lg bg-emerald-700/40 text-white font-semibold border-2 border-white/30 hover:bg-emerald-700/60 transition backdrop-blur">
                    Daftar Akun
                </a>
            </div>
            <p class="mt-6 text-sm text-emerald-100">
                Punya akun? Anda dapat menyimpan riwayat deteksi dan melihat statistik kesehatan tanaman.
            </p>
        </div>
    </section>

</x-layouts.guest>
