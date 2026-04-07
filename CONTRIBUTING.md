# Panduan Kontribusi (Contributing Guide)

Untuk menjaga kualitas kode dan konsistensi arsitektur, harap ikuti panduan berikut.

---

## 1. Persiapan Lingkungan (Setup)
Pastikan Anda sudah mengikuti langkah-langkah di [README.md](./README.md) untuk menjalankan aplikasi secara lokal, termasuk instalasi dependensi dan konfigurasi environment.

## 2. Alur Git (Gitflow)
Kami menggunakan model percabangan (branching) sederhana untuk menjaga stabilitas kode:
* **`main`**: Kode stabil yang siap untuk produksi.
* **`feature/nama-fitur`**: Untuk pengembangan fitur baru.
* **`hotfix/nama-bug`**: Untuk perbaikan bug mendesak di produksi.

**Langkah-langkah kerja:**
1. Lakukan `git pull origin main` untuk sinkronisasi kode terbaru.
2. Buat branch baru: `git checkout -b feature/nama-fitur-anda`.
3. Lakukan commit secara berkala.

## 3. Aturan Pesan Commit
Kami mengikuti standar [Conventional Commits](https://www.conventionalcommits.org/). Pesan commit harus spesifik dan bisa menggunakan Bahasa Indonesia atau Inggris.

* `feat:` Fitur baru (Contoh: `feat: add login with google`)
* `fix:` Perbaikan bug (Contoh: `fix: resolve null pointer on user profile`)
* `docs:` Perubahan dokumentasi.
* `refactor:` Perubahan kode tanpa mengubah fungsi (pembersihan kode).
* `feat and fix:` Gunakan `and` jika terpaksa menggabungkan dua hal (Contoh: `feat and fix: add otp and resolve mailer bug`).

## 4. Standar Kode (Coding Standards)
* **Bahasa**: Penamaan fungsi, variabel, dan file wajib menggunakan **Bahasa Inggris**.
* **Naming Convention**:
    * **Fungsi & Variabel**: `camelCase` (Contoh: `getUserData`)
    * **Class & Interface**: `PascalCase` (Contoh: `TutorRepositoryInterface`)
    * **Database (Tabel & Kolom)**: `snake_case` (Contoh: `telephone_number`)
    * **Constants/Enums**: `UPPER_SNAKE_CASE` (Contoh: `FILE_TYPE_CV`)

## 5. Proses Pull Request (PR)
Sebelum PR diajukan, pastikan:
1. Dokumentasi API di `openapi.yaml` sudah diperbarui (jika ada perubahan endpoint).
2. Kode sudah melalui pengujian lokal dan tidak ada *conflict* dengan branch `main`.

## 6. Architecture & Struktur Folder
Proyek ini menggunakan **Hexagonal Architecture** dengan pemisahan berbasis **Domains**. Struktur direktori diatur agar logika bisnis tidak tercampur dengan detail teknis.

```text
app/
├── Domains/
│   └── Ordering/                   <-- Fokus ke satu konteks bisnis
│       ├── Actions/                # (Logic) Alur proses bisnis tunggal
│       ├── Entities/               # (Data) Objek data murni (Plain PHP)
│       ├── Ports/                  # (Kontrak) Interface untuk Repository/Service
│       └── Infrastructure/         # (Detail Teknis / Adapter)
│           ├── Delivery/            # (Data) Objek data murni (Plain PHP)
│           |   ├── Console/     
│           │   |   └── Commands/    
│           │   └── Http/           <-- Entry Point (Driving Adapters)
│           │       ├── Controllers/ # Menangkap Request API
│           │       └── Requests/    # Validasi Form Request
│           ├── External/           <-- Integrasi Pihak Ketiga (API Luar)
│           │
│           ├── Repository/         <-- Database
│           │
│           └── Service/           <-- Mengambil action domain lain
│
├── Shared/                         <-- Komponen yang digunakan lintas domain
│   ├── Actions/                    # Logic umum (misal: UploadFile)
│   ├── Enums/                      # Enum global (Gender, FileType)
│   ├── Infrastructure/             # Implementasi alat (Mail, Queue, Storage)
│   ├── Ports/                      # Interface global
|
├── Models/                         <-- Model eloquent