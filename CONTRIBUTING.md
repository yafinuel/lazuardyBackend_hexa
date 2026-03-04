# Panduan Kontribusi (Contributing Guide)

## 1. Persiapan Lingkungan (Setup)
Pastikan Anda sudah mengikuti langkah-langkah di [README.md](./README.md) untuk menjalankan aplikasi secara lokal.

## 2. Alur Git (Gitflow)
Kami menggunakan model percabangan (branching) berikut:
* **`main`**: Kode stabil yang siap untuk produksi.
* **`feature/nama-fitur`**: Untuk pengembangan fitur baru.
* **`hotfix/nama-bug`**: Untuk perbaikan bug mendesak di produksi.

**Langkah-langkah:**
1. Lakukan `git pull origin main` untuk mendapatkan kode terbaru.
2. Buat branch baru: `git checkout -b feature/nama-fitur-anda`.
3. Lakukan commit dengan pesan yang jelas (Lihat aturan commit di bawah).

## 3. Aturan Pesan Commit
Kami mengikuti standar [Conventional Commits](https://www.conventionalcommits.org/), commit dapat dilakukan menggunakan Bahasa Indonesia maupun Bahasa Inggris:
* `feat:` untuk fitur baru (Contoh: `feat: add login with google`)
* `fix:` untuk perbaikan bug (Contoh: `fix: resolve null pointer on user profile`)
* `docs:` untuk perubahan dokumentasi.
* `refactor:` untuk perubahan kode yang tidak mengubah fungsi.
Perubahan dan commit harus diusahakan merujuk pada hal yang spesifik. Apabila sudah terlanjur maka gunakan and pada commit, seperti pada dibawah ini.
* `feat and fix: add new feature and resolve a problem`

## 4. Standar Kode (Coding Standards)
* **Bahasa**: Semua variabel dan fungsi menggunakan **Bahasa Inggris**.
* **Naming Convention**:
    * Fungsi & Variabel: `camelCase` (Contoh: `getUserData`)
    * Class: `PascalCase` (Contoh: `UserController`)
    * Database: `snake_case` (Contoh: `user_id`)

## 5. Proses Pull Request (PR)
Sebelum PR Anda disetujui (merged), pastikan:
1. Dokumentasi API di `openapi.yaml` sudah diperbarui jika ada perubahan endpoint.
2. Tidak ada *conflict* dengan branch tujuan.

## 6. Architecture & Struktur Folder
Arsitektur perangkat lunak yang kami gunakan adalah Hexagonal Architecture dengan menerapkan Domains partition. Sehingga peletakan folder dan file dalam struktur kita kita adalah sebagai berikut.
app/
├── Domains/
│   └── Ordering/           <-- Fokus ke satu urusan bisnis
│       ├── Actions/        # (Logic) Contoh: CreateOrderAction.php
│       ├── Models/         # (Data) Entity atau Eloquent khusus domain ini
│       ├── Ports/          # (Kontrak) OrderRepositoryInterface.php
│       ├── ValueObjects/   # (Aturan) OrderStatus.php, Price.php
│       └── Infrastructure/ # (Alat) EloquentOrderRepository.php
│
├── Shared/                 <-- Tempat hal-hal yang dipakai barengan
│   └── ValueObjects/       # Email.php, UUID.php (yang aturannya pasti sama)
│
├── Http/                   <-- Delivery Layer (Bawaan Laravel)
│   ├── Controllers/        # Menangkap Request API
│   └── Resources/          # Mengubah format JSON Response