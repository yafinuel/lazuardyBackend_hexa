# Lazuardy Mobile Backend

Backend API for Lazuardy Mobile Apps, an online course platform. Built with a focus on scalability and clean architecture using Laravel 12.

---

## 🛠 Tech Stack
* **Language:** PHP 8.4.12
* **Framework:** Laravel 12
* **Database:** MySQL
* **Authentication:** Laravel Sanctum
* **Payment Gateway:** Xendit
* **Architecture:** Hexagonal Architecture (Domain-Driven Design approach)

## 🚀 Quick Start

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

1.  **Clone & Install Dependencies**
    ```bash
    composer install
    ```

2.  **Environment Setup**
    Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
    ```bash
    cp .env.example .env
    ```

3.  **Database Migration & Seeding**
    Pastikan database sudah dibuat di MySQL sebelum menjalankan perintah ini.
    ```bash
    php artisan migrate --seed
    ```

4.  **Security & API Keys**
    Generate key aplikasi dan masukkan **Xendit API Key** (silakan hubungi stakeholder atau tim developer untuk mendapatkan key).
    ```bash
    php artisan key:generate
    ```

5.  **Run Development Server**
    ```bash
    php artisan serve
    ```

## 👥 Role Access
Sistem ini mendukung Multi-Role Authentication untuk tipe pengguna berikut:
* **Admin**: Mengelola kursus, verifikasi tutor, dan laporan keuangan.
* **Student**: Membeli kursus, mengakses materi, dan melihat progres belajar.
* **Tutor**: Membuat materi, mengatur jadwal, dan mengelola bimbingan.
* **Parent**: Memantau progres belajar anak (Student).

## 📄 Documentation
* **Panduan Kontribusi**: Lihat [CONTRIBUTING.md](./CONTRIBUTING.md) untuk standar kode dan alur kerja Git.
* **API Specs**: Dokumentasi endpoint tersedia melalui `docs/api/openapi.yaml`.
* **Panduan Edit API Docs**: Lihat [docs/api/apiDocGuide.md](./docs/api/apiDocGuide.md) untuk alur edit dokumentasi modular, lint, dan generate bundle.

---
*Maintained by Lazuardy Dev Team*