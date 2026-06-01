# Sesi Pembelajaran Sequence Diagrams

Dokumen ini merangkum alur sesi pembelajaran pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, database, dan storage.

## 1. Pembatalan Sesi

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman formulir pembatalan sesi
	Client->>Backend: GET /schedule/getById?schedule_id=...
	Backend->>Backend: validasi schedule_id
	Backend->>DB: ambil detail schedule berdasarkan schedule_id
	DB-->>Backend: data schedule
	Backend-->>Client: 200 success + detail schedule

	Tutor->>Client: isi alasan pembatalan
	Tutor->>Client: klik tombol batalkan
	Client-->>Tutor: tampilkan pop up konfirmasi
	alt user klik ya
		Tutor->>Client: konfirmasi pembatalan
		Client->>Backend: POST /schedule/cancel
		Backend->>DB: update status schedule menjadi cancel
		DB-->>Backend: schedule diperbarui
		Backend-->>Client: 200 success
		Client-->>Tutor: sesi berhasil dibatalkan
	else user batal
		Client-->>Tutor: tutup pop up
	end
```

## 2. Formulir Terima Booking

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman formulir terima booking
	Client->>Backend: GET /schedule/getById?schedule_id=...
	Backend->>Backend: validasi schedule_id
	Backend->>DB: ambil detail schedule berdasarkan schedule_id
	DB-->>Backend: data schedule
	Backend-->>Client: 200 success + detail schedule

	alt learning_method adalah online
		Tutor->>Client: isi link zoom
	end

	Tutor->>Client: klik simpan dan kirim
	Client->>Backend: PATCH /tutor/schedule/booking-confirmation { decision: accept }
	Backend->>DB: update status booking menjadi accept
	DB-->>Backend: booking diperbarui
	Backend-->>Client: 200 success
	Client-->>Tutor: booking berhasil diterima
```

## 3. Halaman Manajemen Sesi

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman manajemen sesi
	Client->>Backend: GET /schedules?status=active
	Backend->>Backend: validasi filter status active
	Backend->>DB: ambil daftar schedule aktif milik tutor
	DB-->>Backend: daftar schedule aktif
	Backend-->>Client: 200 success + data active schedule

	Client->>Backend: GET /schedules?status=complete
	Backend->>Backend: validasi filter status complete
	Backend->>DB: ambil daftar schedule complete milik tutor
	DB-->>Backend: daftar schedule complete
	Backend-->>Client: 200 success + data complete schedule

	Tutor->>Client: klik tombol tandai sebagai selesai pada schedule aktif
	Client-->>Tutor: arahkan ke halaman laporan sesi
```

## 4. Halaman Laporan Sesi

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman laporan sesi
	Client->>Backend: GET /schedule/getById?schedule_id=...
	Backend->>Backend: validasi schedule_id
	Backend->>DB: ambil detail schedule berdasarkan schedule_id
	DB-->>Backend: data schedule
	Backend-->>Client: 200 success + detail schedule

	Tutor->>Client: isi topik yang dibahas
	Tutor->>Client: isi catatan untuk siswa
	Tutor->>Client: klik tombol kirim laporan
	Client->>Backend: POST /tutor/presence/create
	Backend->>DB: simpan laporan sesi + update status pertemuan
	DB-->>Backend: laporan berhasil disimpan
	Backend-->>Client: 200 success
	Client-->>Tutor: laporan sesi berhasil dikirim
```

## 5. Halaman Riwayat Sesi

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman riwayat sesi
	Client->>Backend: GET /schedule/getById?schedule_id=...
	Backend->>Backend: validasi schedule_id
	Backend->>DB: ambil detail riwayat sesi berdasarkan schedule_id
	DB-->>Backend: data riwayat sesi
	Backend-->>Client: 200 success + detail riwayat sesi
	Client-->>Tutor: tampilkan riwayat sesi
```

## Catatan

- Endpoint detail schedule berada di grup `auth:sanctum` pada [routes/api.php](../../routes/api.php).
- Endpoint pembatalan sesi berada di grup `auth:sanctum` pada [routes/api.php](../../routes/api.php).
- Endpoint konfirmasi booking tutor berada di grup `role:tutor` pada [routes/api.php](../../routes/api.php).
- Endpoint manajemen sesi menggunakan endpoint schedule pada grup `auth:sanctum` di [routes/api.php](../../routes/api.php).
- Endpoint laporan sesi tutor berada di grup `role:tutor` dan `verified.tutor` pada [routes/api.php](../../routes/api.php).
- Flow pembatalan sesi menampilkan alasan pembatalan dan konfirmasi pop up sebelum cancel.
- Flow terima booking menampilkan detail schedule dan pengisian link zoom jika jadwal berlangsung online.
- Flow manajemen sesi menampilkan section schedule active dan complete, serta aksi tandai selesai.
- Flow laporan sesi menampilkan form topik dan catatan siswa, lalu submit laporan sesi.
- Flow riwayat sesi menampilkan detail sesi berdasarkan schedule_id.
