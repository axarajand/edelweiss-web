import './bootstrap';
import Alpine from 'alpinejs';

import {
    Chart,
    LineController, LineElement, PointElement,
    BarController, BarElement,
    DoughnutController, ArcElement,
    CategoryScale, LinearScale,
    Tooltip, Legend, Filler,
} from 'chart.js';

Chart.register(
    LineController, LineElement, PointElement,
    BarController, BarElement,
    DoughnutController, ArcElement,
    CategoryScale, LinearScale,
    Tooltip, Legend, Filler,
);

Chart.defaults.font.family = "'Instrument Sans', system-ui, -apple-system, sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = 'rgba(148, 163, 184, 0.15)';
Chart.defaults.plugins.tooltip.padding = 10;
Chart.defaults.plugins.tooltip.boxPadding = 4;
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.padding = 16;

window.Chart = Chart;

import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

// =========================================================================
// PENTING: window.detectionPage HARUS didefinisikan SEBELUM Alpine.start()

    /**
     * Helper get translated string dari window.lang yang di-inject blade.
     * Fallback ke text Indonesia default kalau key tidak ada.
     */
    function trans(key, fallback) {
        return (window.lang && window.lang[key]) || fallback;
    }
// =========================================================================
window.detectionPage = function () {
    return {
        mode: 'upload',
        currentFile: null,
        previewUrl: null,
        results: [],
        isLoading: false,
        isDragging: false,
        cameraStream: null,
        cameraActive: false,
        cameraStatus: '',
        detectLoopActive: false,
        isCapturing: false,        // flag saat user klik "Potret"
        isFullscreen: false,       // flag saat kamera mode fullscreen
        FRAME_INTERVAL_MS: 800,

        get csrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        },

        _setFile(file) {
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                Alpine.store('toast').show(trans('file_invalid', 'File harus berupa gambar (.jpg, .png, atau .webp)'), 'warning');
                return;
            }
            const MAX_SIZE = 10 * 1024 * 1024;
            if (file.size > MAX_SIZE) {
                Alpine.store('toast').show(trans('file_too_big', 'Ukuran file terlalu besar. Maksimum 10MB.'), 'warning');
                return;
            }
            if (this.previewUrl) {
                URL.revokeObjectURL(this.previewUrl);
            }
            this.currentFile = file;
            this.previewUrl = URL.createObjectURL(file);
            this.results = [];
            this.$nextTick(() => {
                const canvas = document.getElementById('uploadCanvas');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                }
            });
        },

        handleFileSelect(event) {
            this._setFile(event.target.files[0]);
        },

        handleFileDrop(event) {
            this.isDragging = false;
            this._setFile(event.dataTransfer.files[0]);
        },

        async detectUpload() {
            if (!this.currentFile) return;
            this.isLoading = true;
            try {
                const formData = new FormData();
                formData.append('image', this.currentFile);
                formData.append('source', 'upload');
                const res = await fetch('/detect', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: formData,
                });
                const data = await res.json();
                if (data.success) {
                    this.results = data.detections || [];
                    this.$nextTick(() => {
                        const img = document.getElementById('previewImg');
                        const canvas = document.getElementById('uploadCanvas');
                        if (img.complete) {
                            this.drawDetections(canvas, this.results, img.naturalWidth, img.naturalHeight);
                        } else {
                            img.onload = () => this.drawDetections(canvas, this.results, img.naturalWidth, img.naturalHeight);
                        }
                    });
                } else {
                    // Gunakan handler khusus untuk error response dari backend
                    this.handleDetectionError(data);
                }
            } catch (err) {
                // Exception JavaScript (umumnya network error / fetch gagal)
                this.handleDetectionError({
                    error_type: 'network',
                    message: err.message,
                });
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Handler unified untuk error deteksi.
         * Pilih UI (toast vs modal) berdasarkan error_type dari backend.
         */
        handleDetectionError(data) {
            const errorType = data.error_type || 'unknown';
            const msg = data.message || trans('generic', 'Terjadi kesalahan saat mendeteksi.');

            // Modal untuk error FATAL yang perlu attention user
            if (errorType === 'service_offline') {
                Alpine.store('confirm').show({
                    title: trans('service_offline_title', 'Service Deteksi Belum Tersedia'),
                    message: trans('service_offline_message', 'Sistem belum dapat terhubung ke service deteksi saat ini. Silakan coba beberapa saat lagi, atau hubungi admin jika masalah berlanjut.'),
                    confirmText: trans('understand', 'Mengerti'),
                    cancelText: '',
                    variant: 'default',
                    onConfirm: () => {},
                });
                return;
            }

            // Toast untuk error retry-able (timeout, network, dll)
            if (errorType === 'timeout') {
                Alpine.store('toast').show(
                    trans('timeout', 'Proses deteksi lebih lama dari biasanya. Coba lagi dengan gambar resolusi lebih kecil.'),
                    'warning',
                    7000
                );
                return;
            }

            if (errorType === 'network') {
                Alpine.store('toast').show(
                    trans('network', 'Koneksi terputus. Periksa jaringan Anda lalu coba lagi.'),
                    'error',
                    6000
                );
                return;
            }

            // Default toast error
            Alpine.store('toast').show(msg, 'error', 5000);
        },

        /**
         * Toggle mode fullscreen kamera.
         * Kombinasi CSS fullscreen + native Fullscreen API.
         */
        toggleFullscreen() {
            if (this.isFullscreen) {
                this.exitFullscreen();
            } else {
                this.enterFullscreen();
            }
        },

        /**
         * Masuk mode fullscreen kamera.
         * - Tambah CSS class ke container kamera
         * - Lock scroll body
         * - Coba request native fullscreen (kalau didukung browser)
         */
        enterFullscreen() {
            const container = document.getElementById('camera-container');
            if (!container) return;

            // CSS fullscreen
            container.classList.add('camera-fullscreen');
            document.body.classList.add('has-fullscreen-camera');
            this.isFullscreen = true;

            // Coba request native fullscreen (untuk hide browser UI di mobile)
            try {
                if (container.requestFullscreen) {
                    container.requestFullscreen().catch(() => {
                        // Silent fail — CSS fullscreen sudah cukup
                    });
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
            } catch (e) {
                // Native fullscreen tidak didukung, lanjut dengan CSS fullscreen saja
            }
        },

        /**
         * Keluar dari mode fullscreen.
         */
        exitFullscreen() {
            const container = document.getElementById('camera-container');
            if (container) {
                container.classList.remove('camera-fullscreen');
            }
            document.body.classList.remove('has-fullscreen-camera');
            this.isFullscreen = false;

            // Exit native fullscreen kalau aktif
            try {
                if (document.fullscreenElement) {
                    document.exitFullscreen().catch(() => {});
                } else if (document.webkitFullscreenElement) {
                    document.webkitExitFullscreen();
                } else if (document.msFullscreenElement) {
                    document.msExitFullscreen();
                }
            } catch (e) {
                // Silent fail
            }
        },

        /**
         * Listener: kalau user keluar fullscreen via Esc/swipe,
         * sync state isFullscreen supaya UI ikut update.
         */
        _initFullscreenListener() {
            if (this._fullscreenListenerAdded) return;
            this._fullscreenListenerAdded = true;

            const handler = () => {
                const isNativeFS = !!(
                    document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.msFullscreenElement
                );
                // Kalau native fullscreen exit tapi state masih fullscreen,
                // berarti user pencet Esc — sync state.
                if (!isNativeFS && this.isFullscreen) {
                    const container = document.getElementById('camera-container');
                    if (container) {
                        container.classList.remove('camera-fullscreen');
                    }
                    document.body.classList.remove('has-fullscreen-camera');
                    this.isFullscreen = false;
                }
            };

            document.addEventListener('fullscreenchange', handler);
            document.addEventListener('webkitfullscreenchange', handler);
            document.addEventListener('msfullscreenchange', handler);
        },

        async startCamera() {
            this._initFullscreenListener();
            try {
                this.cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } },
                    audio: false,
                });
                const video = document.getElementById('video');
                video.srcObject = this.cameraStream;
                await video.play();
                this.cameraActive = true;
                this.detectLoopActive = true;
                this.cameraStatus = 'Mendeteksi...';
                this.detectLoop();
            } catch (err) {
                // Camera error - kasih pesan friendly berdasarkan error type
                let msg = trans('camera_generic', 'Tidak dapat mengakses kamera.');
                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                    msg = trans('camera_permission_denied', 'Izinkan akses kamera di browser, lalu coba lagi.');
                } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                    msg = trans('camera_not_found', 'Kamera tidak ditemukan pada perangkat ini.');
                } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                    msg = trans('camera_in_use', 'Kamera sedang digunakan oleh aplikasi lain.');
                }
                Alpine.store('toast').show(msg, 'error');
            }
        },

        stopCamera() {
            // Auto exit fullscreen saat camera distop
            if (this.isFullscreen) {
                this.exitFullscreen();
            }

            this.detectLoopActive = false;
            if (this.cameraStream) {
                this.cameraStream.getTracks().forEach(t => t.stop());
                this.cameraStream = null;
            }
            const video = document.getElementById('video');
            if (video) video.srcObject = null;
            const overlay = document.getElementById('overlay');
            if (overlay) overlay.getContext('2d').clearRect(0, 0, overlay.width, overlay.height);
            this.cameraActive = false;
            this.cameraStatus = 'Berhenti';
            this.results = [];
        },

        async detectLoop() {
            if (!this.detectLoopActive) return;
            const video = document.getElementById('video');
            if (!video || video.readyState < 2) {
                setTimeout(() => this.detectLoop(), 200);
                return;
            }
            const w = video.videoWidth, h = video.videoHeight;
            const off = document.createElement('canvas');
            off.width = w; off.height = h;
            off.getContext('2d').drawImage(video, 0, 0, w, h);

            off.toBlob(async (blob) => {
                if (!blob || !this.detectLoopActive) return;
                const formData = new FormData();
                formData.append('image', blob, 'frame.jpg');
                formData.append('source', 'camera');
                // NOTE: TIDAK append 'save' → backend skip save untuk frame realtime
                try {
                    const res = await fetch('/detect', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                        body: formData,
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.results = data.detections || [];
                        const overlay = document.getElementById('overlay');
                        this.drawDetections(overlay, this.results, w, h);
                        this.cameraStatus = `Mendeteksi... (${data.count} objek)`;
                    }
                } catch (err) {
                    console.error(err);
                    this.cameraStatus = 'Error koneksi ke ML service';
                } finally {
                    if (this.detectLoopActive) setTimeout(() => this.detectLoop(), this.FRAME_INTERVAL_MS);
                }
            }, 'image/jpeg', 0.85);
        },

        /**
         * NEW: Capture frame dari video → simpan ke server (source=camera, save=true).
         * Triggered saat user klik tombol "Potret" di camera mode.
         */
        async capturePhoto() {
            const video = document.getElementById('video');
            if (!video || video.readyState < 2 || !this.cameraActive) {
                Alpine.store('toast').show('Kamera belum siap.', 'error');
                return;
            }

            this.isCapturing = true;

            try {
                // Capture frame current
                const w = video.videoWidth;
                const h = video.videoHeight;
                const off = document.createElement('canvas');
                off.width = w;
                off.height = h;
                off.getContext('2d').drawImage(video, 0, 0, w, h);

                // Convert ke blob (quality lebih tinggi karena ini "official" save)
                const blob = await new Promise(resolve => off.toBlob(resolve, 'image/jpeg', 0.92));
                if (!blob) {
                    Alpine.store('toast').show('Gagal capture frame.', 'error');
                    return;
                }

                // Send dengan save=true
                const formData = new FormData();
                formData.append('image', blob, `camera_${Date.now()}.jpg`);
                formData.append('source', 'camera');
                formData.append('save', '1');

                const res = await fetch('/detect', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: formData,
                });

                const data = await res.json();

                if (data.success && data.saved) {
                    const count = data.count || 0;
                    const msg = count > 0
                        ? `Tersimpan ke riwayat #${data.detection_id} (${count} objek)`
                        : `Tersimpan ke riwayat #${data.detection_id}`;
                    Alpine.store('toast').show(msg, 'success');
                } else if (data.success) {
                    Alpine.store('toast').show('Deteksi berhasil tapi tidak tersimpan.', 'error');
                } else {
                    Alpine.store('toast').show(data.message || 'Gagal menyimpan.', 'error');
                }
            } catch (err) {
                console.error(err);
                Alpine.store('toast').show('Error: ' + err.message, 'error');
            } finally {
                this.isCapturing = false;
            }
        },

        getColorForLabel(label) {
            const colors = {
                'Mekar': '#f43f5e',
                'Sangat_Mekar': '#ec4899',
                'Penyemaian': '#059669',
                'Kuncup': '#84cc16',
                'Pematangan_Biji': '#eab308',
                'Biji_Matang': '#b45309',
                'Penyemaian_Baru': '#14b8a6',
            };
            return colors[label] || '#64748b';
        },

        drawDetections(canvas, detections, srcWidth, srcHeight) {
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            canvas.width = srcWidth;
            canvas.height = srcHeight;
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            detections.forEach(det => {
                const [x1, y1, x2, y2] = det.box;
                const color = this.getColorForLabel(det.label);
                ctx.strokeStyle = color;
                ctx.lineWidth = Math.max(2, srcWidth / 400);
                ctx.strokeRect(x1, y1, x2 - x1, y2 - y1);

                const displayLabel = (det.label || '').replace(/_/g, ' ');
                const conf = det.mlp_confidence ?? det.yolo_confidence ?? 0;
                const text = `${displayLabel} ${(conf * 100).toFixed(0)}%`;
                ctx.font = `${Math.max(14, srcWidth / 60)}px sans-serif`;
                const tw = ctx.measureText(text).width;
                const th = Math.max(18, srcWidth / 50);
                ctx.fillStyle = color;
                ctx.fillRect(x1, Math.max(0, y1 - th), tw + 10, th);
                ctx.fillStyle = '#fff';
                ctx.fillText(text, x1 + 5, Math.max(th - 4, y1 - 5));
            });
        },
    };
};

// =========================================================================
// Toast store — global, dipakai dari komponen manapun
// Pakai: Alpine.store('toast').show('Pesan', 'success' | 'error')
// =========================================================================
Alpine.store('toast', {
    visible: false,
    message: '',
    type: 'success',
    _timeout: null,

    show(message, type = 'success', duration = 4000) {
        this.message = message;
        this.type = type;
        this.visible = true;

        if (this._timeout) clearTimeout(this._timeout);
        this._timeout = setTimeout(() => {
            this.visible = false;
        }, duration);
    },

    hide() {
        this.visible = false;
        if (this._timeout) clearTimeout(this._timeout);
    },
});

// =========================================================================
// Confirm modal store — global modal konfirmasi dengan UI tema.
// Pakai:
//   Alpine.store('confirm').show({
//       title: 'Hapus?',
//       message: 'Tidak dapat dibatalkan.',
//       variant: 'danger',
//       confirmText: 'Hapus',
//       cancelText: 'Batal',
//       onConfirm: () => { /* lakukan aksi */ }
//   });
// =========================================================================
Alpine.store('confirm', {
    visible: false,
    title: '',
    message: '',
    confirmText: 'Konfirmasi',
    cancelText: 'Batal',
    variant: 'default',
    _onConfirm: null,

    show(options = {}) {
        this.title = options.title || 'Konfirmasi';
        this.message = options.message || 'Apakah Anda yakin?';
        this.confirmText = options.confirmText || 'Konfirmasi';
        this.cancelText = options.cancelText || 'Batal';
        this.variant = options.variant || 'default';
        this._onConfirm = options.onConfirm || null;

        this.visible = true;
        document.body.style.overflow = 'hidden';
    },

    confirm() {
        this.visible = false;
        document.body.style.overflow = '';

        if (typeof this._onConfirm === 'function') {
            setTimeout(() => {
                this._onConfirm();
                this._onConfirm = null;
            }, 100);
        }
    },

    cancel() {
        this.visible = false;
        document.body.style.overflow = '';
        this._onConfirm = null;
    },
});


// Theme & sidebar stores
Alpine.store('theme', {
    init() {
        this.dark = localStorage.getItem('theme') === 'dark'
            || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        this.apply();
    },
    dark: false,
    toggle() {
        this.dark = !this.dark;
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        this.apply();
    },
    apply() {
        document.documentElement.classList.toggle('dark', this.dark);
    }
});

Alpine.store('sidebar', {
    open: localStorage.getItem('sidebar') !== 'closed',
    toggle() {
        this.open = !this.open;
        localStorage.setItem('sidebar', this.open ? 'open' : 'closed');
    },
});

const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
}

window.Alpine = Alpine;
Alpine.start();
