# 🎻 Sonata Violin Course Management System

<div align="center">

![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat-square&logo=php)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-EF4223?style=flat-square&logo=codeigniter)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=flat-square&logo=tailwind-css)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql)

Sistem manajemen kursus musik khusus biola yang dirancang untuk mengelola pendaftaran siswa, penjadwalan kelas, hingga pelaporan progres secara efisien dan terintegrasi.

[Features](#-fitur-utama) • [Tech Stack](#️-tech-stack) • [Installation](#-instalasi) • [User Roles](#-role--akses-pengguna)

</div>

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#️-tech-stack)
- [Arsitektur Sistem](#-arsitektur-sistem)
- [Role & Akses Pengguna](#-role--akses-pengguna)
- [Instalasi](#-instalasi)
- [Konfigurasi](#️-konfigurasi)
- [Penggunaan](#-penggunaan)
- [Keamanan](#-keamanan)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)
- [Kontak](#-kontak)

---

## 🎯 Tentang Proyek

**Sonata Violin** adalah sistem manajemen kursus musik berbasis web yang dibangun khusus untuk mengelola operasional kursus biola secara komprehensif. Sistem ini menggabungkan kemudahan pendaftaran mandiri untuk calon siswa dengan dashboard manajemen yang powerful untuk pengelola kursus.

Dibangun dengan prinsip **Clean Code** dan mengadopsi **Role-Based Access Control (RBAC)** untuk keamanan maksimal, Sonata Violin menyediakan solusi end-to-end mulai dari pendaftaran, pembayaran, penjadwalan, hingga tracking progress siswa.

---

## 🚀 Fitur Utama

### 1. 🌐 Pendaftaran Mandiri (Public SPA Page)

Calon siswa dapat mendaftar **tanpa perlu login** melalui halaman Single Page Application (SPA) yang user-friendly:

- ✅ **Formulir Pendaftaran Interaktif** - Interface yang intuitif dan mudah digunakan
- 💳 **Sistem Paket Kursus** - Pilihan paket yang transparan dengan harga dan durasi jelas
- 💰 **Pay-at-Front System** - Pembayaran sekali di awal, tidak ada biaya tambahan
- 📤 **Upload Bukti Transfer** - Wajib upload bukti pembayaran di akhir formulir
- 🔔 **Notifikasi Real-time** - Status pendaftaran langsung terkirim

### 2. 📊 Manajemen Data Master

Sistem pengelolaan data master yang lengkap dan terstruktur:

#### Master Instruktur
- Database pengajar profesional
- Tracking spesialisasi dan ketersediaan instruktur
- Riwayat kelas yang diampu

#### Master Ruangan
- Pengelolaan ketersediaan ruang kelas
- Kapasitas dan fasilitas ruangan
- Status penggunaan real-time

#### Master Siswa
- Database siswa aktif dan alumni
- Profil lengkap dan kontak darurat
- Riwayat kursus dan pencapaian

#### Master Paket
- Pengaturan variasi paket kursus
- Flexible pricing dan durasi
- Deskripsi lengkap benefit tiap paket

### 3. 🔄 Modul Transaksi & Operasional

Sistem operasional yang terintegrasi dan efisien:

#### 💵 Pendaftaran
- Formulir pendaftaran komprehensif
- Validasi data otomatis
- Queue system untuk pemrosesan

#### ✅ Verifikasi Pembayaran
- Dashboard verifikasi untuk admin/operator
- Preview bukti transfer
- Approve/reject dengan alasan
- Notifikasi otomatis ke siswa

#### 📅 Smart Scheduling (Jadwal Kelas)
- **Conflict Prevention System** - Sistem deteksi bentrok otomatis
- Validasi ketersediaan instruktur, ruangan, dan waktu
- Sistem menolak otomatis jika jam, hari, dan tanggal sudah digunakan
- Visual calendar untuk kemudahan scheduling
- Filter berdasarkan instruktur/ruangan/siswa

#### 📝 Absensi
- Pencatatan kehadiran per pertemuan
- Status: Hadir, Izin, Sakit, Alfa
- Catatan tambahan untuk setiap pertemuan
- Statistik kehadiran siswa

#### 📈 Progress Kursus
- Tracking perkembangan kemampuan siswa
- Evaluasi per pertemuan
- Milestone dan achievement
- Grafik progress visual

#### 📊 Laporan
- Rekapitulasi pendaftaran dan pembayaran
- Laporan kehadiran siswa
- Analisis revenue
- Export ke PDF/Excel
- Dashboard analytics

---

## 🛠️ Tech Stack

### Backend
- **PHP 8.1+** - Server-side scripting
- **CodeIgniter 4** - PHP Framework dengan arsitektur MVC
- **MySQL** - Relational Database Management System

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **JavaScript (Vanilla)** - Interactivity dan dynamic content
- **SPA Architecture** - Single Page Application untuk halaman publik

### Development Tools
- **Composer** - PHP dependency management
- **NPM** - Node package management
- **Git** - Version control system

---

## 🏗️ Arsitektur Sistem

```
sonata-violin/
├── app/
│   ├── Config/         # Konfigurasi aplikasi
│   ├── Controllers/    # Business logic handlers
│   ├── Models/         # Database interaction layer
│   ├── Views/          # Presentation layer
│   ├── Filters/        # Middleware (Auth, RBAC)
│   ├── Helpers/        # Utility functions
│   └── Libraries/      # Custom libraries
├── public/
│   ├── assets/         # CSS, JS, Images
│   └── uploads/        # User uploaded files
├── writable/
│   ├── logs/           # Application logs
│   └── cache/          # Cache files
├── tests/              # Unit & Integration tests
└── vendor/             # Dependencies
```

### MVC Pattern Implementation

**Model** → Menangani logika data dan database
**View** → Menampilkan interface kepada user
**Controller** → Menghubungkan Model dan View, mengatur alur aplikasi

---

## 🔐 Role & Akses Pengguna

Sistem mengimplementasikan **Role-Based Access Control (RBAC)** dengan 3 level akses:

### 👑 Admin (Full Access)
**Hak Akses:**
- ✅ Full CRUD pada semua modul
- ✅ Manajemen user (tambah/edit/hapus Operator & Instruktur)
- ✅ Akses ke semua laporan dan analytics
- ✅ Konfigurasi sistem
- ✅ Verifikasi pembayaran
- ✅ Manajemen jadwal global

**Menu yang Dapat Diakses:**
- Dashboard
- Master Data (Instruktur, Ruangan, Siswa, Paket)
- Pendaftaran
- Verifikasi Pembayaran
- Jadwal Kelas
- Absensi
- Progress Kursus
- Laporan
- Manajemen User

---

### 🔧 Operator (Transaction Access Only)
**Hak Akses:**
- ✅ Verifikasi pembayaran pendaftaran
- ✅ Manajemen jadwal kelas
- ✅ Buka absensi kelas
- ❌ Tidak bisa menambah/edit/hapus data master
- ❌ Tidak bisa manajemen user

**Menu yang Dapat Diakses:**
- Dashboard (limited)
- Pendaftaran (tidak bisa edit data sembarangan)
- Verifikasi Pembayaran
- Jadwal Kelas
- Absensi

**Pembatasan Ketat:**
- Tidak bisa mengakses halaman manajemen user
- Tidak bisa mengubah data master
- Hanya bisa melihat laporan terbatas

---

### 🎓 Instruktur (Limited Access)
**Hak Akses:**
- ✅ Lihat jadwal kelas **yang diampu sendiri**
- ✅ Input absensi **kelas sendiri**
- ✅ Input progress **siswa di kelas sendiri**
- ❌ Tidak bisa melihat kelas instruktur lain
- ❌ Tidak bisa akses modul lainnya

**Menu yang Dapat Diakses (Hanya 3 Halaman):**
1. **Jadwal Kelas** - Filtered hanya kelas yang diampu
2. **Absensi** - Input kehadiran untuk kelas sendiri
3. **Progress Kursus** - Evaluasi siswa di kelas sendiri

**Sistem Filtering Otomatis:**
- Semua data yang ditampilkan otomatis difilter berdasarkan ID instruktur
- Instruktur tidak bisa mengakses atau melihat data kelas lain
- Protection di level database query untuk keamanan maksimal

---

## 💻 Instalasi

### Prerequisites

Pastikan sistem Anda sudah terinstall:
- PHP >= 8.1+
- MySQL >= 5.7
- Composer
- Node js & NPM (jika ingin menggunakan tailwind secara lokal)
- Git

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/Codenames-Ren/sonata-violin.git
   ```

2. **Masuk ke Direktori Project**
   ```bash
   cd sonata-violin
   ```

3. **Install Dependencies PHP**
   ```bash
   composer install
   ```

4. **Install Dependencies Frontend**
   ```bash
   npm install
   npm run build
   ```

5. **Konfigurasi Environment**
   ```bash
   # Copy file env menjadi .env
   cp env .env
   
   # Atau di Windows
   copy env .env
   ```

6. **Edit File .env**
   
   Buka file `.env` dan sesuaikan konfigurasi database:
   ```env
   database.default.hostname = localhost
   database.default.database = sonata_violin_db
   database.default.username = your_mysql_username
   database.default.password = your_mysql_password
   database.default.DBDriver = MySQLi
   ```

7. **Buat Database**
   ```sql
   CREATE DATABASE sonata_violin_db;
   ```

8. **Jalankan Migration**
   ```bash
   php spark migrate
   ```

9. **Jalankan Seeder**
   ```bash
   php spark db:seed UserSeeder
   ```

10. **Generate App Key (Opsional)**
    ```bash
    php spark key:generate
    ```

11. **Jalankan Development Server**
    ```bash
    php spark serve
    ```

12. **Akses Aplikasi**
    
    Buka browser dan akses: `http://localhost:8080`

---

## ⚙️ Konfigurasi

### Default Login Credentials

Setelah menjalankan seeder, gunakan kredensial berikut:

**Admin:**
```
Username: admin
Password: admin123
```

**Operator:**
```
Username: operator
Password: operator123
```

**Instruktur:**
```
Username: instruktur
Password: instruktur123
```

> ⚠️ **Penting:** Segera ganti password default setelah login pertama kali!

### Konfigurasi Upload

Edit file `app/Config/App.php` untuk mengatur:
- Maximum file upload size
- Allowed file types untuk bukti transfer
- Upload directory path

### Konfigurasi Email (Opsional)

Untuk fitur notifikasi email, edit `app/Config/Email.php` atau bisa di input di dalam .env:
```php
public $SMTPHost = 'your_smtp_host';
public $SMTPUser = 'your_email@domain.com';
public $SMTPPass = 'your_password';
```

---

## 📖 Penggunaan

### Untuk Calon Siswa

1. Akses halaman pendaftaran publik
2. Isi formulir pendaftaran lengkap
3. Pilih paket kursus yang diinginkan
4. Upload bukti transfer pembayaran
5. Notifikasi akan masuk ke Email (jika config email sudah di set sebelumnya)
6. Submit dan tunggu verifikasi dari admin

### Untuk Admin

1. Login ke dashboard admin
2. Kelola data master (instruktur, ruangan, paket)
3. Verifikasi pembayaran siswa baru
4. Atur jadwal kelas dengan smart scheduling
5. Monitor progress dan generate laporan

### Untuk Operator

1. Login ke dashboard operator
2. Verifikasi pembayaran pendaftaran
3. Atur jadwal kelas
4. Input absensi siswa

### Untuk Instruktur

1. Login ke dashboard instruktur
2. Cek jadwal mengajar
3. Input absensi siswa
4. Update progress pembelajaran siswa

---

## 🔒 Keamanan

### Implementasi Keamanan

- ✅ **RBAC (Role-Based Access Control)** - Pembatasan akses berdasarkan role
- ✅ **CSRF Protection** - Token validation pada setiap form
- ✅ **XSS Prevention** - Input sanitization dan output escaping
- ✅ **SQL Injection Prevention** - Prepared statements dan query builder
- ✅ **Password Hashing** - Menggunakan bcrypt untuk enkripsi password
- ✅ **Session Security** - Secure session handling dengan regeneration
- ✅ **File Upload Validation** - Strict validation untuk file upload
- ✅ **Authentication Middleware** - Filter untuk route protection

### Best Practices

- Selalu gunakan HTTPS di production
- Ganti semua default credentials
- Regular backup database
- Update dependencies secara berkala
- Monitor application logs
- Implementasi rate limiting untuk API endpoints

---

## 🎨 Fitur Unggulan

### Conflict Prevention System

Sistem scheduling dengan validasi bentrok otomatis:
- ✅ Deteksi bentrok instruktur (tidak bisa mengajar 2 kelas bersamaan diwaktu yang sama!)
- ✅ Deteksi bentrok ruangan (satu ruangan satu waktu)
- ✅ Validasi jam, hari, dan tanggal
- ✅ Alert visual jika terjadi konflik

### Smart Dashboard

- 📊 Real-time statistics
- 📈 Grafik pendapatan dan pendaftaran
- 📅 Upcoming schedule
- ⚠️ Alert dan reminder otomatis

### Responsive Design

- 📱 Mobile-friendly interface
- 💻 Optimized untuk desktop
- 🎨 Modern UI dengan Tailwind CSS
- ⚡ Fast loading dengan lazy loading

---

## 🤝 Kontribusi

Kontribusi selalu diterima! Jika Anda ingin berkontribusi:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### Guidelines

- Ikuti coding standards CodeIgniter 4
- Tulis kode yang clean dan readable
- Tambahkan comments untuk logika kompleks
- Test fitur sebelum submit PR
- Update dokumentasi jika diperlukan

---

## 📝 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👨‍💻 Developer

**Bayu Sukma**

- GitHub: [@Codenames-Ren](https://github.com/Codenames-Ren)
- Project Link: [https://github.com/Codenames-Ren/sonata-violin](https://github.com/Codenames-Ren/sonata-violin)

---

## 🙏 Acknowledgments

- CodeIgniter Team untuk framework yang awesome
- Tailwind CSS untuk styling framework
- Semua contributor yang telah membantu project ini

---

<div align="center">

**© 2025 Sonata Violin Project. All Rights Reserved.**

⭐ Jika project ini bermanfaat, jangan lupa berikan star!

[Report Bug](https://github.com/Codenames-Ren/sonata-violin/issues) • [Request Feature](https://github.com/Codenames-Ren/sonata-violin/issues)

</div>
