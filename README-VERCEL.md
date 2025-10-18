# Deploy ke Vercel (SPK-SAW/MFEP + MySQL Aiven)

Dokumen ini menjelaskan cara menjalankan aplikasi di lokal dan deploy ke Vercel dengan database MySQL Aiven.

## 1) Struktur PHP di Vercel
Vercel mendukung PHP via runtime vercel-php (Serverless/Edge). Pola umum:
- Arahkan semua request ke `api/index.php` (router) lalu include aplikasi lama.
- Pastikan koneksi DB memakai environment variables.

Repo ini sudah menyesuaikan koneksi di `configurasi/koneksi.php` untuk membaca ENV.

## 2) Variabel Lingkungan (ENV)
Set variabel ini di Vercel Project Settings → Environment Variables:

- DB_HOST = companyinterior-fadhlirajwaarahmana-9486.i.aivencloud.com
- DB_PORT = 16722
- DB_USER = avnadmin
- DB_PASSWORD = <REDACTED>
- DB_NAME = spksaw
- DB_SSL_MODE = REQUIRED
- DB_SSL_VERIFY_SERVER = true
- DB_SSL_CA_BASE64 = (Base64 dari file ca.pem Aiven)

Cara membuat DB_SSL_CA_BASE64:
- Windows PowerShell:
  ```powershell
  [Convert]::ToBase64String([IO.File]::ReadAllBytes("C:\path\to\ca.pem"))
  ```
- Linux/Mac:
  ```bash
  base64 -w 0 ca.pem
  ```

Untuk lokal, salin `.env.example` menjadi `.env` dan isi sesuai kebutuhan.

## 3) File yang ditambahkan/diubah
- `configurasi/koneksi.php` (ENV-driven + SSL Aiven)
- `.env.example` (template lokal)
- `api/index.php` (router untuk Vercel)
- `vercel.json` (konfigurasi routing/deployment)
- `README-VERCEL.md` (panduan ini)

## 4) vercel.json (routing)
```
{
  "functions": {
    "api/index.php": {
      "runtime": "vercel-php@0.6.0"
    }
  },
  "routes": [
    { "src": "/(.*)", "dest": "/api/index.php" }
  ]
}
```

## 5) api/index.php (router)
Contoh minimal:
```php
<?php
// Router sederhana untuk mem-serve aplikasi lama dari folder "/"
// dan mengarahkan /administrator sesuai index.php lama

// Jalankan aplikasi seperti di hosting biasa
require_once __DIR__ . '/../index.php';
```

> Jika butuh URL rewriting lebih kompleks, bisa gunakan FastRoute atau custom dispatcher dan include file `administrator/index.php` langsung.

## 6) Build dependencies
Jika memakai Composer di Vercel, aktifkan "Install Command":
```
composer install --no-dev --prefer-dist --no-progress --no-interaction
```
Vercel akan meng-upload folder `vendor/` ke output build secara otomatis.

## 7) Jalankan di Lokal
- XAMPP Apache + PHP: arahkan DocumentRoot ke folder `spksaw/` atau akses `http://localhost/spksaw`.
- Buat file `.env` dari `.env.example`:
  ```
  DB_HOST=localhost
  DB_PORT=3306
  DB_USER=root
  DB_PASSWORD=
  DB_NAME=spksaw
  # Jika ingin langsung pakai Aiven dari lokal:
  # DB_HOST=companyinterior-fadhlirajwaarahmana-9486.i.aivencloud.com
  # DB_PORT=16722
  # DB_USER=avnadmin
  # DB_PASSWORD=***
  # DB_NAME=spksaw
  # DB_SSL_MODE=REQUIRED
  # DB_SSL_VERIFY_SERVER=true
  # DB_SSL_CA_BASE64=***
  ```

## 8) Tips Troubleshooting
- Error SSL: pastikan `DB_SSL_CA_BASE64` benar (CA dari Aiven) dan `DB_SSL_MODE=REQUIRED`.
- Error permission `CREATE DATABASE`: buat DB `spksaw` terlebih dahulu di Aiven, lalu import `spksaw-aiven.sql`.
- Error primary key: gunakan `spksaw-aiven.sql` (PK inline), bukan dump standar.
- Error include path di Vercel: pastikan `routes` mengarah ke `api/index.php` dan file `index.php` mem-forward ke `administrator` seperti biasa.

## 9) Import Database ke Aiven
- Gunakan file `spksaw-aiven.sql`.
- Workbench atau CLI dengan SSL CA (gunakan base64 untuk ENV pada produksi).

Selesai. Setelah ENV di Vercel di-set dan repo di-deploy, aplikasi akan menggunakan database Aiven baik di lokal (via .env) maupun di Vercel (via Project Settings).
