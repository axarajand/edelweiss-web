# Edelweiss Detection

Sistem Cerdas Deteksi Kesehatan Bunga Edelweiss Jawa (Anaphalis javanica) Berdasarkan Citra Digital Menggunakan YOLOv11 dan Multi-Layer Perceptron.

Website: https://petaniconservation.tech

---

## Daftar Isi

1. [Tentang Project](#tentang-project)
2. [Fitur Utama](#fitur-utama)
3. [Arsitektur Sistem](#arsitektur-sistem)
4. [Stack Teknologi](#stack-teknologi)
5. [Dataset dan Model](#dataset-dan-model)
6. [Hasil Pelatihan Model](#hasil-pelatihan-model)
7. [Struktur Repository](#struktur-repository)
8. [Instalasi Lokal](#instalasi-lokal)
9. [Konfigurasi](#konfigurasi)
10. [Deployment Production](#deployment-production)
11. [Update dan Maintenance](#update-dan-maintenance)
12. [API Reference](#api-reference)
13. [Internasionalisasi](#internasionalisasi)
14. [Lisensi dan Kredit](#lisensi-dan-kredit)

---

## Tentang Project

Edelweiss Detection adalah sistem berbasis web yang dapat mendeteksi dan mengklasifikasikan kondisi kesehatan bunga Edelweiss Jawa secara otomatis dari gambar digital. Sistem ini dikembangkan sebagai skripsi tugas akhir dengan tujuan mendukung upaya konservasi tanaman endemik pegunungan Indonesia.

### Latar Belakang

Edelweiss Jawa (Anaphalis javanica) adalah tanaman endemik pegunungan Indonesia yang dilindungi. Pemantauan kondisi kesehatannya secara manual memerlukan pengetahuan khusus dan waktu yang lama. Sistem ini menjawab kebutuhan tersebut dengan menyediakan analisis otomatis berbasis kecerdasan buatan.

### Pengguna Target

- Pendaki gunung yang ingin mengenali kondisi Edelweiss di lapangan
- Peneliti yang membutuhkan data terstruktur tentang populasi Edelweiss
- Pemerhati konservasi dan pelestari tanaman endemik
- Akademisi yang melakukan studi vegetasi pegunungan

---

## Fitur Utama

### Untuk Pengunjung (Guest)

- Deteksi gambar tanpa registrasi (upload atau kamera realtime)
- Mode fullscreen kamera untuk inspeksi lapangan
- Tampilan bounding box dengan label kondisi
- Halaman edukasi tentang kondisi kesehatan Edelweiss
- Dukungan dua bahasa: Indonesia dan Inggris

### Untuk Admin

- Dashboard ringkasan statistik deteksi
- Riwayat deteksi dengan filter dan multi-select
- Detail deteksi dengan visualisasi bounding box
- Laporan periodik dengan ekspor PDF dan Excel
- Manajemen pengguna (approve, reject, edit role)
- Notifikasi email otomatis untuk approval flow

### Untuk Super Admin

- Semua fitur admin
- Manajemen role user (super_admin, admin, user)
- Akses penuh ke seluruh data sistem

---

## Arsitektur Sistem

```
Internet
   |
   v
petaniconservation.tech (DNS -> IP VPS)
   |
   v
Nginx (port 80/443, SSL Let's Encrypt)
   |
   +-- Laravel (PHP-FPM) ----- MySQL (db_edelweiss)
           |
           v
       ML Service (FastAPI, port 8001 internal)
           |
           v
       Model YOLO + MLP (PyTorch)
```

### Alur Deteksi

1. User upload gambar atau ambil dari kamera
2. Browser kirim ke Laravel via HTTP POST
3. Laravel forward gambar ke FastAPI internal (port 8001)
4. YOLOv11 deteksi posisi bunga (bounding box)
5. MLP klasifikasi kondisi kesehatan per bunga
6. FastAPI return hasil ke Laravel
7. Laravel simpan ke database, return ke browser
8. Browser render hasil dengan bounding box dan label

---

## Stack Teknologi

### Frontend
- **Blade** (Laravel templating)
- **Tailwind CSS v4** (styling)
- **Alpine.js** (interactivity)
- **Chart.js 4** (visualisasi data)
- **Vite** (asset bundling)

### Backend Web
- **Laravel 13** (PHP framework)
- **PHP 8.3** (runtime)
- **MySQL** (database)
- **Gmail SMTP** (email notifikasi)

### Machine Learning Service
- **FastAPI** (Python web framework)
- **PyTorch** (deep learning, CPU-only)
- **Ultralytics YOLOv11** (object detection)
- **Multi-Layer Perceptron** (classification)
- **Pillow** (image processing)

### Infrastructure
- **Ubuntu 24.04 LTS** (OS server)
- **Nginx** (reverse proxy + SSL)
- **PHP-FPM** (PHP process manager)
- **systemd** (service management)
- **Let's Encrypt** (SSL certificate)

### Tools
- **Composer** (PHP package manager)
- **npm** (Node package manager)
- **Git + GitHub** (version control)
- **Google Colab** (model training)
- **Roboflow** (image annotation)

---

## Dataset dan Model

### Dataset

- **Sumber:** Pengambilan mandiri di lapangan
- **Lokasi:** Gunung Gede Pangrango, Jawa Barat
- **Total Gambar:** 3.000 gambar
- **Split:** 80% latih (2.400), 10% validasi (300), 10% uji (300)
- **Tools Anotasi:** Roboflow

### Label Kondisi (3 kelas aktif)

| Label | Deskripsi |
|-------|-----------|
| Mekar | Bunga sehat dengan mahkota terbuka |
| Sangat_Mekar | Bunga di puncak mekarnya |
| Penyemaian | Fase awal pertumbuhan dari biji |

Arsitektur sistem disiapkan untuk ekspansi ke 7 label (Kuncup, Pematangan_Biji, Biji_Matang, Penyemaian_Baru) tanpa perubahan struktur kode.

### Arsitektur Model

**Two-Stage Detection:**

1. **Stage 1 - YOLOv11n** (object detection)
   - Input: 640x640 px
   - Output: bounding box + class probability

2. **Stage 2 - MLP** (classification)
   - Input: crop region dari bounding box
   - Hidden layers: 512 -> 256 -> 128
   - Output: probabilitas per kelas

Alasan pendekatan dua tahap: YOLO fokus pada deteksi posisi, MLP fokus pada klasifikasi kondisi. Lebih akurat dibanding single-stage untuk dataset kecil-menengah.

---

## Hasil Pelatihan Model

Evaluasi pada test set 300 gambar:

### YOLOv11

| Metrik | Nilai |
|--------|-------|
| mAP@0.5 | 96.04% |
| mAP@0.5:0.95 | 70.84% |
| Precision | 90.29% |
| Recall | 92.97% |

### Per-Class mAP@0.5

| Kelas | mAP@0.5 |
|-------|---------|
| Mekar | 92.0% |
| Penyemaian | 96.9% |
| Sangat_Mekar | 99.2% |

### MLP Classifier

| Metrik | Nilai |
|--------|-------|
| Validation Accuracy | 97.98% |
| Optimizer | AdamW |
| Loss Function | CrossEntropyLoss |
| Learning Rate | 0.001 |

### Tempat Pelatihan

- **Platform:** Google Colab
- **GPU:** NVIDIA T4 (16GB VRAM)
- **Framework:** PyTorch 2.x + Ultralytics
- **Training Time:** ~2 jam total (YOLO + MLP)

---

## Struktur Repository

Project ini terdiri dari **dua repository terpisah**:

### 1. edelweiss-web (Laravel)

```
edelweiss-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # DetectionController, PageController, dll
│   │   └── Middleware/       # Auth, SetLocale
│   ├── Models/               # User, Detection
│   └── Mail/                 # Notification classes
├── resources/
│   ├── views/                # Blade templates
│   │   ├── components/       # Reusable UI components
│   │   ├── layouts/          # App, Guest, Auth layouts
│   │   ├── pages/            # Admin pages
│   │   ├── guest/            # Guest pages
│   │   └── emails/           # Email templates
│   ├── css/                  # Tailwind source
│   ├── js/                   # Alpine.js logic
│   └── lang/                 # Translation files (id/en)
├── routes/
│   └── web.php               # All routes
├── database/
│   ├── migrations/           # Schema migrations
│   └── seeders/              # Initial data
├── public/                   # Document root (Nginx)
└── composer.json             # PHP dependencies
```

### 2. edelweiss-ml-service (Python)

```
edelweiss-ml-service/
├── app/
│   ├── main.py               # FastAPI entry point
│   ├── inference.py          # YOLO + MLP inference logic
│   ├── feature_extractor.py  # Feature extraction untuk MLP
│   └── mlp_model.py          # MLP architecture
├── models/                   # Model files (.pt, .pth) - NOT in Git
├── requirements.txt          # Python dependencies
└── README.md
```

---

## Instalasi Lokal

### Prasyarat

- PHP 8.3+
- Composer 2.x
- Node.js 20+
- MySQL 8+ atau MariaDB 10.6+
- Python 3.11+

### Setup Laravel (edelweiss-web)

```bash
# Clone repository
git clone https://github.com/USERNAME/edelweiss-web.git
cd edelweiss-web

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Edit .env: set DB_DATABASE, DB_USERNAME, DB_PASSWORD, ML_API_URL

# Setup database
php artisan migrate --seed
php artisan storage:link

# Build assets
npm run build

# Run server (development)
php artisan serve
# Buka http://localhost:8000
```

### Setup ML Service (edelweiss-ml-service)

```bash
# Clone repository
git clone https://github.com/USERNAME/edelweiss-ml-service.git
cd edelweiss-ml-service

# Buat virtual environment
python3.11 -m venv venv
source venv/bin/activate    # Windows: venv\Scripts\activate

# Install PyTorch CPU-only (lebih ringan)
pip install torch torchvision --index-url https://download.pytorch.org/whl/cpu

# Install dependencies lain
pip install -r requirements.txt

# Letakkan model files di folder models/
# - models/yolo_best.pt
# - models/mlp_classifier.pth

# Jalankan service
uvicorn app.main:app --host 127.0.0.1 --port 8001 --reload
```

### Test Sistem

1. Pastikan Laravel jalan di port 8000
2. Pastikan ML Service jalan di port 8001
3. Buka http://localhost:8000
4. Klik "Cek Kesehatan Sekarang"
5. Upload gambar Edelweiss
6. Hasil deteksi muncul dengan bounding box

---

## Konfigurasi

### File .env Penting

```env
# Application
APP_NAME="Edelweiss Detection"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://petaniconservation.tech
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_edelweiss
DB_USERNAME=edelweiss_user
DB_PASSWORD=

# ML Service
ML_API_URL=http://127.0.0.1:8001

# Mail (Gmail SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=                # Gmail App Password (16 karakter)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="Edelweiss Detection"

# Queue (gunakan 'sync' untuk simpel, atau 'database' dengan worker)
QUEUE_CONNECTION=sync

# Session
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true    # WAJIB true untuk HTTPS production
```

### Setup Gmail SMTP

1. Aktifkan 2-Step Verification di akun Gmail
2. Buka https://myaccount.google.com/apppasswords
3. Generate App Password untuk "Mail"
4. Copy password 16 karakter ke `MAIL_PASSWORD`

---

## Deployment Production

Stack production: **VPS Hostinger KVM 2 (Indonesia)** dengan Ubuntu 24.04.

### Spesifikasi Server

- 2 vCPU, 8GB RAM, 100GB NVMe
- Lokasi: Indonesia (Jakarta), latensi 16ms
- OS: Ubuntu 24.04 LTS

### Tahapan Deployment Singkat

1. **Setup VPS:** SSH, update apt, buat user non-root, enable firewall (UFW)
2. **Install Stack:** Nginx, PHP 8.3, MySQL, Python 3.11, Node.js 20, Composer
3. **Setup Database:** Buat `db_edelweiss` + user `edelweiss_user`
4. **Deploy Laravel:** Clone repo, composer install, .env, migrate, build
5. **Deploy ML Service:** Clone repo, venv, install PyTorch, upload model
6. **Systemd Service:** Buat `edelweiss-ml.service` untuk auto-restart
7. **Nginx + SSL:** Config Nginx, certbot Let's Encrypt
8. **Pointing Domain:** A record dari domain ke IP VPS

Panduan deployment lengkap tersedia di file terpisah `DEPLOYMENT.md`.

### File Nginx (referensi)

Lihat `scripts/nginx-edelweiss.conf` untuk konfigurasi Nginx production yang sudah dioptimalkan (max body 25M, fastcgi timeout 90s, cache static assets).

### File Systemd ML Service (referensi)

Lihat `scripts/edelweiss-ml.service` untuk auto-restart & memory limit FastAPI.

---

## Update dan Maintenance

### Update Code (Workflow Standard)

Karena project sudah pakai Git, update sangat mudah:

```bash
# Di laptop (development)
git add .
git commit -m "Deskripsi perubahan"
git push origin main

# Di VPS (production)
ssh saka@72.62.121.231
cd /var/www/edelweiss
bash update-laravel.sh
```

Script `update-laravel.sh` otomatis:
1. `git pull` dari GitHub
2. `composer install --optimize-autoloader --no-dev`
3. `npm install && npm run build`
4. `php artisan migrate --force`
5. Clear & rebuild cache
6. Reload PHP-FPM

### Update Model (3 ke 5 Label di Masa Depan)

Saat tambah label baru:

```bash
# 1. Train model baru di Google Colab dengan 5 label
# 2. Download yolo_best.pt dan mlp_classifier.pth

# 3. Upload ke VPS (dari laptop)
scp yolo_best.pt saka@72.62.121.231:/var/www/ml-service/models/
scp mlp_classifier.pth saka@72.62.121.231:/var/www/ml-service/models/

# 4. Update code yang menyesuaikan label (di laptop)
#    File yang biasanya perlu diubah:
#    - resources/views/guest/landing.blade.php (array kondisi)
#    - resources/views/pages/learning.blade.php (array kondisi)
#    - app/Http/Controllers/PageController.php (countByClass)
#    - lang/id/*.php dan lang/en/*.php (terjemahan label baru)

# 5. Push & restart
git push origin main

# Di VPS:
ssh saka@72.62.121.231
cd /var/www/edelweiss
bash update-laravel.sh
sudo systemctl restart edelweiss-ml
```

### Backup Database

```bash
# Backup manual
sudo mysqldump -u edelweiss_user -p db_edelweiss > backup_$(date +%F).sql

# Cek size backup
ls -lh backup_*.sql
```

### Monitor Log

```bash
# Laravel log
sudo tail -f /var/www/edelweiss/storage/logs/laravel.log

# ML Service log
sudo journalctl -u edelweiss-ml -f

# Nginx access log
sudo tail -f /var/log/nginx/edelweiss-access.log

# Nginx error log
sudo tail -f /var/log/nginx/edelweiss-error.log
```

### Restart Services

```bash
# Restart Laravel (reload PHP-FPM)
sudo systemctl reload php8.3-fpm

# Restart ML Service
sudo systemctl restart edelweiss-ml

# Restart Nginx
sudo systemctl reload nginx

# Cek status
sudo systemctl status nginx php8.3-fpm mysql edelweiss-ml
```

---

## API Reference

### Endpoint Laravel Public

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/` | Landing page |
| GET | `/deteksi` | Halaman deteksi guest |
| POST | `/detect` | Endpoint deteksi (form-data: image) |
| GET | `/pembelajaran` | Halaman belajar |
| POST | `/locale/{lang}` | Switch bahasa (id/en) |

### Endpoint Laravel Admin (memerlukan auth)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/admin` | Dashboard |
| GET | `/admin/deteksi` | Halaman deteksi admin |
| GET | `/admin/riwayat` | Riwayat deteksi |
| GET | `/admin/riwayat/{id}` | Detail deteksi |
| GET | `/admin/laporan` | Laporan periodik |
| GET | `/admin/laporan/export/pdf` | Ekspor PDF |
| GET | `/admin/laporan/export/excel` | Ekspor Excel |
| GET | `/admin/pembelajaran` | Halaman belajar |
| GET | `/admin/users` | Manajemen user |

### Endpoint ML Service (FastAPI Internal)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/health` | Health check |
| POST | `/predict` | Predict from image (form-data: file) |

#### Format Response /predict

```json
{
  "success": true,
  "count": 2,
  "detections": [
    {
      "box": [x1, y1, x2, y2],
      "label": "Mekar",
      "yolo_confidence": 0.95,
      "mlp_confidence": 0.87
    }
  ],
  "image_size": {
    "width": 1024,
    "height": 768
  }
}
```

---

## Internasionalisasi

Sistem mendukung **dua bahasa**:
- Indonesia (default)
- English

### Cara Switch Bahasa

1. Di header (nav), klik tombol bahasa (ID atau EN)
2. Pilih bahasa dari dropdown
3. Seluruh website langsung berubah bahasa
4. Preferensi tersimpan di session

### Tambah Bahasa Baru

1. Copy folder `resources/lang/id/` ke `resources/lang/[kode]/`
2. Terjemahkan semua file di folder baru
3. Edit `config/app.php`, tambah kode bahasa di `supported_locales`
4. Edit `resources/views/components/language-switcher.blade.php`, tambah entry di array `$languages`

### Struktur File Lang

```
lang/
├── id/                  # Bahasa Indonesia
│   ├── messages.php     # Common (nav, action, label, status)
│   ├── landing.php      # Landing page
│   ├── detection.php    # Halaman deteksi
│   ├── dashboard.php    # Dashboard admin
│   ├── history.php      # Riwayat
│   ├── reports.php      # Laporan
│   ├── learning.php     # Halaman belajar
│   ├── auth.php         # Login, register
│   ├── users.php        # Manajemen user
│   └── emails.php       # Email templates
└── en/                  # English (struktur sama)
    └── ...
```

---

## Troubleshooting

### Website tampil 500 Internal Server Error

```bash
# Cek log Laravel
sudo tail -50 /var/www/edelweiss/storage/logs/laravel.log

# Common fixes:
cd /var/www/edelweiss
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan view:clear
sudo php artisan config:cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo systemctl reload php8.3-fpm
```

### Deteksi error "Service tidak tersedia"

```bash
# Cek ML Service
sudo systemctl status edelweiss-ml

# Restart kalau perlu
sudo systemctl restart edelweiss-ml

# Cek log
sudo journalctl -u edelweiss-ml -n 50
```

### Email tidak terkirim

1. Pastikan `MAIL_PASSWORD` adalah **App Password Gmail 16 karakter**, bukan password Gmail biasa
2. Cek port 587 tidak diblokir VPS:
   ```bash
   timeout 5 bash -c "</dev/tcp/smtp.gmail.com/587" && echo OK || echo BLOCKED
   ```
3. Test kirim email via tinker:
   ```bash
   sudo php artisan tinker
   Mail::raw('Test', fn($m) => $m->to('test@email.com')->subject('Test'));
   ```

### Database connection refused

```bash
# Cek MySQL jalan
sudo systemctl status mysql

# Test koneksi
sudo mysql -u edelweiss_user -p db_edelweiss
```

---

## Lisensi dan Kredit

### Pengembang

- **Nama:** Axa Rajandrya
- **Email:** axa.rajandrya_ti22@nusaputra.ac.id
- **Institusi:** Universitas Nusa Putra
- **Program Studi:** Teknik Informatika

### Judul Skripsi

"Sistem Cerdas Deteksi Kesehatan Bunga Edelweis Berdasarkan Citra Digital Menggunakan YOLOv11 dan Multi-Layer Perceptron"

### Tools dan Library Open-Source

- Laravel (https://laravel.com) - MIT License
- FastAPI (https://fastapi.tiangolo.com) - MIT License
- PyTorch (https://pytorch.org) - BSD License
- Ultralytics YOLOv11 (https://github.com/ultralytics/ultralytics) - AGPL-3.0
- Tailwind CSS (https://tailwindcss.com) - MIT License
- Alpine.js (https://alpinejs.dev) - MIT License
- Chart.js (https://www.chartjs.org) - MIT License

### Dataset

Dataset Edelweiss dikumpulkan secara mandiri di Gunung Gede Pangrango. Anotasi menggunakan Roboflow. Hak cipta dataset milik pengembang.

### Kontribusi

Project ini adalah hasil skripsi tugas akhir. Untuk diskusi atau kolaborasi, silakan hubungi pengembang melalui email di atas.

---

## Status Project

- **Versi:** 1.0.0
- **Status:** Production (Live)
- **URL Production:** https://petaniconservation.tech
- **Tanggal Rilis:** Mei 2026
- **Update Terakhir:** Juni 2026

---

## Roadmap Pengembangan

### Versi 1.x (Current)
- Deteksi 3 kondisi: Mekar, Sangat Mekar, Penyemaian
- Dukungan bahasa Indonesia dan Inggris
- Mode kamera fullscreen
- Ekspor laporan PDF dan Excel

### Versi 2.0 (Rencana)
- Ekspansi ke 5 atau 7 kondisi (Kuncup, Pematangan Biji, Biji Matang, Penyemaian Baru)
- API publik untuk integrasi pihak ketiga
- Mobile app native (iOS / Android)
- Peta sebaran deteksi (geo-tagging)

### Versi 3.0 (Long-term)
- Multi-project platform (Kopi, Pengawasan Digital)
- AI training dashboard untuk admin
- Public dataset contribution
