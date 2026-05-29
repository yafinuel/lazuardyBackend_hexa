# Book Tutor Sequence Diagrams

Dokumen ini merangkum alur pemilihan tutor pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, dan database.

## 1. Filter Page

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: klik kategori akademik atau umum
	Client->>Backend: GET /jenjang
	Backend->>DB: ambil daftar jenjang
	DB-->>Backend: data jenjang
	Backend-->>Client: data jenjang
	Client-->>User: tampilkan pilihan jenjang

	Client->>Backend: GET /getClassByLevel?level={level}
	Backend->>DB: ambil mata pelajaran sesuai jenjang
	DB-->>Backend: data mata pelajaran sesuai jenjang
	Backend-->>Client: data mata pelajaran sesuai jenjang
	Client-->>User: tampilkan mata pelajaran sesuai jenjang yang dipilih
```

## 2. Pilih Tutor Page

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman pilih tutor
	Client->>Backend: GET /getTutorByCriteria
	Backend->>DB: ambil tutor berdasarkan filter dari halaman sebelumnya
	DB-->>Backend: data tutor terfilter
	Backend-->>Client: data tutor terfilter
	Client-->>User: tampilkan daftar tutor yang sesuai
```

## 3. Detail Tutor Page

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman detail tutor
	Client->>Backend: GET /getTutorById
	Backend->>DB: ambil detail tutor
	DB-->>Backend: data detail tutor
	Backend-->>Client: data detail tutor

	Client->>Backend: GET /schedule/getTutorSchedulesByDay
	Backend->>DB: ambil ketersediaan jadwal tutor
	DB-->>Backend: data jadwal tutor
	Backend-->>Client: data jadwal tutor
	Client-->>User: tampilkan detail tutor dan jadwal tersedia

	User->>Client: pilih jadwal lalu lanjut ke summary
	Client->>Client: tampilkan halaman summary
	User->>Client: klik konfirmasi
	Client->>Backend: POST /schedule/takeMeeting
	Backend->>Backend: validasi data meeting
	Backend->>DB: simpan booking tutor
	DB-->>Backend: booking berhasil dibuat
	Backend-->>Client: 200 success
	Client-->>User: tampilkan popup konfirmasi booking
```

## Catatan

- Filter page menggunakan endpoint [GET /jenjang](../../routes/api.php) dan [GET /getClassByLevel](../../routes/api.php).
- Pilih tutor page menggunakan endpoint [GET /getTutorByCriteria](../../routes/api.php) untuk mengambil tutor yang sudah difilter.
- Detail tutor page menggunakan endpoint [GET /getTutorById](../../routes/api.php), [GET /schedule/getTutorSchedulesByDay](../../routes/api.php), dan [POST /schedule/takeMeeting](../../routes/api.php).
