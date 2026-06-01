# Tutor Dashboard Sequence Diagrams

Dokumen ini merangkum alur dashboard untuk kategori tutor pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, database, dan storage.

## 1. Tutor Homepage

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database
	participant StorageApi as File Storage API

	Tutor->>Client: buka halaman dashboard tutor
	Client->>Backend: GET /tutor/dashboard/homepage
	Backend->>Backend: validasi request pagination
	Backend->>DB: ambil warning + biodata tutor
	DB-->>Backend: data tutor
	Backend->>DB: ambil notifikasi tutor
	DB-->>Backend: daftar notifikasi
	Backend->>DB: ambil ringkasan jadwal tutor
	DB-->>Backend: ringkasan jadwal
	Backend->>DB: ambil data review / performa tutor
	DB-->>Backend: data performa tutor
	Backend->>StorageApi: resolve profile photo tutor jika diperlukan
	StorageApi-->>Backend: url media
	Backend-->>Client: 200 success + data dashboard
	Client-->>Tutor: tampilkan homepage tutor
```

## 2. Schedule Dashboard Page

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman schedule dashboard
	Client->>Backend: GET /schedules?status=active\&date=YYYY-MM-DD
	Backend->>Backend: validasi filter status
	Backend->>DB: ambil daftar schedule dengan status active
	DB-->>Backend: daftar schedule aktif
	Backend-->>Client: 200 success + data schedule aktif
	Client-->>Tutor: tampilkan schedule aktif

	Tutor->>Client: pilih aksi batalkan sesi
	Client-->>Tutor: arahkan ke halaman formulir pembatalan sesi
```

## 3. Konfirmasi Booking Page

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman konfirmasi booking
	Client->>Backend: GET /schedules?status=pending
	Backend->>Backend: validasi filter status pending
	Backend->>DB: ambil schedule tutor dengan status pending
	DB-->>Backend: daftar schedule pending
	Backend-->>Client: 200 success + data pending

	Client->>Backend: GET /schedules
	Backend->>Backend: validasi filter semua status
	Backend->>DB: ambil semua schedule tutor
	DB-->>Backend: daftar semua schedule
	Backend-->>Client: 200 success + data semua schedule

	Tutor->>Client: pilih schedule pending
	alt terima booking
		Tutor->>Client: klik tombol terima
		Client->>Backend: open formulir terima booking
		Backend-->>Client: tampilkan halaman formulir terima
	else tolak booking
		Tutor->>Client: klik tombol tolak
		Client-->>Tutor: tampilkan popup konfirmasi
		alt yakin tolak
			Tutor->>Client: konfirmasi tolak
			Client->>Backend: PATCH /tutor/schedule/booking-confirmation (decision=reject)
			Backend->>DB: update status booking menjadi reject
			DB-->>Backend: booking diperbarui
			Backend-->>Client: 200 success
			Client-->>Tutor: booking berhasil ditolak
		else batal
			Client-->>Tutor: tutup popup
		end
	end
```

## 4. Profile Page

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database
	participant StorageApi as File Storage API

	Tutor->>Client: buka halaman profile tutor
	Client->>Backend: GET /getTutorById (Bearer token)
	Backend->>Backend: validasi token dan ekstrak user_id
	Backend->>DB: ambil data tutor berdasarkan user_id
	DB-->>Backend: data tutor + biodata lengkap
	Backend->>Backend: resolve relasi class, subject, dan file refs
	Backend->>StorageApi: resolve profile photo / file URL jika ada
	StorageApi-->>Backend: url media
	Backend-->>Client: 200 success + TutorEntity
	Client-->>Tutor: tampilkan profile tutor lengkap
```

## Catatan

- Endpoint tutor dashboard homepage berada di grup `role:tutor` dan `verified.tutor` pada [routes/api.php](../../routes/api.php).
- Endpoint schedule dashboard dan konfirmasi booking berada di grup `role:tutor` pada [routes/api.php](../../routes/api.php).
- Endpoint profile tutor berada di grup `role:tutor` pada [routes/api.php](../../routes/api.php).
- Flow homepage menampilkan warning, biodata tutor, notifikasi, ringkasan jadwal, performa, dan media profil jika ada.
- Flow schedule menampilkan jadwal aktif saja.
- Flow konfirmasi booking menampilkan daftar pending dan semua status, lalu aksi terima/tolak untuk booking pending.
- Flow profile menampilkan biodata tutor lengkap beserta media profil jika ada.
