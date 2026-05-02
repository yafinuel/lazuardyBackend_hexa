# Student Dashboard Sequence Diagrams

Dokumen ini merangkum alur dashboard untuk kategori student pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, database, dan storage.

## 1. Student Homepage

```mermaid
sequenceDiagram
	autonumber
	actor Student as Student
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database
	participant StorageApi as File Storage API

	Student->>Client: buka halaman dashboard student
	Client->>Backend: GET /student/dashboard/homepage
	Backend->>Backend: validasi request pagination
	Backend->>DB: ambil warning + biodata student
	DB-->>Backend: data student
	Backend->>DB: ambil notifikasi student
	DB-->>Backend: daftar notifikasi
	Backend->>DB: ambil rekomendasi tutor
	DB-->>Backend: daftar tutor
	Backend->>StorageApi: resolve profile photo tutor jika diperlukan
	StorageApi-->>Backend: url media
	Backend-->>Client: 200 success + data dashboard
	Client-->>Student: tampilkan homepage student
```

## 2. Student Schedule Page

```mermaid
sequenceDiagram
	autonumber
	actor Student as Student
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Student->>Client: buka halaman jadwal student
	Client->>Backend: GET /student/dashboard/schedule?date=...&paginate=...
	Backend->>Backend: validasi date dan paginate
	Backend->>Backend: parse tanggal menjadi objek waktu
	Backend->>DB: ambil jadwal student berdasarkan tanggal
	DB-->>Backend: daftar jadwal
	Backend-->>Client: 200 success + data schedule
	Client-->>Student: tampilkan jadwal student
```

## 3. Student Reports

```mermaid
sequenceDiagram
	autonumber
	actor Student as Student
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Student->>Client: buka halaman report student
	Client->>Backend: GET /student/reports?paginate=...
	Backend->>Backend: validasi paginate
	Backend->>DB: ambil semua report milik student
	DB-->>Backend: daftar report
	Backend->>Backend: transform report menjadi entity response
	Backend-->>Client: 200 success + data report
	Client-->>Student: tampilkan daftar report student
```

## Catatan

- Endpoint student dashboard berada di grup `role:student` pada [routes/api.php](../../routes/api.php).
- Endpoint report student berada di grup `role:student` pada [routes/api.php](../../routes/api.php).
- Flow homepage menampilkan warning, biodata student, notifikasi, dan rekomendasi tutor.
- Flow schedule menampilkan data jadwal berdasarkan tanggal yang dipilih.
- Flow reports menampilkan daftar report milik student.
