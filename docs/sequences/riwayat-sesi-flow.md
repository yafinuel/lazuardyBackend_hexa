# Riwayat Sesi Sequence Diagrams

Dokumen ini merangkum alur riwayat sesi pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, dan database.

## 1. Halaman Daftar Riwayat Sesi

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman riwayat sesi
	Client->>Backend: GET /schedules
	Backend->>DB: ambil daftar sesi milik user aktif
	DB-->>Backend: data daftar sesi
	Backend-->>Client: data daftar sesi
	Client-->>User: tampilkan daftar riwayat sesi

	User->>Client: klik salah satu sesi
	Client-->>User: arahkan ke halaman detail sesi
```

## 2. Halaman Detail Sesi

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman detail sesi
	Client->>Backend: GET /schedule/getById?schedule_id={id}
	Backend->>DB: ambil detail sesi berdasarkan id
	DB-->>Backend: data detail sesi
	Backend-->>Client: data detail sesi
	Client-->>User: tampilkan detail sesi

	alt status sesi reported
		Client-->>User: tampilkan tombol konfirmasi selesai
		User->>Client: klik konfirmasi selesai
		Client->>Backend: PATCH /student/schedule/mark-as-complete
		Backend->>Backend: validasi data jadwal
		Backend->>DB: update status sesi menjadi completed
		DB-->>Backend: update berhasil
		Backend-->>Client: 200 success
		Client-->>User: status sesi diperbarui
	else status sesi completed
		Client-->>User: tampilkan tombol beri rating tutor
		User->>Client: klik beri rating tutor
		Client-->>User: arahkan ke halaman pemberian rating tutor
	else status sesi selain itu
		Client-->>User: tampilkan detail sesi saja
	end
```

## 3. Halaman Pemberian Rating Tutor

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: isi rating dan ulasan
	User->>Client: klik kirim rating
	Client->>Backend: POST /student/review/create
	Backend->>Backend: validasi rating dan ulasan
	Backend->>DB: simpan review tutor
	DB-->>Backend: review berhasil dibuat
	alt review berhasil
		Backend-->>Client: 200 success
		Client-->>User: tampilkan pop up success
	else review gagal
		Backend-->>Client: 500 / 422 error
		Client-->>User: tampilkan pop up gagal
	end
```

## Catatan

- Halaman daftar riwayat sesi menggunakan endpoint [GET /schedules](../../routes/api.php).
- Halaman detail sesi menggunakan endpoint [GET /schedule/getById](../../routes/api.php) dan alur lanjutannya bergantung pada status sesi.
- Halaman pemberian rating tutor menggunakan endpoint [POST /student/review/create](../../routes/api.php).
