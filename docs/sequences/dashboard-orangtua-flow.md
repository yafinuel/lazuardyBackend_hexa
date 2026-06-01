# Parent Dashboard Sequence Diagrams

Dokumen ini merangkum alur dashboard untuk kategori parent pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, database, dan storage.

## 1. Home Page

```mermaid
sequenceDiagram
	autonumber
	actor Parent as Orang Tua
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database
	participant StorageApi as File Storage API

	Parent->>Client: buka halaman dashboard orang tua
	Client->>Backend: GET /parent/dashboard/homepage
	Backend->>Backend: validasi request pagination
	Backend->>DB: ambil data profil parent dan ringkasan dashboard
	DB-->>Backend: data parent
	Backend->>DB: ambil notifikasi parent
	DB-->>Backend: daftar notifikasi
	Backend->>DB: ambil data anak yang tertaut
	DB-->>Backend: data anak
	Backend->>StorageApi: resolve profile photo jika diperlukan
	StorageApi-->>Backend: url media
	Backend-->>Client: 200 success + data dashboard
	Client-->>Parent: tampilkan homepage parent
```

## 2. Schedule Page

```mermaid
sequenceDiagram
	autonumber
	actor Parent as Orang Tua
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Parent->>Client: buka halaman schedule
	Client->>Backend: GET /schedules
	Backend->>Backend: validasi parameter schedule
	Backend->>DB: ambil daftar schedule milik anak yang tertaut
	DB-->>Backend: daftar schedule
	Backend-->>Client: 200 success + data schedule
	Client-->>Parent: tampilkan halaman schedule
```

## 3. Report Page

```mermaid
sequenceDiagram
	autonumber
	actor Parent as Orang Tua
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Parent->>Client: buka halaman report
	Client->>Backend: GET /reports
	Backend->>Backend: validasi request report
	Backend->>DB: ambil daftar report milik anak yang tertaut
	DB-->>Backend: daftar report
	Backend-->>Client: 200 success + data report
	Client-->>Parent: tampilkan halaman report
```

## 4. Profile Page

```mermaid
sequenceDiagram
	autonumber
	actor Parent as Orang Tua
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database
	participant StorageApi as File Storage API

	Parent->>Client: buka halaman profile
	Client->>Backend: GET /parent/dashboard/profile-page
	Backend->>Backend: validasi token dan ekstrak user_id
	Backend->>DB: ambil data profil parent / data anak yang tertaut
	DB-->>Backend: data profile
	Backend->>StorageApi: resolve profile photo jika ada
	StorageApi-->>Backend: url media
	Backend-->>Client: 200 success + data profile
	Client-->>Parent: tampilkan profile parent
```

## Catatan

- Endpoint home page parent berada di grup `role:parent` pada [routes/api.php](../../routes/api.php).
- Endpoint schedule parent berada di grup `auth:sanctum` pada [routes/api.php](../../routes/api.php).
- Endpoint report parent berada di grup `auth:sanctum` pada [routes/api.php](../../routes/api.php).
- Endpoint profile parent berada di grup `role:parent` pada [routes/api.php](../../routes/api.php).
- Flow homepage menampilkan profil parent, notifikasi, data anak yang tertaut, dan media profil jika ada.
- Flow schedule menampilkan daftar jadwal milik anak yang tertaut.
- Flow report menampilkan daftar report milik anak yang tertaut.
- Flow profile menampilkan data profil parent dan informasi yang relevan jika tersedia.
