# Profile Mengajar Sequence Diagrams

Dokumen ini merangkum alur profile mengajar pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, database, dan storage.

## 1. Halaman Profile Mengajar

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman profile mengajar
	Client->>Backend: GET /getTutorById
	Backend->>Backend: validasi token dan ekstrak user_id
	Backend->>DB: ambil biodata tutor berdasarkan user_id
	DB-->>Backend: biodata tutor
	Backend-->>Client: 200 success + biodata tutor

	Client->>Backend: GET /schedule/getTutorSchedulesByDay
	Backend->>Backend: validasi parameter tanggal dan filter
	Backend->>DB: ambil data ketersediaan jadwal tutor
	DB-->>Backend: data ketersediaan jadwal
	Backend-->>Client: 200 success + jadwal ketersediaan
	Client-->>Tutor: tampilkan profile mengajar dan ketersediaan jadwal
```

## 2. Halaman Edit Profile Mengajar

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman edit profile mengajar
	Client->>Backend: GET /getTutorById
	Backend->>Backend: validasi token dan ekstrak user_id
	Backend->>DB: ambil biodata tutor berdasarkan user_id
	DB-->>Backend: biodata tutor
	Backend-->>Client: 200 success + biodata tutor

	Client->>Backend: GET /schedule/getTutorSchedulesByDay
	Backend->>Backend: validasi parameter tanggal dan filter
	Backend->>DB: ambil data ketersediaan jadwal tutor
	DB-->>Backend: data ketersediaan jadwal
	Backend-->>Client: 200 success + jadwal ketersediaan

	Tutor->>Client: ubah data profile mengajar dan ketersediaan jadwal
	Tutor->>Client: klik tombol save
	Client->>Backend: PUT /tutor/teaching-profile
	Backend->>Backend: validasi payload update profile mengajar
	Backend->>DB: simpan perubahan profile mengajar dan jadwal tutor
	DB-->>Backend: data profile mengajar diperbarui
	Backend-->>Client: 200 success
	Client-->>Tutor: perubahan profile mengajar berhasil disimpan
```

## Catatan

- Endpoint biodata tutor berada di grup role:tutor pada [routes/api.php](../../routes/api.php).
- Endpoint jadwal tutor per hari berada di grup auth:sanctum pada [routes/api.php](../../routes/api.php).
- Endpoint update profile mengajar berada di grup role:tutor dan verified.tutor pada [routes/api.php](../../routes/api.php).
- Flow profile mengajar menampilkan biodata tutor dan data ketersediaan jadwal.
- Flow edit profile mengajar mengambil data awal yang sama, lalu menyimpan perubahan lewat endpoint update teaching profile.
